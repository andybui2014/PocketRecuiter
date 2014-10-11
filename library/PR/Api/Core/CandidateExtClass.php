<?php
class PR_Api_Core_CandidateExtClass
{
    public function getcandidateskill($CandidateProfileID)
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
    public function getCandidateEmployments($CandidateProfileID)
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('candidate_employments'),array('*'));        
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
            $retArray['SkillName'] = $this->getcandidateskill($Candidateprofile_ID);  
            $retArray['CandidateEmploymentID'] = $this->getCandidateEmployments($Candidateprofile_ID);  
           // $retArray['CredentialExperienceID'] = $this->getcandidateEducation($Candidateprofile_ID);       
            return $retArray;
        }
    }
}
