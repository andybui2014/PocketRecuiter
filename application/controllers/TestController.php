<?php

class TestController extends Application_Controller_Action {

    public function init() {
        parent::init();
        // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("user");print_r($user);
        if(empty($user))
        {
            $this->_helper->redirector("index","login");
        }
    }

    public function indexAction() {
        //echo("Index-Index-page: ");echo($this->view->page);die();
        $core = new PR_Api_Core_TestClass();
        $companyID = 1;
        $records = $core->getTestListForCompany($companyID);
        $this->view->list =  $records;

    }

}
