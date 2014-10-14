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
    public function stepNextContactAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);

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
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidateEducation($client['UserID'],$instName,$degreeName,$startYear,$endYear);

                if($isSuccess) $ajaxRes['success'] = 1;
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

            $core = new PR_Api_Core_CandidateClass();
            if(!empty($title)){
                $isSuccess = $core->addCandidatePortfolio($client['UserID'],$title,$url,$description,null);
                if($isSuccess) $ajaxRes['success'] = 1;
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
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidateEducation($client['UserID'],$instName,$degreeName,$startYear,$endYear);

                if($isSuccess) $ajaxRes['success'] = 1;
            }
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

            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')          $empId = $item['value'];
                    if($item['name']=='companyName')    $companyName  = $item['value'];
                    if($item['name']=='posotionHeld')   $positionHeld = $item['value'];
                    if($item['name']=='startDate')      $startDate = $item['value'];
                    if($item['name']=='endDate')        $endDate = $item['value'];
                    if($item['name']=='description')    $description = $item['value'];
                }
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->updateCandidateEmployment($empId,$companyName,$positionHeld,$startDate,$endDate,$description);
                if($isSuccess) $ajaxRes['success'] = 1;
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

            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='companyName')    $companyName  = $item['value'];
                    if($item['name']=='posotionHeld')   $posotionHeld = $item['value'];
                    if($item['name']=='startDate')      $startDate = $item['value'];
                    if($item['name']=='endDate')        $endDate = $item['value'];
                    if($item['name']=='description')    $description = $item['value'];
                }
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidateEmployment($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description);
                if($isSuccess) $ajaxRes['success'] = 1;
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function profileAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
        $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        $getUserArray=$Api->getUserArray($authData);
        $this->view->client = $getUserArray;
        $api_candidate= new PR_Api_Core_CandidateClass();
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"];
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=array();
        foreach($getCandidates["SkillName"] as $key=>$values )
        {
            $SkillName[$key]=$values;
        }
        $this->view->SkillName=$SkillName;
        $CandidateEmployment=array();
        foreach($getCandidates["CandidateEmploymentID"] as $key=>$values )
        {
           $CandidateEmployment[$key]=$values;   
        }
        $this->view->CandidateEmployment=$CandidateEmployment;
        $Education = $api_candidate->getCandidateEducationList(2); 
        $this->view->Education=$Education;

        // echo ("getUserArray:<pre>");print_r($Education);echo("</pre>");
        $this->render('profile');
		 
        
        
        
    }

}
