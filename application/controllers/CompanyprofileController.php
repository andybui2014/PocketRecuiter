<?php

class CompanyprofileController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        if(empty($client))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    
    public function listAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
       // $clientID =  $sestionClient['UserID'];
       
        $api = new PR_Api_Core_ClientClass();
        $companylists = $api->getListCompany();
       
        $this->view->companylists = $companylists;

      
    }
    public function profileAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("companyid");
        $api = new PR_Api_Core_ClientClass();
        $company = $api->getCompany($companyid);
        $this->view->company=$company;
       
        $this->render('companyprofileview');
       
      
    }
    public function editprofileAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("companyid");
        $api = new PR_Api_Core_ClientClass();
        $company = $api->getCompany($companyid);
        $this->view->company=$company;
       // echo ("companyid:".$companyid);//print_r($companyid);
        $this->render('editprofile');
       
      
    }
     public function doEditprofileAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("CompanyID");
        $api = new PR_Api_Core_ClientClass();
         $return = array("success" => 0, "error" => "");
        $params = $request->getParams();
       $updateFields=array();
       foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
           
        echo ("companyid:".$companyid);print_r($updateFields);die();
       $result = $api->updatecompanyProfile($companyid,$updateFields);
       if($result)
       {
           $return["success"]=1;
       }
       else{
           $return["success"]=1;
       }
       $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);
      
    }
    

}
