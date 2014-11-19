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
        $messageList = $notiClass->getList($clientID);

        $receivIDs = $Oppotunity_PR_Api->getListReceiveIDbySenderID($clientID);
        $listCandidate = $Oppotunity_PR_Api->getListCandidateByUserID($receivIDs,3,0);

        $getTestList_PR_Api_Core = new PR_Api_Core_TestClass();
        $resultTestList = $getTestList_PR_Api_Core->getTestList(array('CompanyID'=>$CompanyID),10,0);

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

}
