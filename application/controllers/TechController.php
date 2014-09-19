<?php

class TechController extends Application_Controller_Action {

    public function init(){
        parent::init();
        $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        if(empty($client)){
            $this->_helper->redirector("index","login");
        }
    }

    public function indexAction(){
       // $this->_helper->_layout->setLayout(LAYOUT . 'login');
        //$this->render('profile');
    }
    public function listAction(){

    }
}
?>
