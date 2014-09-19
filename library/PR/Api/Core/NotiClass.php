<?php
class PR_Api_Core_NotiClass
{

    public function getList($filters=NULL,$limit=0, $offset=0)
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('n'=>'notis'),array('*'));
        $select->joinLeft(array('c'=>'clients'),
            'c.ClientID = n.cbClientID',
            array('cbContactName'=>'ContactName')
        );
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
    
    public function delete($notiIDArray)
    {
        if(!is_array($notiIDArray) || count($notiIDArray)==0)
        {
            return;        
        }
        $criteria = "NotiID IN (".implode(",",$notiIDArray).")";
        $db = PR_Database::getInstance();
        $result = $db->delete('notis', $criteria);
    }
}
