<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UserController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'User', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $user = User::find($parameters);
        if (count($user) == 0) {
            $this->flash->notice("The search did not find any user");

            $this->dispatcher->forward([
                "controller" => "user",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $user,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $user = User::findFirstByid($id);
            if (!$user) {
                $this->flash->error("user was not found");

                $this->dispatcher->forward([
                    'controller' => "user",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->id);
            $this->tag->setDefault("user_group_id", $user->user_group_id);
            $this->tag->setDefault("first_name", $user->first_name);
            $this->tag->setDefault("last_name", $user->last_name);
            $this->tag->setDefault("login", $user->login);
            $this->tag->setDefault("pass", $user->pass);
            $this->tag->setDefault("ordering", $user->ordering);
            $this->tag->setDefault("failed_login_attempt", $user->failed_login_attempt);
            $this->tag->setDefault("locked_date", $user->locked_date);
            $this->tag->setDefault("author", $user->author);
            $this->tag->setDefault("last_editor", $user->last_editor);
            $this->tag->setDefault("created", $user->created);
            $this->tag->setDefault("modified", $user->modified);
            $this->tag->setDefault("disable", $user->disable);
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user = new User();
        $user->userGroupId = $this->request->getPost("user_group_id");
        $user->firstName = $this->request->getPost("first_name");
        $user->lastName = $this->request->getPost("last_name");
        $user->login = $this->request->getPost("login");
        $user->pass = $this->request->getPost("pass");
        $user->ordering = $this->request->getPost("ordering");
        $user->failedLoginAttempt = $this->request->getPost("failed_login_attempt");
        $user->lockedDate = $this->request->getPost("locked_date");
        $user->author = $this->request->getPost("author");
        $user->lastEditor = $this->request->getPost("last_editor");
        $user->created = $this->request->getPost("created");
        $user->modified = $this->request->getPost("modified");
        $user->disable = $this->request->getPost("disable");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user was created successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user = User::findFirstByid($id);

        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user->userGroupId = $this->request->getPost("user_group_id");
        $user->firstName = $this->request->getPost("first_name");
        $user->lastName = $this->request->getPost("last_name");
        $user->login = $this->request->getPost("login");
        $user->pass = $this->request->getPost("pass");
        $user->ordering = $this->request->getPost("ordering");
        $user->failedLoginAttempt = $this->request->getPost("failed_login_attempt");
        $user->lockedDate = $this->request->getPost("locked_date");
        $user->author = $this->request->getPost("author");
        $user->lastEditor = $this->request->getPost("last_editor");
        $user->created = $this->request->getPost("created");
        $user->modified = $this->request->getPost("modified");
        $user->disable = $this->request->getPost("disable");
        

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'edit',
                'params' => [$user->id]
            ]);

            return;
        }

        $this->flash->success("user was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = User::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => "index"
        ]);
    }

}
