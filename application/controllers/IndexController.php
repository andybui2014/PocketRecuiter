<?php

class IndexController extends Application_Controller_Action {

    public function init() {
        parent::init();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        if ($this->view->ajax == 1 && empty($client)) {
            $this->_helper->json(array('logined' => 0));
        } else if (empty($client)) {
           // $this->_helper->redirector("index", "login");
             $this->_helper->redirector("index", "star");
        }
    }

    public function indexAction() 
    {        
        //echo("Index-Index-page: ");echo($this->view->page);die();
    }

}
