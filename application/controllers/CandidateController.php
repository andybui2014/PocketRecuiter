<?php

class CandidateController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $candidate = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("client");print_r($client);
        if(empty($candidate))
        {
            $this->_helper->redirector("index","login");
        }  
    }

    public function indexAction(){

    }

    public function doLogoutAction()
    {
        $this->_helper->layout->disableLayout();

        PR_Session::clearSessions();
        
        $this->_helper->redirector("index","login");
    }

    public function detailEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = isset($params['id']) ? $params['id'] : null;
            $core = new PR_Api_Core_CandidateClass();
            $ajaxRes['info'] = $core->getCandidateEmployment($id);
            //echo '<pre>';
            //print_r($ajaxRes['info']);
            //echo '</pre>';
            //die();

            //Convert m/d/Y StartDate
            $startDate = $ajaxRes['info']['StartDate'];
            if (($timestamp = strtotime($startDate)) !== false)
                $startDate = date("m/d/Y", $timestamp);
            $ajaxRes['info']['StartDate'] = $startDate;

            //Convert m/d/Y EndDate
            $endDate = $ajaxRes['info']['EndDate'];
            if (($timestamp = strtotime($endDate)) !== false)
                $endDate = date("m/d/Y", $timestamp);
            $ajaxRes['info']['EndDate'] = $endDate;

            if(sizeof($ajaxRes['info']) > 0) $ajaxRes['success'] = 1;

        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function profileBuilderAction(){
        $params = $this->getRequest()->getParams();
        if(!empty($params) && isset($params['utm_source'])){
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
            $this->view->step = $params['utm_source'];
            switch($params['utm_source']){
                case 'contact':
                    $core = new PR_Api_Core_CandidateClass();
                    $info = $core->getContactInfo($client['UserID']);
                    $this->view->stepCount = '1/5 Steps';
                    $this->view->info = $info;
                    $this->render('profile-builder/contact');
                    break;
                case 'education':
                    $core = new PR_Api_Core_CandidateClass();
                    $list = $core->getCandidateEducationList($client['UserID']);
                    $this->view->list = $list;
                    $this->view->stepCount = '2/5 Steps';
                    $this->render('profile-builder/education');
                    break;
                case 'employment':
                    $core = new PR_Api_Core_CandidateClass();
                    $list = $core->getCandidateEmployments($client['UserID']);
                    $this->view->list = $list;
                    $this->view->stepCount = '3/5 Steps';
                    $this->render('profile-builder/employment');
                    break;
                case 'skills':
                    $core = new PR_Api_Core_CandidateClass();
                    $this->view->list = $core->getListAll_CandidateSkills($client['UserID']);
                    $this->view->stepCount = '4/5 Steps';
                    $this->render('profile-builder/skills');
                    break;
                case 'portfolio':
                    $core = new PR_Api_Core_CandidateClass();
                    $list = $core->getListCandidatePortfolio($client['UserID']);
                    $this->view->list = $list;
                    $this->view->stepCount = '5/5 Steps';
                    $this->render('profile-builder/portfolio');
                    break;
                case 'success':
                    $this->view->stepCount = 'Completed';
                    $this->render('profile-builder/success');
                    break;
                default:
                    $this->render('profile-builder/index');
                    break;
            }
        }else{
            $this->render('profile-builder/index');
        }
    }

    public function startProfileAction(){

    }
    public function doUpdateEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            if(!empty($params['data']) && sizeof($params['data'])){
                //$client = PR_Session::getSession(PR_Session::SESSION_USER);

                $instName = null;
                $degreeName = null;
                $startYear = null;
                $endYear = null;
                $eduId = null;
                foreach($params['data'] as $item){

                    if($item['name']=='eduId')          $eduId = $item['value'];
                    if($item['name']=='inst-name')      $instName = $item['value'];
                    if($item['name']=='degree-name')    $degreeName = $item['value'];
                    if($item['name']=='start-year')     $startYear = $item['value'];
                    if($item['name']=='end-year')       $endYear = $item['value'];
                }
                if(empty($eduId)) $errors['eduId'] = 1;
                if(empty($instName)) $errors['inst-name'] = 1;
                if(empty($degreeName)) $errors['degree-name'] = 1;
                if(empty($startYear)) $errors['start-year'] = 1;
                if(empty($endYear)) $errors['end-year'] = 1;

                if(empty($errors)){
                    $core = new PR_Api_Core_CandidateClass();
                    $core->updateCandidateEducation($eduId,$instName,$degreeName,$startYear,$endYear);
                    $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }
            }
            $response = $this->getResponse();
            $response->clearAllHeaders()->clearBody();
            $ajaxRes = json_encode($ajaxRes);
            $response->setHeader('Content-type', 'application/json');
            $response->setHeader('Content-Length', strlen($ajaxRes), true)
                ->setBody($ajaxRes);
        }
    }
    public function detailEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = !empty($params['id']) ? $params['id'] : 0;
            if($id > 0){
                //$client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $res = $core->getCandidateEducation($id);
                $startdate = $res['startdate'];
                $enddate = $res['enddate'];
                if (($timestamp = strtotime($startdate)) !== false)
                    $res['startdate'] = date("Y", $timestamp);
                if (($timestamp = strtotime($enddate)) !== false)
                    $res['enddate'] = date("Y", $timestamp);

               $ajaxRes['info'] = $res;
               $ajaxRes['success']  = 1;
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function stepNextContactAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $data = array();
                $errors = array();
                foreach($params['data'] as $item){
                    if($item['name']=='firstname'){
                        if(empty($item['value'])){
                            $errors['firstname'] = 1;
                        }else{
                            $data['firstname'] = $item['value'];
                        }
                    }
                    if($item['name']=='lastname'){
                        if(empty($item['value'])){
                            $errors['lastname'] = 1;
                        }else{
                            $data['lastname'] = $item['value'];
                        }
                    }
                    if($item['name']=='email'){
                        if(empty($item['value'])){
                            $errors['email'] = 1;

                        }else{
                            if (!filter_var($item['value'], FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 1;
                            }else{
                                $data['emailaddress'] = $item['value'];
                            }
                        }
                    }

                    if($item['name']=='phone')          $data['PhoneNumber']  = $item['value'];
                    if($item['name']=='url')            $data['URL']  = $item['value'];
                    if($item['name']=='city')           $data['City']  = $item['value'];
                    if($item['name']=='country')        $data['Country']  = $item['value'];
                    if($item['name']=='zipcode')        $data['PostalCode']  = $item['value'];

                }

                if(empty($errors)){
                    $core = new PR_Api_Core_CandidateClass();
                    $core->saveContactInfo($client['UserID'],$data);
                    $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = $errors;
                }

            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function doDeletePortfolioAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $core = new PR_Api_Core_CandidateClass();
            if(!empty($params['data']) && sizeof($params['data']) > 0){
                foreach($params['data'] as $key=>$value){
                    $core->deleteCandidatePortfolio($value);
                }
            }
        }
        $ajaxRes['success'] = 1;
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function doAddPortfolioAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            $title = null;
            $url = null;
            $description = null;

            $client = PR_Session::getSession(PR_Session::SESSION_USER);

            foreach($params['data'] as $item){
                if($item['name']=='title')         $title = $item['value'];
                if($item['name']=='url')           $url = trim($item['value']);
                if($item['name']=='description')   $description = $item['value'];
            }
            if(empty($title))       $errors['title'] = 1;
            if(empty($url))         $errors['url'] = 1;
            //if(empty($description)) $errors['description'] = 1;

            if(empty($errors)){
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidatePortfolio($client['UserID'],$title,$url,$description,null);
                if($isSuccess) $ajaxRes['success'] = 1;
            }else{
                $ajaxRes['info'] = $errors;
            }


        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doAddEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $errors = array();
                $instName = null;
                $degreeName = null;
                $startYear = null;
                $endYear = null;

                foreach($params['data'] as $item){
                    if($item['name']=='inst-name')      $instName = $item['value'];
                    if($item['name']=='degree-name')    $degreeName = $item['value'];
                    if($item['name']=='start-year')     $startYear = $item['value'];
                    if($item['name']=='end-year')       $endYear = $item['value'];
                }

                if(empty($instName)) $errors['inst-name'] = 1;
                if(empty($degreeName)) $errors['degree-name'] = 1;
                if(empty($startYear)) $errors['start-year'] = 1;
                if(empty($endYear)) $errors['end-year'] = 1;

                if(empty($errors)){
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidateEducation($client['UserID'],$instName,$degreeName,$startYear,$endYear);
                    if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
        }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doRemoveEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = $params['id'];
            $core = new PR_Api_Core_CandidateClass();
            $core->deleteCandidateEducation($id);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doRemoveEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = $params['id'];
            $core = new PR_Api_Core_CandidateClass();
            $core->deleteCandidateEmployment($id);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function doUpdateEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $empId = null;
            $companyName = null;
            $positionHeld = null;
            $startDate = null;
            $endDate = null;
            $description = null;
            $errors = array();

            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')          $empId = $item['value'];
                    if($item['name']=='companyName')    $companyName  = $item['value'];
                    if($item['name']=='posotionHeld')   $positionHeld = $item['value'];
                    if($item['name']=='startDate')      $startDate = $item['value'];
                    if($item['name']=='endDate')        $endDate = $item['value'];
                    if($item['name']=='description')    $description = $item['value'];
                }
                if(empty($empId)) $errors['empId'] = 1;
                if(empty($companyName)) $errors['companyName'] = 1;
                if(empty($positionHeld)) $errors['posotionHeld'] = 1;
                if(empty($startDate)) $errors['startDate'] = 1;
                //if(empty($endDate)) $errors['endDate'] = 1;
                //if(empty($description)) $errors['description'] = 1;

                if(empty($errors)){
                    //$client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->updateCandidateEmployment($empId,$companyName,$positionHeld,$startDate,$endDate,$description);
                if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doAddEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            $companyName = null;
            $posotionHeld = null;
            $startDate = null;
            $endDate = null;
            $description = null;
            $errors = array();
            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='companyName')    $companyName  = $item['value'];
                    if($item['name']=='posotionHeld')   $posotionHeld = $item['value'];
                    if($item['name']=='startDate')      $startDate = $item['value'];
                    if($item['name']=='endDate')        $endDate = $item['value'];
                    if($item['name']=='description')    $description = $item['value'];
                }

                if(empty($companyName)) $errors['companyName'] = 1;
                if(empty($posotionHeld)) $errors['posotionHeld'] = 1;
                if(empty($startDate)) $errors['startDate'] = 1;
                //if(empty($endDate)) $errors['endDate'] = 1;
                //if(empty($description)) $errors['description'] = 1;

                if(empty($errors)){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidateEmployment($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description);
                if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }


            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
   
    public function watchListAction(){
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        
        $CandidateID =$user['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $wlist = $PR_Api->getWatchList(array('CandidateID'=>$CandidateID));

        $this->view->wlist = $wlist;
    }

    public function deleteWatchListAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $OpportunityID = $request->getParam("OpportunityID","");
        $PR_Api = new PR_Api_Core_CandidateClass();
        $res = $PR_Api->deleteWatchList($OpportunityID);

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $res = json_encode($res);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($res), true)
            ->setBody($res);
    }
	 public function profileAction()
    {           
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
                
        $api_candidate= new PR_Api_Core_CandidateClass();
        
        $getUserArray=$api_candidate->getCandidateInfo($UserID);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=$api_candidate->getList_CandidateSkillsOnly($UserID);
        $Skills=array();
        if(!empty($SkillName)||$SkillName!=""){             
        
            foreach($SkillName as $key=>$values )
            {
               $Skills[$key]=$values;   
            }
        }
        $this->view->SkillName=$Skills;
        $CandidateEmployment=array();
        if(!empty($getCandidates["CandidateEmploymentID"])||$getCandidates["CandidateEmploymentID"]!="")
        {
              foreach($getCandidates["CandidateEmploymentID"] as $key=>$values )
                {
                   $CandidateEmployment[$key]=$values;   
                }
        }
        
        $this->view->CandidateEmployment=$CandidateEmployment;
        $Education = $api_candidate->getCandidateEducationList(2); 
        $this->view->Education=$Education;
         
     //  echo ("getUserArray:<pre>");print_r($a);echo("</pre>");
        $this->render('profile');                  
                     
    }
     public function addSkillsAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params= $request->getParams();
        $skills=$params["skills"]; 
       // echo("testt:<pre>");print_r($skills);echo("</pre>");  die();
        $core=new PR_Api_Core_CandidateClass();
        $skillname=array();
        $skillid=array(); 
        $listskill=array();
        if($skills!="" || !empty($skills))
        {
            foreach ($skills as $skill)
            {
                $skillID=$core->get_skill($skill);
                
                 array_push($skillname,$skillID[0]["SkillName"]);  

               
            }
        }
        $result["SkillName"]=$skillname;         
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);
    }
    public function editoverviewAction()
    {           
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=$core->getList_CandidateSkillsOnly($UserID);
        $Skills=array();
        if($SkillName!="")
        {
            foreach($SkillName as $key=>$values )
            {
               $Skills[$key]=$values;   
            }
        }
        $this->view->SkillName=$Skills;
        $allskills=$core->getListAll_CandidateSkills($UserID);
        $this->view->allskills=$allskills;        
             
        $this->render('edit-overview');          
                     
    }
   public function doEditoverviewAction()
    {           
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $request = $this->getRequest();
        $params= $request->getParams();
        $return = array("success" => 0, "error" => "");
        $updateFields=array();
        foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
       
        $core=new PR_Api_Core_CandidateClass();
       // $skillID=array();
       if(isset($updateFields["SkillName"]))
        {
            foreach($updateFields["SkillName"] as $key=> $skills)
            {
                $skillID[$key]=$core->getskillID($skills);
               
            }
        }
        $updateFields["SkillID"]=$skillID;
        $core-> updateCandidateProfile($UserID,$updateFields);
        $return["success"]=1;
                     
    }
    
    public function vieweducationAction()
    {           
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);
        $this->view->getCandidates=$getCandidates;   
        $listEducation=$core->getCandidateEducationList($UserID);
        $this->view->listEducation=$listEducation;
       // echo("testt:<pre>");print_r($listEducation);echo("</pre>");
        $this->render('view-education');          
                     
    }
    public function editvieweducationAction()
    {   
              
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];  
        $request = $this->getRequest();
        $params= $request->getParams();
        $CredentialExperienceID=$request->getParam("CredentialExperienceID");     
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);

        $Educationlist=$core->getCandidateEducation($CredentialExperienceID);
        $this->view->getCandidates=$getCandidates;   
        $this->view->Educationlist=$Educationlist; 
      //echo("testt:<pre>");print_r($Educationlist);echo("</pre>");
        $this->render('edit-view-education');          
                     
    }
}
