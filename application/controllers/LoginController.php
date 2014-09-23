<?php

class LoginController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        //$this->_helper->_layout->setLayout('login');
    }

    public function indexAction(){
        $this->view->headerstring = "Login";
    }
    public function doLoginAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $username = $request->getParam("email", "");
        $password = $request->getParam("password", "");
        $return = array("success" => 0, "error" => "");


        // login
        $clientApi = new PR_Api_Client();

        $authData = array('UserName' => $username, 'Password' => $password);
        if ($client = $clientApi->loadAndCheckAuthentication($authData))
        {
            PR_Session::setSession($client,PR_Session::SESSION_CLIENT);
            //$session = new Zend_Session_Namespace('MyAppName');
            //$session->user = 'hauangivay-hauoi-chanqua';
            $return['success'] = 1;
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