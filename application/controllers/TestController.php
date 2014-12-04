<?php

class TestController extends Application_Controller_Action {

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
    public function testInfoAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $request = $this->getRequest();
        $tid = $request->getParam('tid',null);

        if(!empty($tid) && is_numeric($tid)){
            $core = new  PR_Api_Core_TestClass();
            $records = $core->getTestInfo($tid);
            $ajaxRes['success'] = 1;
            $ajaxRes['info'] = $records;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function indexAction() {
		$user = PR_Session::getSession(PR_Session::SESSION_USER);
        $core = new PR_Api_Core_TestClass();
        $companyID = $user["CompanyID"];
        $records = $core->getTestListForCompany($companyID);
        $this->view->list =  $records;

    }

    public function addQuestionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $request = $this->getRequest();

        $testId = $request->getParam('testId',null);

        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyId = $user['CompanyID'];

        if($testId > 0){
            $core = new  PR_Api_Core_TestClass();
            $testQstId = $core->createQuestion($testId,'Question New',null);
            $ajaxRes['success'] = 1;
            $ajaxRes['qstId'] = $testQstId;

        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function removeTestsAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $arrTest = $params['dataTIds'];
        if(is_array($arrTest) && sizeof($arrTest) > 0){
            $core  = new PR_Api_Core_TestClass();
            $core->delete($arrTest);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function removeTestAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $tid = $params['dataTId'];
        if($tid > 0){
            $arrTid = array();
            array_push($arrTid,$tid);
            $core = new  PR_Api_Core_TestClass();
            $core->delete($arrTid);
            $ajaxRes['info'] = '';
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function newTestAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $nameTest = $params['name'];
        $companyId = $user['CompanyID'];
        $userId = $user['UserID'];
        if(!empty($nameTest)){
            $core = new  PR_Api_Core_TestClass();
            //$core->delete()
            $arrTest = $core->createTest($userId,$companyId,$nameTest);
            $ajaxRes['info'] = $arrTest;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function saveTestAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);

        $companyId = $user['CompanyID'];
        $userId = $user['UserID'];
        $core = new  PR_Api_Core_TestClass();
        //$core->createTest();

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function removeQuestionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $dataTId = $params['dataTId'];
        $dataQid = $params['dataQid'];
        if($dataTId > 0 && $dataQid > 0){
            $core = new  PR_Api_Core_TestClass();
            $core->deleteQuestion($dataQid);
            $ajaxRes['success'] = 1;
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doAddQuestionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();

        $qstName = $params['qstName'];
        //$qstOpt = $params['qstOpt'];
        $qstListID = $params['qstListID'];
        $qasList = $params['qasList'];
        $dataTId = $params['dataTId'];
        $dataQid = $params['dataQid'];
        /*echo "<pre>";
            print_r($params);
        echo "</pre>"; die();*/
        if($dataTId > 0 && !empty($qstName)){
            $core = new  PR_Api_Core_TestClass();
            //$core->updateQuestion($dataQid,$qstName,$qstOpt);
            $core->updateQuestion($dataQid,$qstName,$qasList,$qstListID);
            $ajaxRes['success'] = 1;
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);

    }

    public function getQuestionAction(){

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);

        $request = $this->getRequest();
        $dataTId = $request->getParam('dataTId',null);
        $dataQid = $request->getParam('dataQid',null);
        if(!empty($dataTId) && !empty($dataQid)){
            $core = new PR_Api_Core_TestClass();
            $ajaxRes['info'] = $core->getTestQuestionInfo($dataQid);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
 
    }
    public function editNameAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $request = $this->getRequest();

        $testName = $request->getParam('name',null);
        $testId = $request->getParam('testId',null);

        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $companyId = $user['CompanyID'];


        if(!empty($testName) && is_numeric($testId)){
            $core = new  PR_Api_Core_TestClass();
            $arrResult = $core->updateTest($testId,$companyId,$testName);
            if($arrResult['success']){
                $ajaxRes = array('success'=>1);
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
