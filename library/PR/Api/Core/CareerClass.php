<?php
class PR_Api_Core_CareerClass
{

    public function getListSkill()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('*'));
        $select->where("ParentSkillID = 0");
        $records = PR_Database::fetchAll($select);
        return $records;
    }

    public function getListCareer($filters=NULL,$limit=0, $offset=0)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
            $select->from(array('cr'=>'career_type'),array('*'));
        if(count($filters)>0)
        {
            if(isset($filters['career_type'])){
                $select->where("cr.CareertypeID = '".$filters['CareertypeID']."' ");
            }
        }
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getCareerTypeByID($CareertypeID)
    {
        $list = $this->getListCareer(array('CareertypeID'=>$CareertypeID));
        if(count($list)==0){
            return array();
        } else {
            return $list[0];
        }
    }
    public function getCompany($filters=NULL,$limit=0, $offset=0)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('c'=>'company'),array('*'));
        if(count($filters)>0)
        {
            if(isset($filters['CompanyID'])){
                $select->where("c.CompanyID = '".$filters['CompanyID']."' ");
            }
        }
           // $select->where("c.CompanyID = '".$CompanyID."'");

        $records = PR_Database::fetchAll($select);
        return $records;
    }

    public function getCompanyByID($CompanyID)
    {
        $list = $this->getCompany(array('CompanyID'=>$CompanyID));
        if(count($list)==0){
            return array();
        } else {
            return $list[0];
        }
    }

    public function getListOpportunity($filters=NULL,$limit=0, $offset=0)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('p'=>'opportunity'),array('*'));
        if(count($filters)>0)
        {
            if(isset($filters['CompanyID'])){
                $select->where("p.CompanyID = '".$filters['CompanyID']."' ");
            }
            if(isset($filters['OpportunityID'])){
                $select->where("p.OpportunityID = '".$filters['OpportunityID']."' ");
            }
        }
        if ( $limit != 0 || $offset != 0)
            $select->limit($limit, $offset);

        $records = PR_Database::fetchAll($select);
        if(empty($records) && count($records)==0){
            return array();
        } else {
            $list = array();
            foreach($records as $rec){
                $rec['Skills'] = $this->getSkillByOpportunityID($rec['OpportunityID']);
                $rec['Tests'] = $this->getTestByOpportunityID($rec['OpportunityID']);

                $list[] = $rec;
            }
            return $list;
        }
    }

    public function getOpportunityInfoByID($oppID)
    {
        $list = $this->getListOpportunity(array('OpportunityID'=>$oppID));
        if(count($list)==0){
            return array();
        } else {
            return $list[0];
        }
    }

    public function getSkillByOpportunityID($oppID)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('SkillID','SkillName'));
        $select->join(array('Opp_sk'=>'opportunity_skill'),
                    'Opp_sk.SkillID = sk.SkillID',
                    array()
        );
        $select->where("Opp_sk.OpportunityID = '".$oppID."' ");

        $records = PR_Database::fetchAll($select);
        if(empty($records) && count($records)==0){
            return array();
        } else {
            return $records;
        }
    }

    public function getTestByOpportunityID($oppID)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('t'=>'test'),array('TestID','CompanyID','TestName'));
        $select->join(array('ot'=>'opportunity_test'),
            'ot.TestID = t.TestID',
            array()
        );
        $select->where("ot.CareerID = '".$oppID."' ");

        $records = PR_Database::fetchAll($select);
        if(empty($records) && count($records)==0){
            return array();
        } else {
            return $records;
        }
    }   
    
    public function saveCareerSkills($careerID, $skillIDs)
    {
        //--- select current skills
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('opportunity_skill',array('SkillID','OpportunityID'));
        $select->where("OpportunityID = '".$careerID."' ");
        $records = PR_Database::fetchAll($select);
        
        $currentSkills = array();
        if(count($records)>0){
            foreach($records as $rec){
                $currentSkills[]=$rec['SkillID'];
            }            
        }


        if(empty($skillIDs) || count($skillIDs)==0){
            $criteria = "OpportunityID = '$careerID'";
            $result = $db->delete('opportunity_skill', $criteria);
        } else if(count($currentSkills)==0){
            foreach($skillIDs as $id){
                $updateFields=array('OpportunityID'=>$careerID,
                            'SkillID'=>$id);
                $result = PR_Database::insert("opportunity_skill", $updateFields);                
            }
        } else {
            $arrayDiff_1 = array_diff($currentSkills,$skillIDs);
            if(count($arrayDiff_1) > 0){
                $criteria = "OpportunityID = '$careerID' AND SkillID IN (".implode(",",$arrayDiff_1).")";
                $result = $db->delete('opportunity_skill', $criteria);
            }
            
            $arrayDiff_2 = array_diff($skillIDs,$currentSkills);
            if(count($arrayDiff_2) > 0){
                foreach($arrayDiff_2 as $id){
                    $updateFields=array('OpportunityID'=>$careerID,
                                'SkillID'=>$id);
                    $result = PR_Database::insert("opportunity_skill", $updateFields);                
                }                
            }
            
        }

    }

    public function saveCareerTests($careerID, $testIDs)
    {
        //opportunity_test: OpportunityTestID,CareerID,TestID
        //--- select current tests
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('opportunity_test',array('OpportunityTestID','CareerID','TestID' ));
        $select->where("CareerID = '".$careerID."' ");
        $records = PR_Database::fetchAll($select);
        
        $currentTests = array();
        if(count($records)>0){
            foreach($records as $rec){
                $currentTests[]=$rec['TestID'];
            }            
        }
        
        if(empty($testIDs) || count($testIDs)==0){
            $criteria = "CareerID = '$careerID'";
            $result = $db->delete('opportunity_test', $criteria);
        } else if(count($currentTests)==0){
            foreach($testIDs as $id){
                $updateFields=array('CareerID'=>$careerID,'TestID'=>$id);
                $result = PR_Database::insert("opportunity_test", $updateFields);                
            }
        } else {
            $arrayDiff_1 = array_diff($currentTests,$testIDs);
            if(count($arrayDiff_1) > 0){
                $criteria = "CareerID = '$careerID' AND TestID IN (".implode(",",$arrayDiff_1).")";
                $result = $db->delete('opportunity_test', $criteria);
            }
            
            $arrayDiff_2 = array_diff($testIDs,$currentTests);
            if(count($arrayDiff_2) > 0){
                foreach($arrayDiff_2 as $id){
                    $updateFields=array('CareerID'=>$careerID,'TestID'=>$id);
                    $result = PR_Database::insert("opportunity_test", $updateFields);                
                }                
            }            
        }
    }
    
    public function deleteCareer($careerID)
    {
        //opportunity:OpportunityID
        //echo("isUsing: ".$this->isUsing($careerID).'<br/>');
        if(empty($careerID)){
            return true;
        }
        //--- is in using?
        if($this->isUsing($careerID)){
            return false;
        }
        
        $db = PR_Database::getInstance();
        $criteria = "OpportunityID = '$careerID'";
        $result = $db->delete('opportunity', $criteria);
        
        return true;
    }
    
    public function isUsing($careerID)
    {
        //opportunity_skill,opportunity_candidate_match,opportunity_test
        $isUsing = false;  
        $db = PR_Database::getInstance();
        //--- opportunity_test
        $select = $db->select();
        $select->from('opportunity_test',array('CareerID'));
        $select->where("CareerID = '".$careerID."' ");
        $records = PR_Database::fetchAll($select);        
        if(count($records)>0){
           $isUsing = true; 
        }
        //--- opportunity_skill: OpportunityID
        $select_1 = $db->select();
        $select_1->from('opportunity_skill',array('OpportunityID'));
        $select_1->where("OpportunityID = '".$careerID."' ");
        $records_1 = PR_Database::fetchAll($select_1);        
        if(count($records_1)>0){
           $isUsing = true; 
        }
        //--- opportunity_candidate_match: OpportunityID
        $select_2 = $db->select();
        $select_2->from('opportunity_candidate_match',array('OpportunityID'));
        $select_2->where("OpportunityID = '".$careerID."' ");
        $records_2 = PR_Database::fetchAll($select_2);        
        if(count($records_2)>0){
           $isUsing = true; 
        }
        //echo("<br/>fun-isUsing: $careerID- $isUsing");
        return $isUsing;                 
    }
    
}
