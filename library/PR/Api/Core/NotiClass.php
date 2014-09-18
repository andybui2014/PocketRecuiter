<?php
class PR_Api_Core_NotiClass
{

    public function getList($filters=NULL,$limit=0, $offset=0)
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("notis",array('*'));
        if(count($filters)>0)
        {
            if(isset($filters['ClientID'])){
                $select->where("cbClientID = '".$filters['ClientID']."'");                
            }
        }
        if ( $limit != 0 || $offset != 0)
            $select->limit($limit, $offset);
            
        $records = PR_Database::fetchAll($select);
        return $records;
    }
}
