<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');


/**
 * The FactoryDefault Dependency Injector automatically registers the services that
 * provide a full stack framework. These default services can be overidden with custom ones.
 */
$di = new FactoryDefault;

/**
 * Include web environment specific services
 */
require APP_PATH . '/config/services_api.php';

/**
 * Include Autoloader
 */
include APP_PATH . '/config/loader.php';

/**
 * Handle the request
 */
$app = new Micro($di);

// event.checkACL
$app->before(function() use ($app)
{
	$acl = $app->session->get('acl');
	if ( !$acl )
	{
        $acl = new Phalcon\Acl\Adapter\Memory;
        
        $acl->setDefaultAction(Phalcon\Acl::DENY);
        
		// Basic action presets
		$manage = ['', 'get', 'create', 'update', 'delete'];
		$forbid = [];
		$index  = array_slice($manage, 0, 1);
        $read   = array_slice($manage, 0, 2);
        $write  = array_slice($manage, 2, 2);
        $rw     = array_slice($manage, 0, 4);
        $error    = ['show404', 'show500'];
        $error401 = array_merge($error, ['show401']);
        // Extend
        $authRead = ['get', 'end'];
        $authMan  = array_merge($authRead, array_slice($manage, 2, 3));
        $pollRead = array_merge($read, ['vote']);
        $pollMan  = array_merge($manage, ['vote']);
        
        // Roles mapping
        $roles =[      'guest',     'user',       'admin'];
        $allow = [
        ''          => [$forbid,    $index],
        'error'     => [$error,     $error401,    $error],
        'poll'      => [$read,      $pollRead,    $pollMan],
        'auth'      => [$index,     $authRead,    $authMan],
		];
        
        // Add roles
        foreach ($roles as $role) {
            $acl->addRole( new Phalcon\Acl\Role($role) );
        }
        
        // Set permissions
        foreach ($allow as $resource => $actions) {
            // Add resources
            $actionSet = array_merge_sub($actions);
            $acl->addResource(new Phalcon\Acl\Resource($resource), $actionSet);
            
            // Set permission
            foreach ($roles as $roleId => $role) {
                $actionSet = isset($actions[$roleId])
                	? $actions[$roleId]
                	: end($actions);
                
                $acl->allow($role, $resource, $actionSet);
            }
        }
        
        $app->session->set('acl', $acl);
	}
	
	// Who's using this system?
	$auth = [
        "id"         => 0,
        "name"       => "Guest",
        "roleId"     => 0,
        "roleName"   => 'guest',
    ];
	$auth = (object) array_merge($auth, (array)$app->session->get('auth'));
	
	//@TODO https://docs.phalconphp.com/en/latest/application-micro#using-controllers-as-handlers
	// List requested components
	$def  = array_fill(0, 3, "");
	$uri  = $app->router->getRewriteUri();
	$frag = explode("/", $uri);array_shift($frag);
	list($module, $ctrl, $act) = array_replace($def, $frag);
	
    $isAllow = $acl->isAllowed($auth->roleName, $ctrl, $act);
    
    if (!$isAllow) {
    	$code = $acl->isAllowed($auth->roleName, 'error', 'show401') ? 401 : 404;
		return $app->response->setJsonContent(
    	[
    		'statusx' => $code,
    	 	'msg'    => $app->lang->_($code."")
		])->send();
		exit;
	}
});
// event.checkACL

// API
$app->get(
    '/api',
    function ()
    {
    	$user = $this->session->get('auth');
    	$user = $user ? $user->first_name : 'guest';
    	
        return $this->response->setJsonContent([
            'status' => 0,
            'msg'    => "Restful API Phalcon. " . $this->lang->_('greeting user',[
            	'user' => $user
            ]),
        ]);
    }
);

