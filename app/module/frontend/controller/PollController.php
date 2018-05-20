<?php

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PollController extends ControllerBase
{

	public function initialize()
    {
    	parent::initialize();
    	$this->tag->setTitle("Poll");
        $this->view->setTemplateAfter("header");
        $this->view->setTemplateBefore("footer");
    }
	
	/**
     * Index action
     */
    public function indexAction()
	{
        $numberPage = $this->request->getQuery("page", "int", 1);
        if ($this->request->isPost()) {
        	if ($this->request->isPost())
            {
        		// Store search data
	            $query = Criteria::fromInput($this->di, 'App\Model\Poll', $_POST);
	            $this->persistent->parameters = $query->getParams();
	            $parameters = $this->persistent->parameters;
			} else {
				// Re-populate fields
            	Phalcon\Tag::setDefaults($parameters['bind']);
			}
            
	        $poll = App\Model\Poll::find($parameters);
			
	        if (count($poll) == 0) {
	            $this->flash->notice("The search did not find any poll");
	            return;
	        }

	        $paginator = new Paginator([
	            'data' => $poll,
	            'limit'=> 10,
	            'page' => $numberPage
	        ]);
	        //@TODO hide paginator.nav_buttons when only page 1

	        $this->view->page = $paginator->getPaginate();
        }
    }
	
    /**
     * Searches for poll
     */
    public function searchAction()
    {
        
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a poll
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $poll = Poll::findFirstByid($id);
            if (!$poll) {
                $this->flash->error("poll was not found");

                $this->dispatcher->forward([
                    'controller' => "poll",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $poll->id;

            $this->tag->setDefault("id", $poll->id);
            $this->tag->setDefault("content", $poll->content);
            $this->tag->setDefault("author", $poll->author);
            $this->tag->setDefault("editor", $poll->editor);
            $this->tag->setDefault("created", $poll->created);
            $this->tag->setDefault("modified", $poll->modified);
            $this->tag->setDefault("disable", $poll->disable);
            
        }
    }

    /**
     * Creates a new poll
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'index'
            ]);

            return;
        }

        $poll = new Poll();
        $poll->content = $this->request->getPost("content");
        $poll->author = $this->request->getPost("author");
        $poll->created = $this->request->getPost("created");
        $poll->editor = $this->request->getPost("editor");
        $poll->modified = $this->request->getPost("modified");
        $poll->disable = $this->request->getPost("disable");
        

        if (!$poll->save()) {
            foreach ($poll->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("poll was created successfully");

        $this->dispatcher->forward([
            'controller' => "poll",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a poll edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $poll = Poll::findFirstByid($id);

        if (!$poll) {
            $this->flash->error("poll does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'index'
            ]);

            return;
        }

        $poll->content = $this->request->getPost("content");
        $poll->author = $this->request->getPost("author");
        $poll->editor = $this->request->getPost("editor");
        $poll->created = $this->request->getPost("created");
        $poll->modified = $this->request->getPost("modified");
        $poll->disable = $this->request->getPost("disable");
        

        if (!$poll->save()) {

            foreach ($poll->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'edit',
                'params' => [$poll->id]
            ]);

            return;
        }

        $this->flash->success("poll was updated successfully");

        $this->dispatcher->forward([
            'controller' => "poll",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a poll
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $poll = Poll::findFirstByid($id);
        if (!$poll) {
            $this->flash->error("poll was not found");

            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'index'
            ]);

            return;
        }

        if (!$poll->delete()) {

            foreach ($poll->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "poll",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("poll was deleted successfully");

		return $this->response->redirect('poll/index');
		/*return $this->response-redirect([
		    'controller' => 'poll',
		    'action'     => 'index',
		    'params'     => implode('/',$params_array)
    	]);*/
    }
	
}