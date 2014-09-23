<?php

class LoginController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        //$this->_helper->_layout->setLayout('login');
    }

    public function indexAction()
    {

        $this->view->headerstring = "Login";
    }

}
?>