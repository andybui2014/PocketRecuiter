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
        $select->where("ot.career_idcareer = '".$oppID."' ");

        $records = PR_Database::fetchAll($select);
        if(empty($records) && count($records)==0){
            return array();
        } else {
            return $records;
        }
    }
}
