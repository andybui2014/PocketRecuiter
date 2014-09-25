<?php

class LoginController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        //$this->_helper->_layout->setLayout('login');
    }

    public function indexAction()
    {

        $this->view->headerstring = "Login";
    }
    
     function doLoginAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $username = $request->getParam("email", "");
        $password = $request->getParam("password", "");
        $return = array("success" => 0, "error" => "usertype",""=>"");

        //echo ("username:".$username);
        // login
        $clientApi = new PR_Api_Client();

        $authData = array('emailaddress' => $username, 'password' => $password);
        if ($client = $clientApi->loadAndCheckAuthentication($authData))
        {
            PR_Session::setSession($client,PR_Session::SESSION_USER);
            $user = PR_Session::getSession(PR_Session::SESSION_USER);
           // echo("user:");print_r($user);
            //$session = new Zend_Session_Namespace('MyAppName');
            //$session->user = 'hauangivay-hauoi-chanqua';
            $return['success'] = 1;
            $return['usertype'] = $user["usertype"];
        } else
        {
            $return['error'] = PR_Api_Error::getInstance()->getFirstError();
        }
        //return
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);                    
                                
    }
    

}
?>