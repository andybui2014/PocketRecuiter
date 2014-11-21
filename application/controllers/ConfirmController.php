<?php

class ConfirmController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        //$this->_helper->_layout->setLayout('login');
    }

    public function indexAction()
    {

        $this->view->headerstring = "Confirm";
    }
    
     function doConfirmAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $useid=$request->getParam("UserID");
        $username = $request->getParam("emailaddress");
        $password = $request->getParam("password");
        $useid=base64_decode($useid);
        $username=base64_decode($username);
        $password=base64_decode($password);
        $authData = array('emailaddress' => $username, 'password' => $password);
        $userApi = new PR_Api_User();
        $client = $userApi->loadAndCheckAuthentication($authData);
        if(!empty($client)){
            $userApi->UpdateActive($useid);
            header("Location:".URL_BASE."login");
        }
       

              
        
                                
    }
    

}
?>