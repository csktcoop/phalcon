<?php

use App\Model\User;

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{

    public function initialize()
    {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            $this->tag->setDefault('login', 'rattonwing@yahoo.com');
            $this->tag->setDefault('password', '123');
        }
    }
    
    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        $this->session->set("auth",
            [
                "id"         => $user->id,
                "first_name" => $user->first_name,
                "name"       => "$user->first_name $user->last_name",
                "roleId"     => 1,
                "roleName"   => 'user',
            ]
        );
        
    }
    
    /**
     * This action authenticate and logs a user into the application
     */
    public function startAction()
    {
        if ($this->request->isPost()) {
            // Get the data from the user
            $post = array_bind($this->request->getPost(),
                [
                    'login'    => '',
                    'password' => '',
                ]
            );
            
            // Find the user in the database
            $user = User::findFirst(
                [
                    "(login = :login: OR email = :login:) AND pass = :password:",
                    "bind" => $post
                ]
            );
            
            if ($user !== false) {
                $this->_registerSession($user);
                $this->flash->success("Welcome " . $this->session->get('auth')['name']);
                
                $this->response->redirect("user");
            }

            $this->flash->error(
                "Wrong email/password"
            );
        }

        // Forward to the login form again
        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }
    
    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');

        return $this->dispatcher->forward(
            [
                "controller" => "index",
                "action"     => "index",
            ]
        );
    }
    
}