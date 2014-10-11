<?php
class PR_Api_Core_CandidateClass extends PR_Api_Core_CandidateExtClass
{
   const USER_TYPE_COMPANY = 1;
   const USER_TYPE_CANDIDATE = 2;
    
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
    /*****************************************************************************************************************/
    /********************************************** Candidate Education **********************************************/    
    public function addCandidateEducation($userID,
            $institutionName,$nameDegree,
            $startYear,$endYear)
    {
        //user: UserID,usertype,CandidateProfileID
        $candiInfo = $this->getCandidateInfo($userID);
        if(empty($candiInfo)){
            return 0;
        }
        
        $institutionName = trim($institutionName);        
        //--- institution:institution_id,institution_name,created_by_userid,created_datetime,last_updated_by_userid,last_updated_datetime,institution_type
        $institutionInfo = $this->getInstitutionByName($institutionName);
        
        if(count($institutionInfo)==0){
            $institution_id = $this->addInstitution($userID,$institutionName);
        } else {
            $institution_id = $institutionInfo['institution_id'];
        }
        
        $updateFields = array('CandidateProfileID'=>$candiInfo['CandidateProfileID'],
                    'institution_id'=>$institution_id,
                    'title'=>$nameDegree,
                    'comments'=>''
        );        
        if($startYear > 0){
            $updateFields['startdate']=date("Y-m-d H:i:s",mktime(0,0,0,1,1,$startYear));
        }
        if($endYear > 0){
            $updateFields['enddate']=date("Y-m-d H:i:s",mktime(0,0,0,12,30,$endYear));
        }
        //credentialexperience:CredentialExperienceID,CandidateProfileID,institution_id,startdate,enddate,title,comments
        $CredentialExperienceID =   PR_Database::insert('credentialexperience',$updateFields, true );
        return $CredentialExperienceID;                
    }
    
