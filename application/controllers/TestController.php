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
        //echo("Index-Index-page: ");echo($this->view->page);die();
        $core = new PR_Api_Core_TestClass();
        $companyID = 1;
        $records = $core->getTestListForCompany($companyID);
        $this->view->list =  $records;

    }

}
