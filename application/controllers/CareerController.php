<?php

class CareerController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        if(empty($client))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    
    public function careerlistAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        /*
          $clientID =  $sestionClient['ClientID'];
          $notiClass = new PR_Api_Core_NotiClass();
          $result = $notiClass->getList($clientID);
          $this->view->result = $result; //UserName
          $this->view->clientIDLogin = $clientID;
          $this->view->userName = $sestionClient['UserName']; */
    }

    public function careercreateAction()
    {
        $request = $this->getRequest();
        $sestionUSER = PR_Session::getSession(PR_Session::SESSION_USER);
        $PR_Api_Core_Career = new PR_Api_Core_CareerClass();

        $resultCareerList = $PR_Api_Core_Career->getListCareer();
        $resultSkillList = $PR_Api_Core_Career->getListSkill();

/*
        echo "<pre>";
            print_r($resultSkillList);
        echo "</pre>"; die();
*/
        $this->view->resultCareerList = $resultCareerList;
        $this->view->resultSkillList = $resultSkillList;
        /*
          $clientID =  $sestionClient['ClientID'];
          $notiClass = new PR_Api_Core_NotiClass();
          $result = $notiClass->getList($clientID);
          $this->view->result = $result; //UserName
          $this->view->clientIDLogin = $clientID;
          $this->view->userName = $sestionClient['UserName']; */
    }
    public function previewpostAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        /*
          $clientID =  $sestionClient['ClientID'];
          $notiClass = new PR_Api_Core_NotiClass();
          $result = $notiClass->getList($clientID);
          $this->view->result = $result; //UserName
          $this->view->clientIDLogin = $clientID;
          $this->view->userName = $sestionClient['UserName']; */
    }

}
