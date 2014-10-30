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

    public function contactInfoAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $core = new PR_Api_Core_CandidateClass();
        $info = $core->getContactInfo($client['UserID']);
        $this->view->info = $info;
        $this->render('contact-info');
    }
    public function updateContactInfoAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
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
                    if($item['name']=='country'){
                        if(empty($item['value'])){
                            $errors['country'] = 1;
                        }else{
                            $data['Country']  = $item['value'];
                        }
                    }
                    if($item['name']=='addResLine'){
                        if(empty($item['value'])){
                            $errors['addResLine'] = 1;
                        }else{
                            $data['Address1']  = $item['value'];
                        }
                    }
                    if($item['name']=='addResLine2'){
                        if(empty($item['value'])){
                            $errors['addResLine2'] = 1;
                        }else{
                            $data['Address2']  = $item['value'];
                        }
                    }
                    if($item['name']=='city'){
                        if(empty($item['value'])){
                            $errors['city'] = 1;
                        }else{
                            $data['City']  = $item['value'];
                        }
                    }
                    if($item['name']=='stateProvince'){
                        if(empty($item['value'])){
                            $errors['stateProvince'] = 1;
                        }else{
                            $data['State']  = $item['value'];
                        }
                    }
                    if($item['name']=='zipcode'){
                        if(empty($item['value'])){
                            $errors['zipcode'] = 1;
                        }else{
                            $data['PostalCode']  = $item['value'];
                        }
                    }

                    if($item['name']=='phone')          $data['PhoneNumber']  = $item['value'];
                    if($item['name']=='url')            $data['URL']  = $item['value'];
                    //if($item['name']=='city')           $data['City']  = $item['value'];
                    //if($item['name']=='country')        $data['Country']  = $item['value'];
                    //if($item['name']=='zipcode')        $data['PostalCode']  = $item['value'];
                    if($item['name']=='fax')            $data['faxnumber']  = $item['value'];
                    //if($item['name']=='addResLine')     $data['Address1']  = $item['value'];
                    //if($item['name']=='addResLine2')    $data['Address2']  = $item['value'];
                    if($item['name']=='snu')            $data['URlnetwork']  = $item['value'];
                    if($item['name']=='insMsg')         $data['instanmessaing']  = $item['value'];

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

                    $core=new PR_Api_Core_CandidateClass();
                    $skills = $core->getList_CandidateSkillsDad($client['UserID']);

                    $core = new PR_Api_Core_CandidateClass();
                    $this->view->list = $core->getListAll_CandidateSkills($client['UserID']);
                    $this->view->stepCount = '4/5 Steps';
                    $this->view->skills = $skills;

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

        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $res = $PR_Api->deleteWatchList($OpportunityID, $CandidateProfileID);

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
        $this->render('edit-view-education');          
                     
    }

    public function employmentAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
        $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        $getUserArray=$Api->getUserArray($authData);

        $core = new PR_Api_Core_CandidateClass();
        $list = $core->getCandidateEmployments($UserID);

        $this->view->client = $getUserArray;
        $this->view->list = $list;

        // $this->render('profile');
    }

    public function doCandidateEmploymentAction(){
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

            $CandidateEmploymentID = $params['CandidateEmploymentID'];
            //add new employment
            if($CandidateEmploymentID==""){
                $companyName  = $params['CompanyName'];
                $posotionHeld = $params['PostionHeld'];
                $startDate = $params['StartDate'];
                $endDate = $params['EndDate'];
                $description = $params['Description'];

                if(empty($companyName)) $errors['CompanyName'] = 1;
                if(empty($posotionHeld)) $errors['PostionHeld'] = 1;
                if(empty($startDate)) $errors['StartDate'] = 1;
                if(empty($endDate)) $errors['EndDate'] = 1;

                if(empty($errors)){
                    $client = PR_Session::getSession(PR_Session::SESSION_USER);
                    $core = new PR_Api_Core_CandidateClass();
                    $isSuccess = $core->addCandidateEmployment($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description);
                    if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }
            } //end new
            else{
                $companyName  = $params['CompanyName'];
                $posotionHeld = $params['PostionHeld'];
                $startDate = $params['StartDate'];
                $endDate = $params['EndDate'];
                $description = $params['Description'];

                if(empty($companyName)) $errors['CompanyName'] = 1;
                if(empty($posotionHeld)) $errors['PostionHeld'] = 1;
                if(empty($startDate)) $errors['StartDate'] = 1;
                if(empty($endDate)) $errors['EndDate'] = 1;

                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->updateCandidateEmployment($CandidateEmploymentID,$companyName,$posotionHeld,$startDate,$endDate,$description);

                if($isSuccess){ $ajaxRes['success'] = 1;
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
	 public function doEdieducationAction()
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
            
        if(isset($updateFields["display"]))
        {
              $updateFields["display"]=1;
        }
        else{
             $updateFields["display"]=0;  
        }
        $core=new PR_Api_Core_CandidateClass();
        $core->updateEducation($updateFields["CredentialExperienceID"],$updateFields["institution_name"],
        $updateFields["title"],$updateFields["startdate"],$updateFields["enddate"],$updateFields["comments"],$updateFields["display"]);
       // echo("tetstt:<pre>");print_r($updateFields);echo("</pre>");die();   
        $return["success"]=1;
        
                     
    }
	 public function addEducationAction()
    {           
       
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
        $CredentialExperienceID=$request->getParam("CredentialExperienceID");     
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);   
        $this->view->getCandidates=$getCandidates;
        $a=$core->getList_CandidateSkillsDad($UserID);
       // echo("tetst:<pre>");print_r($a);echo("</pre>");
        $this->render('Add-education');
                     
    }
	 public function doAddnewEducationAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest();
        $params = $request->getParams();
        $core=new PR_Api_Core_CandidateClass();
             
        if(isset($params["display"]))
        {
              $params["display"]=1;
        }
        else{
             $params["display"]=0;  
        }
        $result=$core->addEducation($UserID,$params["institution_name"],$params["title"],$params["startdate"],$params["enddate"],$params["comments"],$params["display"]);
        $return["success"]=1; 
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);                    
        // echo ("tetstt:<pre>");print_r($params);echo("</pre>");
     }
	  public function portfolioAction()
     {
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
            
        $core=new PR_Api_Core_CandidateClass();
        $list = $core->getListCandidatePortfolio($UserID);
        $this->view->list = $list;
        $this->render('Portfolio');
     }
	 public function addportfolioAction()
     {
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
        $CredentialExperienceID=$request->getParam("CredentialExperienceID");     
        
        $this->render('add-portfolio');
        //
     }
	 public function doAddnewportfolioAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest();
        $params = $request->getParams();
        $core=new PR_Api_Core_CandidateClass();
        $portfolioId=$core->setPortfolioid();
        $images=array();
        for($i=0; $i<count($_FILES['file']['name']); $i++) {
          //Get the temp file path
          $tmpFilePath = $_FILES['file']['tmp_name'][$i];
          
          //Make sure we have a filepath
          if ($tmpFilePath != ""){
            //Setup our new file path
            $filename = uniqid() ."_". $_FILES["file"]["name"][$i];   
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], DIR_MEDIA_PORTFOLIO . $filename); 
             $url=URL_MEDIA_PORTFOLIO . $filename;  
             $core->saveImagesPortfolio($portfolioId,$filename);             
          //echo "<img src='$url' width='120' /><br />";                   
           }
          }
        //  echo ("testt:<pre>");print_r($images);echo("</pre>");die();
          $core->addCandidatePortfolio($UserID,$params["Title"],$params["URL"],
        $params["Description"],"");
        if(isset($params["AddPorfolio"])&& $params["AddPorfolio"]!="" )
        {
            header("Location: portfolio");
        }
        if(isset($params["AddAndAnothorPortfolio"])&& $params["AddAndAnothorPortfolio"]!="" )
        {
            header("Location: addportfolio");
        }
          $return["success"]=1; 
     }
	  public function editportfolioAction()
     {
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
        $CandidatePortfolioID=$request->getParam("id");     
        $core=new PR_Api_Core_CandidateClass();
        $portfolio=$core->getCandidatePortfolio($CandidatePortfolioID);
        $images=$core->getImagesPortfolio($CandidatePortfolioID);
        $this->view->Portfolio=$portfolio;
        $this->view->ListImages=$images;
       // echo("Testt:<pre>");print_r($images);echo("</pre>");
        $this->render('edit-portfolio');
     }
	  public function deleteimagesAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $request = $this->getRequest(); 
        $id=$request->getParam("id");    
        $portfolioid=$request->getParam("portfolioid"); 
          
        $core=new PR_Api_Core_CandidateClass();
        //echo "tetst:";print_r($id);die();
        $result=$core->deleteImagesPortfolio($id);
        $ajaxRes['success'] = 1;
        //$ajaxRes['portfolioid']=$id;
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
        
     }
	 public function doUpdateportfolioAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest();
        $params = $request->getParams();
        $core=new PR_Api_Core_CandidateClass();
        $portfolioId=$request->getParam("CandidatePortfolioID");
        //echo("Testt:<pre>");print_r($portfolioId);echo("</pre>");die();
        if(isset($_FILES['file']['name'])){
        for($i=0; $i<count($_FILES['file']['name']); $i++) {
          //Get the temp file path
          $tmpFilePath = $_FILES['file']['tmp_name'][$i];
          
          //Make sure we have a filepath
          if ($tmpFilePath != ""){
            //Setup our new file path
            $filename = uniqid() ."_". $_FILES["file"]["name"][$i];   
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], DIR_MEDIA_PORTFOLIO . $filename); 
             $url=URL_MEDIA_PORTFOLIO . $filename;  
             $core->saveImagesPortfolio($portfolioId,$filename);             
                           
           }
          }}

          $core->updateCandidatePortfolio($portfolioId,$params["Title"],$params["URL"],
        $params["Description"],"");
        if(isset($params["AddPorfolio"])&& $params["AddPorfolio"]!="" )
        {
            header("Location: portfolio");
        }
        if(isset($params["AddAndAnothorPortfolio"])&& $params["AddAndAnothorPortfolio"]!="" )
        {
            header("Location: addportfolio");
        }
          $return["success"]=1; 
     }
	  public function portfoliodetailAction()
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
        
        $request = $this->getRequest();
        $CandidatePortfolioID = $request->getParam("id");
        $core=new PR_Api_Core_CandidateClass();
        $portfolio=$core->getCandidatePortfolio($CandidatePortfolioID);
        $images=$core->getImagesPortfolio($CandidatePortfolioID);
        $this->view->Portfolio=$portfolio;
        $this->view->ListImages=$images;
         
     // echo ("getUserArray:<pre>");print_r($portfolio);echo("</pre>");
        $this->render('portfoliodetail');          
                     
    }

    public function matchopportunitiesAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];
        $PR_Api = new PR_Api_Core_CareerClass();
        $skillList = $PR_Api->getListSkill();
        $result = $PR_Api->getListOpportunity();

        $industryListUnique = array();
        $industryList = array();
        $experiencedUnique = array();
        $experienced_List = array();
        $countryUnique = array();
        $country_List = array();
        $cityUnique = array();
        $city_List = array();

        $core = new PR_Api_Core_CandidateClass();

        $oppList = array();

        if($result !=""){
            foreach ($result as $kk=>$oppListInfo){
                $industryListUnique[] = trim(strtolower($oppListInfo['industry']));
                $experiencedUnique[] = $oppListInfo['experienced'];
                $countryUnique[] = $oppListInfo['country'];
                $cityUnique[] =trim(strtolower($oppListInfo['city']));

                $hadApplied = $core->opportunityCandidateHadApplied($oppListInfo['OpportunityID'],$CandidateProfileID);
                if($hadApplied){
                    $oppListInfo['hadApplied'] =true ;
                    $oppList[] = $oppListInfo;
                } else {
                    $oppListInfo['hadApplied'] =false ;
                    $oppList[] = $oppListInfo;
                }

            }

            $industryListUnique = array_unique($industryListUnique);
            $experienced_List = array_unique($experiencedUnique);
            $country_List = array_unique($countryUnique);
            $cityUnique = array_unique($cityUnique);

            foreach ($industryListUnique as $industryInfo) {
                $industryList[] = ucwords($industryInfo);
            }

            foreach ($cityUnique as $cityInfo) {
                $city_List[] = ucwords($cityInfo);
            }
        }
        /*echo "<pre>";
            print_r($oppList);
        echo "</pre>"; die();*/

        $this->view->skillList = $skillList;
        $this->view->oppList = $oppList;
        $this->view->industryList = $industryList;
        $this->view->experienced_List = $experienced_List;
        $this->view->country_List = $country_List;
        $this->view->city_List = $city_List;
        $this->view->CandidateProfileID = $client['CandidateProfileID'];
        // $this->render('profile');
    }

    public function doSearchOpportunitiesAction(){
        $this->_helper->layout->disableLayout();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];
        $request = $this->getRequest();
        $params = $this->getRequest()->getParams();
        $industry = $request->getParam("technology-id","");
        $experienced = $request->getParam("experience-name","");
        $country = $request->getParam("country-name","");
        $city = $request->getParam("city-name","");

        if(isset($params['matchopportunitySear'])){
            $opportunitiesSearchList = $request->getParam("matchopportunitySear","");
        } else{
            $opportunitiesSearchList = array();
        }

        $core = new PR_Api_Core_CandidateClass();
        $opportunityListID = $core->getOpportunitiesMatch($industry,$experienced,$country,$city,$opportunitiesSearchList);

        $PR_Api = new PR_Api_Core_CareerClass();

        $oppList = array();
        $result = array();
        if($opportunityListID !=""){
            foreach($opportunityListID as $key=>$opportunityID){
                $result = $PR_Api->getOpportunityInfoByID($opportunityID);

               $hadApplied = $core->opportunityCandidateHadApplied($opportunityID,$CandidateProfileID);

               if($hadApplied){
                    $result['hadApplied'] =true ;
                    $oppList[] = $result;
               } else {
                   $result['hadApplied'] =false ;
                   $oppList[] = $result;
               }
            }
        }

        /*echo "<pre>";
        print_r($oppList);
        echo "</pre>";die();*/

        /*if($oppList !=""){
            foreach ($oppList as $kk=>$oppListInfo){
                $industryListUnique[] = trim(strtolower($oppListInfo['industry']));
                $experiencedUnique[] = $oppListInfo['experienced'];
                $countryUnique[] = $oppListInfo['country'];
                $cityUnique[] =trim(strtolower($oppListInfo['city']));

            }

            $industryListUnique = array_unique($industryListUnique);
            $cityUnique = array_unique($cityUnique);

            $experienced_List = array_unique($experiencedUnique);
            $country_List = array_unique($countryUnique);

            foreach ($industryListUnique as $industryInfo) {
                $industryList[] = ucwords($industryInfo);
            }

            foreach ($cityUnique as $cityInfo) {
                $city_List[] = ucwords($cityInfo);
            }
        } */

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $oppList = json_encode($oppList);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($oppList), true)
            ->setBody($oppList);
        $this->_helper->viewRenderer('matchopportunities');

    }

    public function candidateApplyAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $params = $this->getRequest()->getParams();
        $CandidateProfileID = $params['CandidateProfileID'];
        $OpportunityID = $params['OpportunityID'];
        $OppCandidateAppliedStatus = 1;
        $CandidateHideStatus = 1;

        $updateFields = array('OpportunityID'=>$OpportunityID, 'CandidateProfileID'=>$CandidateProfileID,'CandidateAppliedStatus'=>$OppCandidateAppliedStatus,
            'CandidateHideStatus'=>$CandidateHideStatus);
        $core = new PR_Api_Core_CandidateClass();
        $result = $core->saveOpportunityCandidateApply($updateFields);
        /*echo "<pre>";
            print_r($OpportunityCandidateApplyID);
        echo "</pre>"; die(); */
        if($result){
            $return = array("success" => 1, "error" => "Apply successfully");
        } else{
            $return = array("success" => 0, "error" => "You had applied the job");
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }
	
	    public function activitiesAction(){
        $user = PR_Session::getSession(PR_Session::SESSION_USER);

        $CandidateID =$user['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $activelist = $PR_Api->getOpportunityCandidateMatchActivities($CandidateID);
        $this->view->activelist = $activelist;
    }
	
     public function myprofileAction()
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
        $portfolio = $api_candidate->getListCandidatePortfolio($UserID);
        $this->view->Portfolio=$portfolio;
       // $CandidateEmployment=array();
       // if(!empty($getCandidates["CandidateEmploymentID"])||$getCandidates["CandidateEmploymentID"]!="")
       // {
         //     foreach($getCandidates["CandidateEmploymentID"] as $key=>$values )
            //    {
               //    $CandidateEmployment[$key]=$values;   
               // }
        //}
        
       // $this->view->CandidateEmployment=$CandidateEmployment;
        //$Education = $api_candidate->getCandidateEducationList(2); 
        //$this->view->Education=$Education;
         
      // echo ("getUserArray:<pre>");print_r($portfolio);echo("</pre>");
        $this->render('myprofile');                  
                     
    }

}
