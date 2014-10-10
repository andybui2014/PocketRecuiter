<?php
class PR_Api_Core_CandidateClass extends PR_Api_Core_CandidateExtClass
{
   public function getContactInfo($userID)
   {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('u'=>'user'), array("*"));
        $select->where("UserID = '$userID'");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return null;
        }        
        return $records[0];
   }    
   
    public function saveContactInfo($userID, $data)
    {
        $avaiFields = array('firstname','middlename','lastname','dob','emailaddress',
            'URL','PhoneNumber','Address1','Address2','City','State','PostalCode','Country');
        $contactInfo = $this->getContactInfo($userID);
        
        $updateFields = array();
        foreach($data as $k=>$v){
            if(in_array($k,$avaiFields)){
                if($v != $contactInfo[$k]){
                    $updateFields[$k] = $v;
                }        
            }
        }
        
        if(count($updateFields)==0){
            return 0;
        } else {
            $criteria = "UserID = '$userID'";        
            $result = PR_Database::update("user", $updateFields, $criteria);
            return $result;            
        }
    }
    
    public function getEducationList($userID)
    {
        
    }
    
       
}
