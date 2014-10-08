<?php

class ClientController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);ououi
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
       // echo("client");print_r($client);
        if(empty($client))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    
    
    public function startProfileAction()
    {
        
    }
    public function profileAction()
    {
         $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
         $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        
        $this->view->client = $Api->getUserArray($authData);
       
        $this->render('profile');
        
        
        
    }
    function updateProfleAction()
    {
       $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
		// echo ("params:<pre>");print_r($params);echo("</pre>");die();
         $client = PR_Session::getSession(PR_Session::SESSION_USER);
      //  $username = $request->getParam("UserName", "");
      //  $password = $request->getParam("Password", "");
        $return = array("success" => 0, "error" => "");
        $clientID=$client["UserID"];
       
       $clientClass = new PR_Api_Core_ClientClass();
       $updateFields=array();
       foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
           
        //echo ("test:<pre>");print_r($updateFields);echo("</pre>");
       // echo ("clientID:".$clientID);
       $result = $clientClass->updateClientProfile($clientID,$updateFields);
    // echo ("result:".$result);
       if ($result) {
           $return['success'] = 1;
                
             }
             else{
                 $return['success'] = 1;
             // $return['error'] = PR_Api_Error::getInstance()->getFirstError();
                }
     
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);                    


        
    }
     
    public function listuserAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $clientID =  $sestionClient['UserID'];
        $companyid=$request->getParam("CompanyID");
        $role=$sestionClient["Role"];
       // $companyid=$_REQUEST;
       // echo("companyid:");print_r($companyid);
        $api = new PR_Api_Core_ClientClass();
        
        $Companylists=$api->getListCompany();
        $this->view->Role=$role;
       
        $this->view->Companylists = $Companylists;
        $Userlists = $api->getListUsersCompany($companyid);
        
       // echo ("companylists:<pre>");print_r($Userlists);echo("</pre>");die();
        
        $this->view->Userlists = $Userlists;
        $this->render("listuser");

      
    }
    public function edituserAction()
    {
        $request = $this->getRequest();
         $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $userid = $request->getParam("UserID");
      //  echo ("userid:");print_r($client);die();
        $role=$client["Role"];
        $companyid=$request->getParams();
        //echo("CompanyID");print_r($companyid);
         $Api = new PR_Api_User();
        $authData = array('UserID' => $userid);
        
        $this->view->client = $Api->getListUserArray($authData);
        $this->view->companyid=$companyid;
        $this->view->Role=$role;
        $this->render('userprofile');
        
        
        
    }
     public function doEditUsereAction()
        {        
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            $request = $this->getRequest();
                    
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
            $userid = $request->getParam("UserID");     
            $api = new PR_Api_Core_ClientClass();
            $return = array("success" => 0, "error" => "");
            $company=$api->getCompanyid($userid);
            $companyid=$company["CompanyID"];
            $params = $request->getParams(); 
           // echo ("params:<pre>");print_r($params);echo("</pre>");die();
            $updateFields=array();
            foreach ($params as $key => $value) {
                $updateFields[$key]=$value;            
            }
            if(isset($updateFields["activate"]))
            {
                $updateFields["activate"]=1;
            }
            else{
                $updateFields["activate"]=0;
            }
           
           
          // echo ("updateFields:<pre>");print_r($updateFields);echo("</pre>");die();
            $result = $api->updateUserProfile($userid,$updateFields);
            header("Location: listuser?CompanyID=".$companyid);
          
        }
         public function deleteusereAction()
        {        
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            $request = $this->getRequest();
                    
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
            $userid = $request->getParam("UserID");     
            $api = new PR_Api_Core_ClientClass();
            $result = $api->deleteUser($userid);
            header("Location: listuser?CompanyID=1");
          
        }
        public function adduserAction()
        {
            $request = $this->getRequest();
             $client = PR_Session::getSession(PR_Session::SESSION_USER);
             $role=$client["Role"];
             $this->view->Role=$role;     
            $this->render('adduser');
           // echo ("role:".$role);
            
            
        }
        public function doAddUsereAction()
        {        
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            $request = $this->getRequest();
            $params = $request->getParams();
             $Fields=array();
            foreach($params as $key=>$values) 
            {
                $Fields[$key]=$values;
            }  
             if(isset($Fields["activate"]))
            {
                $Fields["activate"]=1;
            }
            else{
                $Fields["activate"]=0;
            } 
            $api = new PR_Api_Core_ClientClass();
           //echo ("updateFields:<pre>");print_r($Fields);echo("</pre>");  die();  
              $result = $api->AddUser($Fields);
               header("Location: listuser?CompanyID=1");
          
        }
        
       
     
}