    public function getInstitutionByName($institutionName)
    {
        $institutionName = trim($institutionName);        
        //--- institution:institution_id,institution_name,created_by_userid,created_datetime,last_updated_by_userid,last_updated_datetime,institution_type
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('institution', array('*'));
        $select->where("institution_name = '$institutionName'");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return null;
        } else {
            return $records[0];
        }       
    }
    
    public function addInstitution($userID,$institutionName)
    {
        $institution_id = PR_Database::insert('institution',
            array('institution_name'=>$institutionName,
                'created_by_userid'=>$userID,
                'created_datetime'=>date("Y-m-d H:i:s"),
                'last_updated_by_userid'=>$userID,
                'last_updated_datetime'=>date("Y-m-d H:i:s"),
            ),
            true
        );
        return $institution_id;
    }
    
    public function getCandidateEducation($CredentialExperienceID)
    {
        //credentialexperience:CredentialExperienceID,CandidateProfileID,institution_id,startdate,enddate,title,comments
        //--- institution:institution_id,institution_name,created_by_userid,created_datetime,last_updated_by_userid,last_updated_datetime,institution_type
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('ce'=>'credentialexperience'), array('*'));
        $select->join(array('i'=>'institution'),
            'i.institution_id = ce.institution_id',
            array('institution_name')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = ce.CandidateProfileID',
            array('UserID')
        );
        $select->where("ce.CredentialExperienceID = '$CredentialExperienceID'");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return null;
        } else {
            return $records[0];
        }
    }
    
    public function updateCandidateEducation($CredentialExperienceID,$institutionName,$nameDegree,
            $startYear,$endYear)
    {
        $CandidateEducationInfo = $this->getCandidateEducation($CredentialExperienceID);
        //print_r($CandidateEducationInfo);
        if(count($CandidateEducationInfo)==0){
            return;
        }
        $userID= $CandidateEducationInfo['UserID'];
        $institutionName = trim($institutionName);        
        $current_institution_id = $CandidateEducationInfo['institution_id'];
        $current_institutionName = $CandidateEducationInfo['institution_name'];
        
        $institution_id = $current_institution_id;
        if(!empty($institutionName) && $institutionName != $current_institutionName){
            //$institution_id   
            $institutionInfo = $this->getInstitutionByName($institutionName);
            if(count($institutionInfo)>0){
                $institution_id = $institutionInfo['institution_id'];
            } else {
                $institution_id = $this->addInstitution($userID,$institutionName);
            }
        }        

        $updateFields = array();
        if($institution_id != $current_institution_id){
            $updateFields['institution_id'] = $institution_id;   
        }
        if($startYear > 0){
            $updateFields['startdate'] = date("Y-m-d H:i:s",mktime(0,0,0,1,1,$startYear));               
        }
        if($endYear>0){
            $updateFields['enddate'] = date("Y-m-d H:i:s",mktime(0,0,0,12,30,$endYear)); 
        }
        if(!empty($nameDegree)){
            $updateFields['title'] = $nameDegree;   
        }

        if(count($updateFields)>0){
            PR_Database::update('credentialexperience', $updateFields,
                  "CredentialExperienceID = '$CredentialExperienceID'"
            );            
        }
        
        return;
    }
    
        
    public function getCandidateEducationList($userID)
    {
        //credentialexperience:CredentialExperienceID,CandidateProfileID,institution_id,startdate,enddate,title,comments
        //--- institution:institution_id,institution_name,created_by_userid,created_datetime,last_updated_by_userid,last_updated_datetime,institution_type
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('ce'=>'credentialexperience'), 
            array('CredentialExperienceID','CandidateProfileID','institution_id','startdate','enddate','title','comments')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = ce.CandidateProfileID',
            array('UserID')
        );
        $select->join(array('i'=>'institution'),
            'i.institution_id = ce.institution_id',
            array('institution_name')
        );
        $select->where("u.UserID = '$userID'");
        $select->where("u.usertype = 2");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    
    public function deleteCandidateEducation($CredentialExperienceID)
    {
        $db = PR_Database::getInstance();
        $criteria = "CredentialExperienceID = '$CredentialExperienceID'";
        $result = $db->delete('credentialexperience', $criteria);      
        return $result;  
    }
    /************************************************************************************/
    /********************************* Candidate Employment *****************************/    
    public function getCandidateEmployments($userID)
    {
        //candidate_employments:CandidateEmploymentID,CandidateProfileID,CompanyName,PostionHeld,StartDate,EndDate,Description,LastUpdated,LastUpdatedByUserID
        
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('caem'=>'candidate_employments'), 
            array('CandidateEmploymentID','CandidateProfileID','CompanyName','PostionHeld','StartDate','EndDate','Description','LastUpdated','LastUpdatedByUserID')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = caem.CandidateProfileID',
            array('UserID')
        );
        $select->where("u.UserID = '$userID'");
        $select->where("u.usertype = 2");
        $records = PR_Database::fetchAll($select);
        return $records;        
    }
    
    public function getCandidateEmployment($CandidateEmploymentID)
    {
        //candidate_employments:CandidateEmploymentID,CandidateProfileID,CompanyName,PostionHeld,StartDate,EndDate,Description,LastUpdated,LastUpdatedByUserID
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('caem'=>'candidate_employments'), 
            array('CandidateEmploymentID','CandidateProfileID','CompanyName','PostionHeld','StartDate','EndDate','Description','LastUpdated','LastUpdatedByUserID')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = caem.CandidateProfileID',
            array('UserID')
        );
        $select->where("caem.CandidateEmploymentID = '$CandidateEmploymentID'");
        $records = PR_Database::fetchAll($select);
        if(count($records)>0){
            return $records[0];
        } else {
            return null;
        }
    }
    
    public function addCandidateEmployment($userID,$companyName,$position,
        $startDate,$endDate,$description)
    {
        //candidate_employments:CandidateEmploymentID,CandidateProfileID,
        //CompanyName,PostionHeld,StartDate,EndDate,Description,LastUpdated,LastUpdatedByUserID
        $candidateInfo = $this->getCandidateInfo($userID);
        if(count($candidateInfo)==0){
            return 0;
        }
        
        $candidateProfileID = $candidateInfo['CandidateProfileID'];
        if(empty($candidateProfileID)){
            $candidateProfileID = $this->createCandidateProfileID($userID);
        }
        
        $updateFields = array('CandidateProfileID'=>$candidateProfileID,
            'CompanyName'=>$companyName,'PostionHeld'=>$position,'Description'=>$description,
            'LastUpdated'=>date("Y-m-d H:i:s"),'LastUpdatedByUserID'=>$userID
        );
        
        if(!empty($startDate)){
            $updateFields['StartDate'] = date("Y-m-d",strtotime($startDate));
        }
        
        if(!empty($endDate)){
            $updateFields['EndDate'] = date("Y-m-d",strtotime($endDate));
        }
        $CandidateEmploymentID = PR_Database::insert('candidate_employments',$updateFields,true);
    }
    
    public function updateCandidateEmployment($CandidateEmploymentID,$companyName,$position,
        $startDate,$endDate,$description)
    {
        $candidateEmploymentInfo = $this->getCandidateEmployment($CandidateEmploymentID);  
        
        //candidate_employments:CandidateEmploymentID,CandidateProfileID,
        //CompanyName,PostionHeld,StartDate,EndDate,Description,LastUpdated,LastUpdatedByUserID        
        $updateFields = array('CompanyName'=>$companyName, 'PostionHeld'=>$position,'Description'=>$description);
        if(!empty($startDate)){
            $updateFields['StartDate'] = date("Y-m-d",strtotime($startDate));
        }
        
        if(!empty($endDate)){
            $updateFields['EndDate'] = date("Y-m-d",strtotime($endDate));
        }
        
        $criteria = "CandidateEmploymentID = '$CandidateEmploymentID'";        
        $result = PR_Database::update('candidate_employments',$updateFields,$criteria);        
        return $result;
    }
    
    
    /************************************************************************************/
    /*************************************** Candidate Info *****************************/    
    public function getCandidateInfo($userID)
    {
        //user: UserID,usertype,CandidateProfileID
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('user', array('*'));
        $select->where("UserID = '$userID'");
        $select->where("usertype = 2");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0) {
           return null; 
        } else {
           return $records[0]; 
        }
    }
    
    public function createCandidateProfileID($userID)
    {
        //--- userInfo
        $userInfo = $this->getCandidateInfo($userID);
        if(empty($userInfo) || count($userInfo)==0) {
           return 0; 
        }
        if($userInfo['usertype'] != self::USER_TYPE_CANDIDATE){
            return 0;
        }
        
        $candidateProfileID = 0;
        if(!empty($userInfo['CandidateProfileID'])) {
            $candidateProfileID = $userInfo['CandidateProfileID'];
        } else {
            $candidateProfileID = PR_Database::insert('candidate_profile',array('usercol1'=>''),true);
            PR_Database::update('user',array('CandidateProfileID'=>$candidateProfileID),
                    "UserID = '$userID'"
            );            
        }
        //print_r($userInfo);
        return $candidateProfileID;
    }
    
       
}
