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
        $userApi = new PR_Api_User();

        $authData = array('emailaddress' => $username, 'password' => $password);
        $client = $userApi->loadAndCheckAuthentication($authData);
        //if ($client = $userApi->loadAndCheckAuthentication($authData))
        if(!empty($client) && $client["active"]==1 )
        {
            PR_Session::setSession($client,PR_Session::SESSION_USER);
            $user = PR_Session::getSession(PR_Session::SESSION_USER);
            //echo("user:");print_r($user);die();
            $userID = $user['UserID'];
            //update Lastsigned
           // echo ("userID:".$userID);
            $userApi->UpdateLastsigned($userID);
            $return['success'] = 1;
            $return['usertype'] = $user["usertype"];
            
            if($user["usertype"]==USER_TYPE_CANDIDATE){
                $coreCandi = new PR_Api_Core_CandidateClass();
                $coreCandi->createCandidateProfileID($userID);
            }
            
        } else
        {
           // $return['error'] = PR_Api_Error::getInstance()->getFirstError();
       $return['error']="Account not activated. Please check email and active.";
        }//echo ("etsttt:<pre>");print_r($client);echo("</pre>");die();
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