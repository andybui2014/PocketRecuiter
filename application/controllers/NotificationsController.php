<?php

class NotificationsController extends Application_Controller_Action
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
    
    public function listAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $clientID =  $sestionClient['UserID'];
        $notiClass = new PR_Api_Core_NotiClass();
        $result = $notiClass->getList($clientID);
        $useCS = new PR_Api_Core_NotiClass();
        $uselist = $useCS->geAllUser();
     //  echo "<pre>";
     // echo "client= ";print_r($idCurrentActive);
       //echo "</pre>";die();
        $this->view->result = $result;

        $this->view->clientIDLogin = $clientID;
        $this->view->userName = $sestionClient['firstname'];
        $this->view->uselist = $uselist;
    }
/*
     private function getList($filters=NULL,$limit=0, $offset=0)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('n'=>'notification'),array('*'));
        $select->joinLeft(array('senderuser'=>'user'),
            'senderuser.UserID = n.sender_iduser',
            array('cbContactNameT'=>'firstname')
        );
        $select->joinLeft(array('receiveduser'=>'user'),
           'receiveduser.UserID = n.receiver_iduser',
            array('cbContactNameR'=>'firstname')
        );

        if(count($filters)>0)
        {
            if(isset($filters['ClientID'])){
               $select->where("sender_iduser = 1 || receiver_iduser = 1");
           }
        }
        if ( $limit != 0 || $offset != 0)
            $select->limit($limit, $offset);

        $records = PR_Database::fetchAll($select);
        return $records;
    }
*/
    /*
    private function geAllUser()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('u'=>'user'),array('*'));
        $records = PR_Database::fetchAll($select);
        return $records;
    }
*/
    public function saveNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $subjectNotification = $request->getParam("subjectNotification", "");
        $contentNotification = $request->getParam("contentNotification", "");
        $receideid = $request->getParam("receiverid", "");
        //echo "<pre>";
         //   print_r($receideid);
        //echo "</pre>";die();
            $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
            $clientID =  $sestionClient['UserID'];
            $noti = new PR_Api_Noti(null);
            $updateFields = array('subjecttext'=>$subjectNotification,'sender_iduser'=>$clientID,'sender_iduser'=>$clientID, 'receiver_iduser'=>$receideid, 'content'=>$contentNotification);
            $result = $noti->save($updateFields);

        if($result){
            $return = array("success" => 1, "error" => "");
        } else{
            $return = array("success" => 0, "error" => "1");
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }

    public function saveEditNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();

        $subject = $request->getParam("editNotification", "");
        $content = $request->getParam("editcontentNotification", "");
        $ModalEditNotiID = $request->getParam("ModalEditNotiID", "");
        $ModalEditNotiID =trim($ModalEditNotiID);
       // echo "<pre>";
       //print_r($ModalEditNotiID);
       // echo "</pre>"; die();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);
        $clientID =  $sestionClient['UserID'];
        $noti = new PR_Api_Noti($ModalEditNotiID);
        //$updateFields = array('subjecttext'=>$content,'sender_iduser'=>$clientID,'sender_iduser'=>$clientID, 'receiver_iduser'=>$receideid);
        $updateFields = array('subjecttext'=>$subject,'sender_iduser'=>$clientID,'content'=>$content);
        $result = $noti->save($updateFields);
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

    public function deleteNotificationsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $listNotiID = $request->getParam("listNotiID", "");
        $idCurrentActive = $request->getParam("idCurrentActive", "");
        $core = new PR_Api_Core_NotiClass();
        $return = $core->delete($listNotiID);
        if(empty($return)){
            $return = array("success" => 1, "error" => "");
        }else{
            $return = array("success" => 0, "error" => "");
        }
        //return
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }
}
