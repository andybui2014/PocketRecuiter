<?php
   class RegisterController extends Application_Controller_Action {

    public function init() {
        parent::init();
        
    }

    public function indexAction() 
    {   
       /* $api=new PR_Api_Client();
        $firstname= "Nguyen";
        $lasttname= "Carot";
        $username= "CR";
        $email= "test@pr.com";
        $pass= "Test1234";
        
        $data=array(
                "FirstName" => $firstname,
                "LastName" => $lasttname,
                "UserName" => $username,
                "Email1" => $email,
                "Password" => $pass
                );
        $tets=$api->registerClient($data);
        if($tets)
        {
            echo ("isert thanh cong");do-register
        }
        */
        
    }
     public function doRegisterAction() 
    {   
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $return = array("success" => 0, "error" => "","usertype"=>"");
 //  echo ("params:<pre>");print_r($params);echo("</pre>");die();
         $firstname= $params["firstname"];
        $lasttname= $params["lastname"];
        $Acount_type= $params["usertype"];
        $email= $params["emailaddress"];
        $pass= $params["password"];
        $About_us=$params["About_us"];
        //$loginname=$params["loginname"];
               
        $data=array(
                "firstname" => $firstname,
                "lastname" => $lasttname,
                "usertype" => $Acount_type,
                "emailaddress" => $email,
                "password" => $pass,
                "HeardFrom" => $About_us
                //"loginname" => $loginname
                );
             // echo ("data:<pre>");print_r($data);echo("</pre>");die();
       // $api=new PR_Api_Register();
       $api= new PR_Api_Core_Register();
      
       
        if(isset($params["accept"]) )
        {
           // echo("test");die();
            $tets=$api->registerClient($data);
        }
        
        
       // print_r($tets);
       if($tets["error"]=="")
       {
           $return['success'] = 1;
           $userApi = new PR_Api_User();
            $authData = array('emailaddress' => $data["emailaddress"], 'password' => $data["password"]);
            if ($User = $userApi->loadAndCheckAuthentication($authData))
            {
                PR_Session::setSession($User,PR_Session::SESSION_USER);
                $user = PR_Session::getSession(PR_Session::SESSION_USER);
               // echo("user:");print_r($user);hgj
                
                $return['success'] = 1;
                $return['usertype'] = $user["usertype"];
            } 
       }
      else
        {
            $return['success'] = 0;
            $return['error'] =$tets["error"] ;
        }
        //return
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);  
     // echo("testt:".$tets["error"]);
        
        
    }

}
?>
