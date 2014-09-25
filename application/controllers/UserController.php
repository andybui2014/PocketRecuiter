<?php

class UserController extends Application_Controller_Action
{
    public function init()
    {
         parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        echo("user");print_r($user);
        if(empty($user))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    public function doLogoutAction()
    {
        $this->_helper->layout->disableLayout();

        Zsis_Session::clearSessions();
        
        $this->_helper->redirector("index","login");
    }   
}
