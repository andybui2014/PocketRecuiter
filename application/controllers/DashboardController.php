<?php

class DashboardController extends Application_Controller_Action {

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

        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);

        $CompanyID = $sestionClient['CompanyID'];
        $clientID =  $sestionClient['UserID'];

        $Oppotunity_PR_Api = new PR_Api_Core_CareerClass();

        $getListOpp = $Oppotunity_PR_Api->getListOpportunity(array('CompanyID'=>$CompanyID));
        $notiClass = new PR_Api_Core_NotiClass();
        $messageList = $notiClass->getList($clientID);
        $this->view->getListOpp = $getListOpp;
        $this->view->messageList = $messageList;
    }

}
