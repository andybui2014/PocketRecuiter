<?php

class CandidateController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        echo("client");print_r($client);
        if(empty($client))
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
    
     
}
