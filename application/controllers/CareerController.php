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
        $CompanyID = $sestionClient['CompanyID'];
        $Oppotunity_PR_Api = new PR_Api_Core_CareerClass();
        $getListOpp = $Oppotunity_PR_Api->getListOpportunity(array('CompanyID'=>$CompanyID));
       /*echo "<pre>";
            print_r($getListOpp);
       echo "</pre>"; die(); */
        $this->view->getListOpp = $getListOpp;
    }
    public function careereditAction()
    {
        $request = $this->getRequest();
        $oppid = $request->getParam("opportunityID", "");
        //$sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        //$CompanyID = $sestionClient['CompanyID'];
        $Oppotunity_PR_Api = new PR_Api_Core_CareerClass();
        $getListOpp = $Oppotunity_PR_Api->getOpportunityInfoByID($oppid);
        $careerType = trim($getListOpp['jobtype']);
        $Career = $Oppotunity_PR_Api->getCareerTypeByID($careerType);

        $CareerList = $Oppotunity_PR_Api->getListCareer();

        $CompanyID = trim($getListOpp['CompanyID']);
        $companyInfo = $Oppotunity_PR_Api->getCompanyByID($CompanyID);
        $listCompany = $Oppotunity_PR_Api->getCompany();
        $resultSkillList = $Oppotunity_PR_Api->getListSkill();

        $getTestList_PR_Api_Core = new PR_Api_Core_TestClass();
        $resultTestList = $getTestList_PR_Api_Core->getTestList(array('CompanyID'=>$CompanyID));
         /*echo "<pre>";
            print_r($getListOpp);
              echo "</pre>"; die(); */
        $this->view->getListOpp = $getListOpp;
        $this->view->getListCareerType = $Career;
        $this->view->CareerList = $CareerList;
        $this->view->companyInfo = $companyInfo;
        $this->view->listCompany = $listCompany;
        $this->view->resultSkillList = $resultSkillList;
        $this->view->resultTestList = $resultTestList;


    }
    public function getTestAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $CompanyID = $request->getParam("CompanyID", "");

        $getTestList_PR_Api_Core = new PR_Api_Core_TestClass();
        $result = $getTestList_PR_Api_Core->getTestList(array('CompanyID'=>$CompanyID));
         //echo "<pre>";
        //print_r($result);
       // echo "</pre>";die();

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);
}
    public function careercreateAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $CompanyID = $sestionClient['CompanyID'];
        $PR_Api_Core_Career = new PR_Api_Core_CareerClass();

        $resultCareerList = $PR_Api_Core_Career->getListCareer();
        $resultSkillList = $PR_Api_Core_Career->getListSkill();

        $getTestList_PR_Api_Core = new PR_Api_Core_TestClass();
        $resultTestList = $getTestList_PR_Api_Core->getTestList(array('CompanyID'=>$CompanyID));

        $companyInfo = $PR_Api_Core_Career->getCompanyByID($CompanyID);
        $listCompany = $PR_Api_Core_Career->getCompany();
       // echo "<pre>";
       // print_r($resultTestList);
        //echo "</pre>";die();
        $this->view->resultCareerList = $resultCareerList;
        $this->view->resultSkillList = $resultSkillList;
        $this->view->resultTestList = $resultTestList;
        $this->view->companyInfo = $companyInfo;
        $this->view->listCompany = $listCompany;
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

        $posteddate ="";

        $title = $request->getParam("careername","");
        $OppCompanyID = $request->getParam("CompanyID","");
        $careerdescription = $request->getParam("careerdescription", "");
        $status = 1;
        $industry = $request->getParam("industry", "");
        $jobtype = $request->getParam("jobtype", "");
        $duration = $request->getParam("duration", "");
        $location = $request->getParam("location", "");
        $zipcode = $request->getParam("zipcode", "");
        $minimuneducation = $request->getParam("minimuneducation", "");
        $degreetitle = $request->getParam("degreetitle", "");
        $StaffFavorite = $request->getParam("StaffFavorite", "");
        $SkillID = $request->getParam("SkillID", array());
        $requiredExperience = $request->getParam("requiredExperience", "");
        $salaryRangeF = $request->getParam("salaryRangeF", "");
        $salaryRangeT = $request->getParam("salaryRangeT", "");
        $testid = $request->getParam("testid", array());

        $clientID =  $sestionUSER['UserID'];
        $career_PR_Api = new PR_Api_Career(null);

        $updateFields = array('CompanyID'=>$OppCompanyID,'postedby'=>$postedby, 'posteddate'=>$posteddate,
        'title'=>$title,'careerdescription'=>$careerdescription,'status'=>$status,'industry'=>$industry,'industry'=>$industry,'jobtype'=>$jobtype,
            'duration'=>$duration,'location'=>$location,'zipcode'=>$zipcode,'minimuneducation'=>$minimuneducation,'degreetitle'=>$degreetitle,'StaffFavorite'=>$StaffFavorite,
            'salaryrangefrom'=>$salaryRangeF, 'salaryrangeto'=>$salaryRangeT,'experienced'=>$requiredExperience
        );

        $result = $career_PR_Api->saveCareer($updateFields);
       /* echo "<pre>";
           print_r($result->OpportunityID);
          echo "</pre>"; die(); */
        if($result){
            $OpportunityID = $result->OpportunityID;
            $edit_PR_Api = new PR_Api_Core_CareerClass();
            $edit_PR_Api->saveCareerSkills($OpportunityID, $SkillID);
            $edit_PR_Api->saveCareerTests($OpportunityID, $testid) ;
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

    public function editCareerAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $sestionUSER = PR_Session::getSession(PR_Session::SESSION_USER);

        $postedby = $sestionUSER['UserID'];

        $posteddate ="";
        $OpportunityID = $request->getParam("OpportunityID","");
        $title = $request->getParam("careername","");
        $OppCompanyID = $request->getParam("CompanyID","");
        $careerdescription = $request->getParam("careerdescription", "");
        $status = 1;
        $industry = $request->getParam("industry", "");
        $jobtype = $request->getParam("jobtype", "");
        $duration = $request->getParam("duration", "");
        $location = $request->getParam("location", "");
        $zipcode = $request->getParam("zipcode", "");
        $minimuneducation = $request->getParam("minimuneducation", "");
        $degreetitle = $request->getParam("degreetitle", "");
        $StaffFavorite = $request->getParam("StaffFavorite", "");
        $SkillID = $request->getParam("SkillID", array());
        $requiredExperience = $request->getParam("requiredExperience", "");
        $salaryRangeF = $request->getParam("salaryRangeF", "");
        $salaryRangeT = $request->getParam("salaryRangeT", "");
        $testid = $request->getParam("testid", array());

        $career_PR_Api = new PR_Api_Career($OpportunityID);
        /*echo "<pre>";
           print_r($OpportunityID);
          echo "</pre>"; die(); */
        $updateFields = array('CompanyID'=>$OppCompanyID,'postedby'=>$postedby, 'posteddate'=>$posteddate,
            'title'=>$title,'careerdescription'=>$careerdescription,'status'=>$status,'industry'=>$industry,'industry'=>$industry,'jobtype'=>$jobtype,
            'duration'=>$duration,'location'=>$location,'zipcode'=>$zipcode,'minimuneducation'=>$minimuneducation,'degreetitle'=>$degreetitle,'StaffFavorite'=>$StaffFavorite,
            'salaryrangefrom'=>$salaryRangeF, 'salaryrangeto'=>$salaryRangeT,'experienced'=>$requiredExperience
        );

        $result = $career_PR_Api->saveCareer($updateFields);

        if($result){
            $edit_PR_Api = new PR_Api_Core_CareerClass();
            $edit_PR_Api->saveCareerSkills($OpportunityID, $SkillID);
            $edit_PR_Api->saveCareerTests($OpportunityID, $testid) ;
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
    public function doCompanyProfileAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $data = array();
        $data['Companyname'] = $request->getParam("Companyname", "");
        $data['Industry'] = $request->getParam("Industry", "");
        $data['Address'] = $request->getParam("Address", "");
        $data['Zipcode'] = "";
        $data['Description'] = $request->getParam("Description", "");
        $data['images'] = "";
        $data['PhoneNumber'] = $request->getParam("PhoneNumber", "");
        $data['country'] = $request->getParam("country", "");
        $data['emailinfo'] = $request->getParam("emailinfo", "");


        $PR_Api_ClientClass = new PR_Api_Core_ClientClass();
        $comID = $PR_Api_ClientClass->AddCompany($data);
        /*echo "<pre>";
        print_r($result);
        echo "</pre>";die(); */
        if($comID){
            $PR_Api_CareerClass = new PR_Api_Core_CareerClass();
            $result = $PR_Api_CareerClass->getCompany();
            $result['comID'] = $comID;
        } else{
            $result = "";
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);
    }
    public function careermatchAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $CompanyID = $sestionClient['CompanyID'];
        $Oppotunity_PR_Api = new PR_Api_Core_CareerClass();
        $getListOpp_com = $Oppotunity_PR_Api->getListOpportunity(array('CompanyID'=>$CompanyID));


        //echo "<pre>";
        // echo "<pre>";
       //   print_r("");
       //  echo "</pre>"; die();

       // $this->view->getListOpp = $getListOpp;
    }
}
