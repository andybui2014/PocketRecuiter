<?php

class ResetpassController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        //$this->_helper->_layout->setLayout('login');
    }

    public function indexAction()
    {

        $this->view->headerstring = "Resetpass";
         $request = $this->getRequest();
        $params = $request->getParams();
        $useid=$request->getParam("UserID");
        $username = $request->getParam("emailaddress");
        //$password = $request->getParam("password");
        $useid=base64_decode($useid);
        $username=base64_decode($username);
		$this->view->emailaddress=$username;
		$this->view->useid=$useid;
		
       
    }
    
     function doResetPassAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
		$return = array("success" => 0, "error" => "","UserID"=>"");
        $useid=$request->getParam("UserID");
        $username = $request->getParam("emailaddress");
       
        $password=$params["password"];
        $authData = array('emailaddress' => $username, 'password' => $password);
        $userApi = new PR_Api_User();
        $client = $userApi->loadAndCheckAuthentication($authData);
        if(!empty($useid)){
            $userApi->UpdatePass($useid,$password);
			$return['success'] = 1;
          //  header("Location:".URL_BASE."login");
       }
       // header("Location:".URL_BASE."login");
       $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return); 

              
        
                                
    }
    

}
?>