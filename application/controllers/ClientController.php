<?php

class ClientController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
        $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
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
    public function profileAction()
    {
        $this->render('profile');
        
    }
     
}
