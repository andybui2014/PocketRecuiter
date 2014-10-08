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
            $this->view->step = $params['utm_source'];

            switch($params['utm_source']){
                case 'contact':
                    $this->render('profile-builder/contact');
                    break;
                case 'education':
                    $this->render('profile-builder/education');
                    break;
                case 'employment':
                    $this->render('profile-builder/employment');
                    break;
                case 'skills':
                    $this->render('profile-builder/skills');
                    break;
                case 'portfolio':
                    $this->render('profile-builder/portfolio');
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
