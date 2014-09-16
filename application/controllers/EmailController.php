<?php

class EmailController extends Application_Controller_Action {

    public function init()
    {
        parent::init();
        $user = CS_Session::getSession();
        if ($this->view->ajax == 1 && empty($user))
        {
            $this->_helper->json(array('logined' => 0));
        } else if (empty($user))
        {
            $this->_helper->redirector("index", "login");
        }
    }

    function indexAction()
    {
//from info
        $frominfo = $this->getFrominfo($this->_user['user_id']);
        $fromname = $frominfo['FormName'];
        $frommail = $frominfo['cfgval'];

//to info
        $toinfo = $this->getToinfo($this->_request->getParam('toemail'));
        if ($toinfo['user_type'] == 4)
        {
            $toname = $toinfo['fname'] . ' ' . $toinfo['mname'] . ' ' . $toinfo['lname'];
            $tomail = $toinfo['email'];
        } else
        {
            $toname = $toinfo['name'];
            $tomail = $toinfo['email'];
        }

        $this->view->frommail = $frommail;
        $this->view->fromname = $fromname;
        $this->view->toname = $toname;
        $this->view->tomail = $tomail;
    }

    function sendAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $return = array("success" => 0, "error" => "");
        $frommail = $request->getParam("formemail", "");
        $fromname = $request->getParam("formname", "");
        $tomail = $request->getParam("toemail", "");
        $toname = $request->getParam("toname", "");
        $subject = $request->getParam("emailsubject", "");
        $msg = $request->getParam("emailbody", "");
        try
        {
            $mail = new CS_Mail();
            $mail->setFromName($fromname);
            $mail->setFromEmail($frommail);
            $mail->setToName($toname);
            $mail->setToEmail($tomail);
            $mail->setSubject($subject);
            $mail->setBodyText($msg);
            $mail->send();
            $return['success'] = 1;
        } catch (Exception $e)
        {
            $return['error'] = "Can not send";//CS_Api_Error::getInstance()->getFirstError();
        }
        
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);
    }

    function getFrominfo($formid)
    {
        if ($formid == 0)
        {
            $FormName = "System Administrator";
            $configinfo = new CS_Api_Config();
            $Forminfo = $configinfo->getConfig('ADMIN_EMAIL');
            $Forminfo['FormName'] = $FormName;
        } else
        {
            $userinfoapi = new CS_Api_User();
            $Forminfo = $userinfoapi->getUserById($formid);
        }

        return $Forminfo;
    }

    function getToinfo($toid)
    {
        if ($toid == 0)
        {
            $ToName = "System Administrator";
            $configinfo = new CS_Api_Config();
            $Toinfo = $configinfo->getConfig('ADMIN_EMAIL');
            $Toinfo['ToName'] = $ToName;
        } else
        {
            $userinfoapi = new CS_Api_User();
            $Toinfo = $userinfoapi->getUserById($toid);
        }
        return $Toinfo;
    }

}