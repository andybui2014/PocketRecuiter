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
     public function getListCompany()
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        //$fieldList = array("Companyname","Industry","Address","Decreption");   
        $select = $db->select();
       // $select->from(PR_Database::TABLE_COMPANY, $fieldList); 
        $select->from("company", array("*"));             
      
        $companys = PR_Database::fetchAll($select);
        
        return $companys;
    }
     public function getCompany($companyid)
    {             
       $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("company", array("*"));
        $select->where("CompanyID = '$companyid'");
        $record = PR_Database::fetchAll($select);
        if(empty($record)) return NULL;
        else return $record[0];        
    }

    public function getDefaultCompany()
    {             
       $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from("company", array("*"));   
        $select->where("IsDefaultCompany = 1");
        $record = PR_Database::fetchAll($select);
        if(empty($record)) return NULL;
        else return $record[0];        
    }
    
    public function updatecompanyProfile($companyid,$Data)
    {
       $avaiUpdateFields1 = array('Companyname','Industry','Address',
                'Decreption','images','PhoneNumber','country','emailinfo','Zipcode');       

        if(count($companyid)==0){
            return;
        }
        
        $updateFields = array();
        foreach($Data as $key=>$value){
            if (in_array($key,$avaiUpdateFields1)) {
                $updateFields[$key] = $value;
            }            
        }

        $criteria = "CompanyID = '$companyid'";
        //echo ("criteria:".$criteria);print_r($updateFields);die();
        $result= PR_Database::update('company',$updateFields,$criteria);
        return $result;
    }

        
}  

