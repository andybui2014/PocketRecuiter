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
                    $this->view->stepCount = '3/5 Steps';
                    $this->render('profile-builder/employment');
                    break;
                case 'skills':
                    $this->view->stepCount = '4/5 Steps';
                    $this->render('profile-builder/skills');
                    break;
                case 'portfolio':
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
    /*
    public function listEducationAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $core = new PR_Api_Core_CandidateClass();
        $list = $core->getCandidateEducationList($client['UserID']);
        $this->view->list = $list;
        $this->render('profile-builder/education-list');
    }*/
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

    public function startProfileAction(){
        $this->render('profile');
    }
    public function stepNextContactAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){

            $params = $this->getRequest()->getParams();
            $data = $params['data'];

            if(!empty($data)){

                $arrData = array();
                foreach($data as $key=>$item){
                    if($item['name']=='firstname')  $arrData['firstname']= $item['value'];
                    if($item['name']=='lastname')   $arrData['lastname']= $item['value'];
                    if($item['name']=='email')      $arrData['emailaddress']= $item['value'];
                    if($item['name']=='phone')      $arrData['PhoneNumber']= $item['value'];
                    if($item['name']=='url')        $arrData['URL']= $item['value'];
                    if($item['name']=='city')       $arrData['City']= $item['value'];
                    if($item['name']=='country')    $arrData['Country']= $item['value'];
                    if($item['name']=='zipcode')    $arrData['PostalCode']= $item['value'];
                }
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $core->saveContactInfo($client['UserID'],$arrData);
                $ajaxRes['success'] = 1;

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
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
         $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        
        $this->view->client = $Api->getUserArray($authData);
       
        $this->render('profile');
        //$api=new PR_Api_Core_Skill();
        //$skill= $api->getSkillArray('5');
        //echo ("Skill:<pre>");print_r($skill);echo("</pre>");
        
        
    }
     
}
