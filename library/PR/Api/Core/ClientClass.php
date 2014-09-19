<?php
class PR_Api_Core_ClientClass
{
    private $avaiUpdateFields = array('UserName','Password','CompanyName','Company_ID','ContactName','ContactPhone1','ContactPhone2','Email1','Email2','BusinessAddress','BusinessCity','BusinessState','BusinessZip','CompanyDescription','RegDate','AccountEnabled','Company_Logo','Country','CreatedBy','LastLoginDate');
    
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
        $criteria = "ClientID = '$clientID'";
        PR_Database::update('clients',$updateFields,$criteria);
    }
}  

