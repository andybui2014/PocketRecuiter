<?php
class PR_Api_Core_ClientClass
{
    private $avaiUpdateFields = array('usertype','firstname','middlename','lastname','dob','CompanyID','CandidateProfileID','loginname','password','emailaddress','URL','PhoneNumber','Address1','Address2','City','State','PostalCode','Country','HeardFrom');
    
    public function  __construct() {
        $errMsg="";
    }    
    public function updateClientProfile($clientID,$clientData)
    {
        if(count($clientData)==0){
            return;
        }
        
        $updateFields = array();
        foreach($clientData as $key=>$value){
            if (in_array($key,$this->avaiUpdateFields)) {
                $updateFields[$key] = $value;
            }            
        }

        $criteria = "UserID = '$clientID'";
       $user= PR_Database::update('user',$updateFields,$criteria);
       //echo ("user:".$user);
               
    }
}  

