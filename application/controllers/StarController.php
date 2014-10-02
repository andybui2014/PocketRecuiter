<?php

class StarController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        //$this->_helper->_layout->setLayout('login');
         //$this->_helper->_layout->setLayout(LAYOUT . 'login');
    }

    public function indexAction()
    {

        $this->view->headerstring = "Star";
    }
    
    
    

}
?>