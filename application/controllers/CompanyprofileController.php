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
        $this->render("list");
      
    }
    
    public function profileAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("companyid");
        $api = new PR_Api_Core_ClientClass();
        $company = $api->getCompany($companyid);
        $this->view->company=$company;
		$Industry=$company["Industry"];
		$listOrtherCompany=$api->getCompany_Industry($Industry);
		//echo("tetstt:<pre>");print_r($listOrtherCompany); echo("</pre>");
		$this->view->listOrtherCompany=$listOrtherCompany;
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
        $contrylist=$api->getListCountry();
		$this->view->countrylist=$contrylist;
		//echo ("Tetst:<pre>");print_r($contrylist);echo("</pre>");

        $this->render('editprofile');
      
      
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
      // header('Location: http://www.example.com/'); 
      
      /* $return["success"]=1;  
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
                ->setBody($return);   */
      
    }

    
   
    public function addcompanyAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);             
        //$api = new PR_Api_Core_ClientClass();
    
        $this->render("addcompany");
      
    }
    public function doAddCompanyAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER); 
        $params = $request->getParams();
        $filename = "";
        if (isset($_FILES["images"])) {
            if ($_FILES["images"]["error"] > 0) {
                $params["images"]="";
            } else {
                $filename = uniqid() ."_". $_FILES["images"]["name"];
                move_uploaded_file($_FILES["images"]["tmp_name"], DIR_MEDIA_COMPANY_PROFILE . $filename);
                $params["images"]=$filename;
                
            }
        }
        
               
             $Fields=array();
            foreach($params as $key=>$values) 
            {
                $Fields[$key]=$values;
            }    
           
    
        $api = new PR_Api_Core_ClientClass();
        $result = $api->AddCompany($Fields);
       
       // header("Location: list");                      
        header("Location: profile?companyid=$result");
      
    }
     public function deletecompanyAction()
     {        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
                
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyid = $request->getParam("companyid");   
       
        $api = new PR_Api_Core_ClientClass();
        $result = $api->deleteCompany($companyid);
        header("Location: list");
        
      
      } 
	public function uploadVideoAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
		$companyid = $request->getParam("companyid");
        $api = new PR_Api_Core_ClientClass();
        $company = $api->getCompany($companyid);
        $this->view->company=$company;
		$this->render('upload-video');
       
      
    }
	 public function doUploadVideoAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("testt:");print_r($user);
        $core=new PR_Api_Core_ClientClass(); 
        $CompanyID=$client["CompanyID"];
        $return = array("status" => "", "message" => "","CompanyID"=>"");    
        $allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma","flv","mpeg","mov","m4v","dat","aac","avi");
        $extension = pathinfo($_FILES['myfile']['name'],PATHINFO_EXTENSION);
        $mimes = array(
        'image/jpeg', 'image/png', 'image/gif', 'image/pjpeg', 'video/mp4', 'audio/mp3', 'audio/wma'
        );
        if(in_array($extension, $allowedExts)){  
        sleep(2);
        
        if (isset($_FILES['myfile'])) {
            $fileName = $_FILES['myfile']['name'];
            $fileType = $_FILES['myfile']['type'];
            $fileError = $_FILES['myfile']['error'];
            $fileStatus = array(
                'status' => 0,
                'message' => '' 
            );
            if ($fileError== 1) { 
                $fileStatus['message'] = 'Size over the allowed limit';
                $fileStatus['status'] = 0;
            //} //elseif (!in_array($fileType, $mimes)) { 
              //  $fileStatus['message'] = 'format error';
              //  $fileStatus['status'] = 0; 
            } else { 
                move_uploaded_file($_FILES['myfile']['tmp_name'], DIR_MEDIA_VIDEO.$fileName);
                $fileStatus['status'] = 1;
                $fileStatus['message'] = "Completed";
                $core->uploadVideo($CompanyID,$fileName);
            }   
                $return["status"]= $fileStatus['status'];
                $return["message"]= $fileStatus['message'];
				$return["CompanyID"]= $CompanyID;
               // header("Location: profile");  
             echo json_encode($return);
            
  

        
            }    } else echo "format error";  
             
       
            
     }
   
    


    

}
