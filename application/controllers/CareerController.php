<?php

class CareerController extends Application_Controller_Action
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
    
    public function careerlistAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        /*
          $clientID =  $sestionClient['ClientID'];
          $notiClass = new PR_Api_Core_NotiClass();
          $result = $notiClass->getList($clientID);
          $this->view->result = $result; //UserName
          $this->view->clientIDLogin = $clientID;
          $this->view->userName = $sestionClient['UserName']; */
    }

    public function careercreateAction()
    {
        $request = $this->getRequest();
        $sestionUSER = PR_Session::getSession(PR_Session::SESSION_USER);
        $PR_Api_Core_Career = new PR_Api_Core_CareerClass();

        $resultCareerList = $PR_Api_Core_Career->getListCareer();
        $resultSkillList = $PR_Api_Core_Career->getListSkill();

        $this->view->resultCareerList = $resultCareerList;
        $this->view->resultSkillList = $resultSkillList;

    }

    public function saveCareerNewAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $sestionUSER = PR_Session::getSession(PR_Session::SESSION_USER);
        /*
        echo "<pre>";
        print_r($sestionUSER);
        echo "<pre>"; die();
        */
        $CompanyID = $sestionUSER['CompanyID'];
        $postedby = $sestionUSER['UserID'];

        $posteddate =""; //$request->getParam("posteddate", "");
        $title = ""; //$request->getParam("title", "");
        $careerdescription = $request->getParam("careerdescription", "");

        $status = ""; //$request->getParam("status", "");
        $industry = $request->getParam("industry", "");
        $jobtype = $request->getParam("jobtype", "");
        $duration = $request->getParam("duration", "");
        $location = $request->getParam("location", "");
        $zipcode = $request->getParam("zipcode", "");
        $minimuneducation = $request->getParam("minimuneducation", "");
        $degreetitle = $request->getParam("degreetitle", "");
        $StaffFavorite = $request->getParam("StaffFavorite", "");
        $SkillID = $request->getParam("SkillID", array());

        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $clientID =  $sestionClient['UserID'];
        $career_PR_Api = new PR_Api_Career(null);

        $updateFields = array('CompanyID'=>$CompanyID,'postedby'=>$postedby, 'posteddate'=>$posteddate,
        'title'=>$title,'careerdescription'=>$careerdescription,'status'=>$status,'industry'=>$industry,'industry'=>$industry,'jobtype'=>$jobtype,
            'duration'=>$duration,'location'=>$location,'zipcode'=>$zipcode,'minimuneducation'=>$minimuneducation,'degreetitle'=>$degreetitle,'StaffFavorite'=>$StaffFavorite,
        'SkillID'=>$SkillID);

        $result = $career_PR_Api->saveCareer($updateFields);

        if($result){
            $return = array("success" => 1, "error" => "");
        } else{
            $return = array("success" => 0, "error" => "");
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);

    }
}
