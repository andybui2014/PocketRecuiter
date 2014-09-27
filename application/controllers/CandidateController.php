<?php

class CandidateController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $candidate = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("client");print_r($client);
        if(empty($candidate))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    public function doLogoutAction()
    {
        $this->_helper->layout->disableLayout();

        PR_Session::clearSessions();
        
        $this->_helper->redirector("index","login");
    }   
    
    public function startProfileAction()
    {
        
    }
        public function profileAction()
    {
         $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
         $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        
        $this->view->client = $Api->getUserArray($authData);
       
        $this->render('profile');
        //$api=new PR_Api_Core_Skill();
        //$skill= $api->getSkillArray('5');
        //echo ("Skill:<pre>");print_r($skill);echo("</pre>");
        
        
    }
     
}