// API.AUTH
$app->post(
    '/api/auth',
    function ()
    {
    	if ($this->session->get("auth")) {
			return $this->response->setJsonContent([
	            'status' => 0,
	            'msg'    => "OK",
	        ]);
		}
    	
//        $request = (array)$this->request->getJSONRawBody();
        $request = $this->request->getPost();
        $request = array_bind($request,
        [
            'login' => '',
            'pass'  => '',
        ]);
        
        $phql = 'SELECT * FROM App\Model\User WHERE login = :login: AND pass = :pass: AND disable IS NULL';

        $result = $this->modelsManager->executeQuery($phql, $request);
        
        if ($result->valid()) {
        	$user = $result->getFirst();
			$this->session->set("auth", (object)
            [
                "id"         => $user->id,
                "first_name" => $user->first_name,
                "name"       => "$user->first_name $user->last_name",
                "roleId"     => 2,
                "roleName"   => 'admin',
            ]);
            
            return $this->response->setJsonContent([
	            'status' => 0,
	            'msg'    => "OK",
	        ]);
		}
        
        return $this->response->setJsonContent([
            'status' => 1,
            'msg'    => "Login failed",
        ]);
    }
);

// API.AUTH.GET(id)
$app->get(
    '/api/auth/get/{id:[0-9]+}',
    function ($id)
    {
    	$phql = 'SELECT * FROM App\Model\User WHERE id = ?0 AND disable IS NULL';

        $data = $this->modelsManager->executeQuery($phql, [$id])->getFirst();

        return $this->response->setJsonContent(
        	($data === false)
        	? [
        		'status' => 0,
        	 	'msg'    => 'NOT FOUND'
			]
        	: $data
        );
    }
);

// APP.AUTH.END
$app->get(
    '/api/auth/end',
    function ()
    {
    	$this->session->remove("auth");
    	
    	return $this->response->setJsonContent([
	            'status' => 0,
	            'msg'    => "OK",
	        ]);
    }
);

// API.POLL
$app->get(
    '/api/poll',
    function ()
    {
        $phql = 'SELECT * FROM App\Model\Poll WHERE disable IS NULL ORDER BY created DESC';

        $data = $this->modelsManager->executeQuery($phql);
        
        return $this->response->setJsonContent($data);
    }
);

// API.POLL.GET(id)
$app->get(
    '/api/poll/get/{id:[0-9]+}',
    function ($id)
    {
        $phql = 'SELECT * FROM App\Model\Poll WHERE id = :id: AND disable IS NULL';

        $data = $this->modelsManager->executeQuery($phql, [
            'id' => $id,
        ])->getFirst();

        return $this->response->setJsonContent(
        	($data === false)
        	? [
        		'status' => 0,
        	 	'msg'    => 'NOT FOUND'
			]
        	: $data
        );
    }
);

// API.POLL.GET(text)
$app->get(
    '/api/poll/get/{text}',
    function ($text) {
        $phql = 'SELECT * FROM App\Model\Poll WHERE content LIKE :text: AND disable is NULL ORDER BY created DESC';

        $data = $this->modelsManager->executeQuery($phql, [
            'text' => "%$text%"
        ]);

        return $this->response->setJsonContent($data);
    }
);

// API.POLL.UPDATE
$app->post(
    '/api/poll/update',
    function ()
    {
//        $request = (array) $this->request->getJSONRawBody();
        $request = $this->request->getPost();

		if (isset($request['id'])) {
			// Update/Delete
			$defs = [
				'content' => '',
				'disable' => NULL,
			];
			
			$id      = $request['id'];unset($request['id']);
			$request = array_merge($defs, array_intersect_key($request,$defs));
			$request['id'] = $id;
			
			$phql = 'UPDATE App\Model\Poll SET
			content = :content:,
			disable = :disable:
				WHERE id = :id:';
		} else {
			$phql = 'INSERT INTO App\Model\Poll (content) VALUES (:content:)';
		}

        $status = $this->modelsManager->executeQuery($phql, $request);

        // Check if the editing was successful
        if ($status->success() === true) {
            // Change the HTTP status
            $this->response->setStatusCode(201, 'Created');
            
            if (!isset($request['id'])) $request['id'] = $status->getModel()->id;
            
            $this->response->setJsonContent([
                'status' => 0,
                'msg'    => "OK",
                'data'   => $request,
            ]);
        } else {
            // Change the HTTP status
            $this->response->setStatusCode(409, 'Conflict');

            // Send errors to the client
            $errors = [];

            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $this->response->setJsonContent([
                'status' => -1,
                'msg'    => $errors,
            ]);
        }

        return $this->response;
    }
);

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
	return $app->response->setJsonContent([
        'status' => "404",
        'msg'    => "NOT FOUND",
    ]);
});

$app->handle();