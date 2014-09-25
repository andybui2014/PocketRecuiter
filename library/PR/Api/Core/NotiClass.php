<?php
class PR_Api_Core_NotiClass
{

    public function getList($filters=NULL,$limit=0, $offset=0)
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('n'=>'notification'),array('*'));
        $select->joinLeft(array('senderuser'=>'user'),
            'senderuser.UserID = n.sender_iduser',
            array('cbContactNameT'=>'firstname', 'cbContactLNameT'=>'lastname' )
        );
        $select->joinLeft(array('receiveduser'=>'user'),
            'receiveduser.UserID = n.receiver_iduser',
            array('cbContactNameR'=>'firstname' , 'cbContactLNameR'=>'lastname')
        );

        if(count($filters)>0)
        {
            if(isset($filters)){
                $select->where("sender_iduser = '".$filters."' || receiver_iduser = '".$filters."'");
            }
        }
        if ( $limit != 0 || $offset != 0)
            $select->limit($limit, $offset);
            
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    
    public function delete($notiIDArray)
    {
        if(!is_array($notiIDArray) || count($notiIDArray)==0)
        {
            return;        
        }
        $criteria = "NotificationID IN (".implode(",",$notiIDArray).")";
        $db = PR_Database::getInstance();
        $result = $db->delete('notification', $criteria);
    }
    public function geAllUser()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('u'=>'user'),array('*'));
        $records = PR_Database::fetchAll($select);
        return $records;
    }
}
