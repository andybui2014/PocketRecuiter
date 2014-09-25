<?php

class ClientController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);ououi
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
       // echo("client");print_r($client);
        if(empty($client))
        {
            $this->_helper->redirector("index","login");
        }  
    }
    public function doLogoutAction()
    {
        $this->_helper->layout->disableLayout();

        PR_Session::clearSessions();
        
        $this->_helper->redirector("index","login");
    }   
    
    public function startProfileAction()
    {
        
    }
    public function profileAction()
    {
         $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $username = $client["UserName"];
        $password = $client["Password"];
         $clientApi = new PR_Api_Client();
        $authData = array('UserName' => $username, 'Password' => $password);
        $this->view->client = $clientApi->getUserArray($authData);
        $this->render('profile');
        //$api=new PR_Api_Core_Skill();
        //$skill= $api->getSkillArray('5');
        //echo ("Skill:<pre>");print_r($skill);echo("</pre>");
        
        
    }
    function updateProfleAction()
    {
       $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
		// echo ("params:<pre>");print_r($params);echo("</pre>");die();
         $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $username = $request->getParam("UserName", "");
        $password = $request->getParam("Password", "");
        $return = array("success" => 0, "error" => "");
        $clientID=$client["ClientID"];
       
       $clientClass = new PR_Api_Core_ClientClass();
       $updateFields=array();
       foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
           
          
       $result = $clientClass->updateClientProfile($clientID,$updateFields);
     
       if ($result) {
           $return['success'] = 1;
                
             }
             else{
                 $return['success'] = 1;
             // $return['error'] = PR_Api_Error::getInstance()->getFirstError();
                }
     
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);                    


        
    }
     
}
