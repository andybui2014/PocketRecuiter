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
         $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $username = $client["UserName"];
        $password = $client["Password"];
         $clientApi = new PR_Api_Client();
        $authData = array('UserName' => $username, 'Password' => $password);
        $this->view->client = $clientApi->getClientArray($authData);
        //$a= $clientApi->getClientArray($authData);
       // echo ("username:".$username);
        //echo ("password:".$password);
       // echo ("test:<pre>");print_r($a);echo("</pre>");
        $this->render('profile');
        
    }
     
}
