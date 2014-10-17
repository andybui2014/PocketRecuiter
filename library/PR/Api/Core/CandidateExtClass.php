<?php
class PR_Api_Core_CandidateExtClass
{
    public function get_skill($SkillID)
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('SkillID','SkillName'));
        
       
        $select->where("sk.SkillID = '$SkillID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getskillID($SkillName)
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('SkillID'));
        
       
        $select->where("sk.SkillName = '$SkillName'");
        $records = PR_Database::fetchAll($select);
        return $records[0];
    }
    public function get_Listskill()
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('*'));
        $select->where("sk.Level = '0'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getcandidate_skill($CandidateProfileID)
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('SkillID','SkillName'));
        $select->join(array('c_sk'=>'candidate_skill'),
                    'c_sk.SkillID = sk.SkillID',
                    array()
        );
       
        $select->where("c_sk.CandidateProfileID = '$CandidateProfileID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getcandidateEducation($CandidateProfileID)
    {

       $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('credentialexperience'),array('*'));        
        $select->where("CandidateProfileID = '$CandidateProfileID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getCandidate_Employments($CandidateProfileID)
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('candidate_employments'),array('CandidateEmploymentID','CandidateProfileID','CompanyName','PostionHeld','StartDate','EndDate','Description','LastUpdated','LastUpdatedByUserID'));        
        $select->where("CandidateProfileID = '$CandidateProfileID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getCandidateProfile($Candidateprofile_ID)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('candidate_profile', array('*'));
        $select->where("CandidateProfileID = '$Candidateprofile_ID'");
        
        
      // print_r($select->__toString());die();
        $records = PR_Database::fetchAll($select);
        
        if(empty($records) || count($records)==0){
            return array();
        } else {
            $retArray = array();
            foreach($records[0] as $key=>$val){
                $retArray[$key] = $val;
            }    
            //$retArray['SkillName'] = $this->getcandidate_skill($Candidateprofile_ID);  
            $retArray['CandidateEmploymentID'] = $this->getCandidate_Employments($Candidateprofile_ID);  
           // $retArray['CredentialExperienceID'] = $this->getcandidateEducation($Candidateprofile_ID);       
            return $retArray;
        }
    }
   public function updateCandidateProfile($userID,$updatefiles)   
    {
       $avaiFields= array("firstname","lastname","CandidateProfileID","tagline","Overview","minimumsalary","maximumsalary","CandidateSkillID","SkillID","keywords","servicedescription");
       $api=new PR_Api_Core_CandidateClass();
      
      
        $listskill=$updatefiles["SkillID"];
        
        //echo("tetst:");print_r($listskill);die();
        // update table user
       $updateFields = array('firstname'=>$updatefiles["firstname"], 'lastname'=>$updatefiles["lastname"]);
        
        $criteria = "UserID = '$userID'";        
        PR_Database::update('user',$updateFields,$criteria);
        //update table candidate
        $candidateInfo = $api->getCandidateInfo($userID);
        if(count($candidateInfo)==0){
            return 0;
        }
        
        $candidateProfileID = $candidateInfo['CandidateProfileID'];
        $updateFields1=array('tagline'=>$updatefiles["tagline"], 'Overview'=>$updatefiles["Overview"],'minimumsalary'=>$updatefiles["minimumsalary"], 'maximumsalary'=>$updatefiles["maximumsalary"],
        'keywords'=>$updatefiles["keywords"],'servicedescription'=>$updatefiles["servicedescription"]);  
        $criteria1 = "CandidateProfileID = '$candidateProfileID'";        
        PR_Database::update('candidate_profile',$updateFields1,$criteria1);
        
        $db = PR_Database::getInstance();  
        $select = $db->select();
        $select->from('candidate_skill',array('*'));            
        $select->where("CandidateProfileID = '$candidateProfileID'");
        $records = PR_Database::fetchAll($select);  
        if(empty($records) && count($records)>0){
            return -1;
        } 
        
        $CandidateSkillID = $records[0]['CandidateProfileID'];

        $skillIDs=array();
        foreach($listskill as $Skills)
        {
             $skillIDs[]=$Skills["SkillID"];
        }
       
       // echo("tetst:");print_r($skillIDs);die(); 
        $api->saveCandidateSkills($userID,$CandidateSkillID,$skillIDs);
       
    }
}
