<?php

namespace App\Plugin;

use Phalcon\Acl;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * Security
 *
 * Handles ACL controller/actions
 */
class Security extends Plugin
{
	/**
	 * Returns an existing or new access control list
	 *
	 * @returns Acl\Adapter\Memory
	 */
	public function getAcl($roles)
    {
        // $roleDefaultName  = 'guest';
        // $auth             = $this->session->get("auth");
        
        // $dispatcher = $this->getDI()->getdispatcher();
        // $resource   = $dispatcher->getControllerName();
        // $action     = $dispatcher->getActionName();
        
        // Get current role
        // $role   = isset($auth['roleName']) ? $auth['roleName'] : $roleDefaultName;
        // $roleId = isset($auth['roleId'])   ? $auth['roleId']   : 0;
        
		if (!isset($this->persistent->acl))
        {
			$acl = new Acl\Adapter\Memory;
			$acl->setDefaultAction(Acl::DENY);

            // Action presets
            $canIndex = ['index'];
            $canRead  = array_merge($canIndex, ['detail']);
            $canWrite = ['new', 'edit', 'delete', 'review', 'save'];
            $canRW    = array_merge($canRead, $canWrite);
            $error    = ['show404', 'show500'];
            $error401 = array_merge($error, ['show401']);
            $sess     = ['index', 'register', 'start'];
            $sessUser = ['index', 'end'];
            
            // Role map
            //                 'guest',      'user',      'admin'
            $allow = [
                'user'      => [[],          $canRead,    $canRW],
                'poll'      => [$canRead,    $canRead,    $canRW],
                
                'index'     => [$canIndex],
                'error'     => [$error401,   $error401,   $error],
                'session'   => [$sess,       $sessUser,   $sessUser],
            ];
            //@TODO put this map into DB
            
            // Add roles
            foreach ($roles as $role) {
                $acl->addRole( new Acl\Role($role) );
            }
            
            foreach ($allow as $resource => $actions) {
                // Add resources
                $actionAll = array_unique(call_user_func_array('array_merge', $actions));
                $acl->addResource(new Acl\Resource($resource), $actionAll);
                $useDefaultId = count($actions) == 1;
                
                // Set permission
                foreach ($roles as $roleId => $role) {
                    $roleId = $useDefaultId ? 0 : $roleId;
                    $acl->allow($role, $resource, $actions[$roleId]);
                }
            }
            
			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}
        
		return $this->persistent->acl;
	}

    /**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        // Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get("auth");

        $roles = [
            'guest', 'user', 'admin'
        ];
        $auth = $this->session->get("auth");
        
        $role = isset($auth['roleName']) ? $auth['roleName'] : current($roles);

        // Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        $action     = $dispatcher->getActionName();
        
        // Obtain the ACL list
        $acl = $this->getAcl($roles);

        // The requested controller isn't enlisted
        if (!$acl->isResource($controller)) {
			$dispatcher->forward([
				'controller' => 'error',
				'action'     => 'show404'
			]);

			return false;
		}
        
        // Check if the Role have access to the controller (resource)
        $allowed = $acl->isAllowed($role, $controller, $action);

        if (!$allowed) {
            // If he doesn't have access forward him to the index controller
            $this->flash->error(
                "Access denied."
            );

            $dispatcher->forward([
				'controller' => 'error',
				'action'     => 'show401'
			]);
            echo "b4killSess";
            $this->session->destroy();
            
            // Returning "false" we tell to the dispatcher to stop the current operation
            return false;
        }
    }
}