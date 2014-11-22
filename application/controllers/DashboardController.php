<?php

class DashboardController extends Application_Controller_Action {

    public function init() {
        parent::init();
        // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("user");print_r($user);
        if(empty($user))
        {
            $this->_helper->redirector("index","login");
        }
    }

    public function indexAction() {

        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);

        $CompanyID = $sestionClient['CompanyID'];
        $clientID =  $sestionClient['UserID'];

        $Oppotunity_PR_Api = new PR_Api_Core_CareerClass();

        $getListOpp = $Oppotunity_PR_Api->getListOpportunity(array('CompanyID'=>$CompanyID));
        $notiClass = new PR_Api_Core_NotiClass();
        $messageList = $notiClass->getList($clientID,$limit=10, $offset=0);

        $receivIDs = $Oppotunity_PR_Api->getListReceiveIDbySenderID($clientID);
        $listCandidate1 = $Oppotunity_PR_Api->getListCandidateByUserID($receivIDs,3,0);

        $listSkill = $Oppotunity_PR_Api->getSkillByCompanyID($CompanyID);
        $listCandidate  = array();

        if(!empty($listCandidate1) && count($listCandidate1)>0){
            foreach ($listCandidate1 as $kkk=>$listCandidate1Info) {
               if(!empty($listCandidate1Info['Skills']) && count($listCandidate1Info['Skills'])>0){
                   $list  = array();
                    foreach($listCandidate1Info['Skills'] as $listInfor){

                        if(!empty($listSkill) && count($listSkill)>0){
                            foreach($listSkill as $keylistskill=>$listSkillInfo){
                                if($listSkillInfo['SkillID'] == $listInfor['SkillID']){
                                    $list[] = $listSkillInfo['title'];
                                }
                            }
                        }

                   }

                }
                $list = array_unique($list);
                $listCandidate1Info ['strTitle'] = $list;
                $listCandidate[] = $listCandidate1Info;
            }


        }

        $getTestList_PR_Api_Core = new PR_Api_Core_TestClass();
        $resultTestList = $getTestList_PR_Api_Core->getTestList(array('CompanyID'=>$CompanyID),10,0);

        $useCS = new PR_Api_Core_NotiClass();
        $uselist = $useCS->geAllUser();
        $this->view->uselist = $uselist;
        /*echo "<pre>";
        print_r($messageList);
        echo "</pre>"; die();*/

        $this->view->getListOpp = $getListOpp;
        $this->view->messageList = $messageList;
        $this->view->listCandidate = $listCandidate;
        $this->view->resultTestList = $resultTestList;
}

    public function deleteTestAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $CompanyID = $sestionClient['CompanyID'];

        $request = $this->getRequest();
        $testID = array();
        $testID[] = $request->getParam("testID","");

        $return = 0;

        $Test_Core = new PR_Api_Core_TestClass();

        $rsl = $Test_Core->delete($testID);
        if($rsl !==0){
            $return = 1;
        } else{
            $return = 0;
        }

        $notiClass = new PR_Api_Core_NotiClass();
        $messageList = $notiClass->getList($CompanyID);

        $result = array();
        $result['list'] = $Test_Core->getTestList(array('CompanyID'=>$CompanyID),10,0);
        $result['suc'] = $return;
       /* echo "<pre>";
        print_r($result);
        echo "</pre>"; die(); */
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);

    }

    public function newNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $subjectNotification = $request->getParam("subjectNotification", "");
        $contentNotification = $request->getParam("contentNotification", "");
        $receideid = $request->getParam("receiverid", "");

        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $clientID =  $sestionClient['UserID'];
        $noti = new PR_Api_Noti(null);
        $updateFields = array('subjecttext'=>$subjectNotification,'sender_iduser'=>$clientID,'sender_iduser'=>$clientID, 'receiver_iduser'=>$receideid, 'content'=>$contentNotification);
        $rsl = $noti->save($updateFields);

        if($rsl !==0){
            $return = 1;
        } else{
            $return = 0;
        }

        $notiClass = new PR_Api_Core_NotiClass();
        $result = array();
        $result['list'] = $notiClass->getList($clientID,$limit=10, $offset=0);
        $result['suc'] = $return;

        //reload
        $Career_PR_Api = new PR_Api_Core_CareerClass();
        $receivIDs = $Career_PR_Api->getListReceiveIDbySenderID($clientID);
        $listCandidate1 = $Career_PR_Api->getListCandidateByUserID($receivIDs,3,0);

        $CompanyID = $sestionClient['CompanyID'];
        $listSkill = $Career_PR_Api->getSkillByCompanyID($CompanyID);
        $listCandidate  = array();

        if(!empty($listCandidate1) && count($listCandidate1)>0){
            foreach ($listCandidate1 as $kkk=>$listCandidate1Info) {
                if(!empty($listCandidate1Info['Skills']) && count($listCandidate1Info['Skills'])>0){
                    $list  = array();
                    foreach($listCandidate1Info['Skills'] as $listInfor){

                        if(!empty($listSkill) && count($listSkill)>0){
                            foreach($listSkill as $keylistskill=>$listSkillInfo){
                                if($listSkillInfo['SkillID'] == $listInfor['SkillID']){
                                    $list[] = $listSkillInfo['title'];
                                }
                            }
                        }

                    }

                }
                $list = array_unique($list);
                $listCandidate1Info ['strTitle'] = $list;
                $listCandidate[] = $listCandidate1Info;
            }
        }

        $result['listCandidate'] = $listCandidate;
        //
        /* echo "<pre>";
         print_r($result);
         echo "</pre>"; die(); */
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);
    }

    public function deleteNotificationsCheckedAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $CompanyID = $sestionClient['CompanyID'];
        $clientID =  $sestionClient['UserID'];
        $request = $this->getRequest();
        $listNotiID = $request->getParam("listNotiID", "");

        $core = new PR_Api_Core_NotiClass();
        $rsl = $core->delete($listNotiID);
        if(empty($rsl)){
            $return = 1;
        } else{
            $return = 0;
        }

        $notiClass = new PR_Api_Core_NotiClass();

        $result = array();
        $result['list'] = $notiClass->getList($clientID,$limit=10, $offset=0);
        $result['suc'] = $return;

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);

    }

    public function publibOpportunityAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $request = $this->getRequest();
        $params = $this->getRequest()->getParams();
        $opportunityID = $params['oppotunityID'];
        $status = $params['status'];
/*
        if(isset($params['oppotunityID'])){
            $opportunityID = $params['oppotunityID'];
        } else{
            $opportunityID ="";
        }

        if(isset($params['status'])){
            $status = $params['status'];
        } else{
            $status ="";
        }

        $Career_PR_Api = new PR_Api_Core_CareerClass();
        if($opportunityID !="" && $status!=""){

            $result=  $Career_PR_Api->publibOpportunityByOpportunityID($opportunityID,$status);
        } */
        $Career_PR_Api = new PR_Api_Core_CareerClass();
        $result=  $Career_PR_Api->publibOpportunityByOpportunityID($opportunityID,$status);

       // $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
       // $CompanyID = $sestionClient['CompanyID'];

        //$getListOpp = $Career_PR_Api->getListOpportunity(array('CompanyID'=>$CompanyID));
        /*echo "<pre>";
        print_r($getListOpp);
        echo "</pre>"; die(); */
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
