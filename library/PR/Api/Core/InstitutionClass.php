<?php
class PR_Api_Core_InstitutionClass   
{
    //institution: institution_id,institution_type,institution_name,created_by_userid,created_datetime,last_updated_by_userid,last_updated_datetime
    public function getList()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('institution', array("*"));
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    
    //return institution_id
    public function add($userID,$institutionName)
    {
        if(empty($institutionName)) return 0;
        
        //--- get institution_id by $institutionName
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('institution', array('institution_id','institution_name'));
        $select->where("institution_name = '$institutionName'");
        $records = PR_Database::fetchAll($select);
        $institution_id = 0;
        if(count($records)>0){
            $institution_id = $records[0]['institution_name'];
        }
        
        if($institution_id > 0){
            return $institution_id;
        }
        
        //--- 
        $updateFields=array('institution_name'=>$name,
            'created_by_userid'=>$userID,'created_datetime' =>date('Y-m-d H:i:s'),
            'last_updated_by_userid'=>$userID,'last_updated_datetime' =>date('Y-m-d H:i:s'),            
        );
        $id = PR_Database::insert("institution", $updateFields,true);                
        return $id;
    }
}  

