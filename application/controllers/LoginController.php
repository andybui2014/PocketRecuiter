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
	public function resetPassAction(){
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
		$return = array("success" => 0, "error" => "","UserID"=>"");
        $emailaddress = $request->getParam("emailaddress");
		$core= new PR_Api_Core_UserClass();
		$ismail=$core->checkUser($emailaddress);
		//echo "username:";print_r($ismail["UserID"]);die();
		if(empty($ismail["Error"])){
		$UserID=$ismail["UserID"];
			  $pageURL = 'http';
                if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}} 
                $pageURL .= "://";
                if ($_SERVER["SERVER_PORT"] != "80") {
                    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
                } else {
                    $pageURL .= $_SERVER["SERVER_NAME"];
                }
                //echo $pageURL;
                //send mail
                $toEmail = $emailaddress; //$techEmail;base64_encode($User["password"])
                $fromName = "Pocket Recruiter";
                $fromEmail = "info@vienetllc.com";
                $link=$pageURL.URL_BASE."resetpass?UserID=".base64_encode($UserID)."&&emailaddress=".base64_encode($emailaddress);
                $subject = "Pocket Recruiter Password Retrieval";
                $body = "Hello,
				
Please click on the following link to reset your password:     
                                                
".$link." 
            
Thank you,                    
Your Pocket Recruiter Team
                ";
               
                $mail = new PR_Api_Core_Mail();
               // $mail1=new PR_Mail();
                $mail->setBodyText($body);
                $mail->setFromName($fromName);
                $mail->setFromEmail($fromEmail);
                $mail->setToEmail($toEmail);
                $mail->setSubject($subject);
                $mail->send();
				$return['success'] = 1;
                $return['UserID'] = $UserID;
		}else{
		$return['success'] = 0;
		$return['error'] = $ismail["Error"];
		}
		
		$response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);    
	}
    

}
?>