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
       
       
        $Oppotunity_PR_Api = new PR_Api_Core_CareerClass();
        $getListOpp = $Oppotunity_PR_Api->getListOpportunity(array('CompanyID'=>$companyid));
        $this->view->getListOpp = $getListOpp;
       
        $this->render('companyprofileview');
       
      
    }
    public function editprofileAction()
    {
        $response = $this->getResponse();//new
        $response->clearAllHeaders()->clearBody();//new
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("companyid");
        $api = new PR_Api_Core_ClientClass();
        $company = $api->getCompany($companyid);
        $this->view->company=$company;
        
       // echo ("companyid:".$companyid);//print_r($companyid);
        $this->render('editprofile');
      // print_r($_FILES);
      
    }
     public function doEditProfileAction()
    {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
                
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("CompanyID");      
        $api = new PR_Api_Core_ClientClass();
         $return = array("success" => 0, "error" => "");
           $params = $request->getParams(); 
       // $filename=$request->getParam("images");

        
       $updateFields=array();
       foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
       if(isset($updateFields["Company_Logo_Rm"]) && $updateFields["Company_Logo_Rm"]==1)
            {
                $updateFields["images"]="";
            }
            $filename = "";
        if (isset($_FILES["images"])) {
            if ($_FILES["images"]["error"] > 0) {
                echo ("upload errors.");
            } else {
                $filename = uniqid() ."_". $_FILES["images"]["name"];
                move_uploaded_file($_FILES["images"]["tmp_name"], DIR_MEDIA_COMPANY_PROFILE . $filename);
                $updateFields["images"]=$filename;
                
            }
        }

       $result = $api->updatecompanyProfile($companyid,$updateFields);
       header("Location: profile?companyid=$companyid");
     
      
    }

    
   
        
   
    


    

}
