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
            'URL','PhoneNumber','Address1','Address2','City','State','PostalCode','Country','faxnumber','URlnetwork','instanmessaing');  
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
            array('CredentialExperienceID','CandidateProfileID','institution_id','startdate','enddate','title','comments','display')
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
		return $CandidateEmploymentID;
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
    
   public function deleteCandidateEmployment($CandidateEmploymentID)
    {
        $db = PR_Database::getInstance();
        $criteria = "CandidateEmploymentID = '$CandidateEmploymentID'";
        $result = $db->delete('candidate_employments', $criteria);      
        return $result;  
    }
    
   public function getCandidatePortfolio($CandidatePortfolioID)
    {
        //candidate_employments:CandidateEmploymentID,CandidateProfileID,CompanyName,PostionHeld,StartDate,EndDate,Description,LastUpdated,LastUpdatedByUserID
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('capfl'=>'candidate_portfolio'), 
            array('CandidatePortfolioID','CandidateProfileID','Title','URL','Description','IconURL')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = capfl.CandidateProfileID',
            array('UserID')
        );
        $select->where("capfl.CandidatePortfolioID = '$CandidatePortfolioID'");
        $records = PR_Database::fetchAll($select);
        if(count($records)>0){
            return $records[0];
        } else {
            return null;
        }
    }
   public function getCandidateSkill($CandidateSkillID)
    {
       
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('cask'=>'candidate_skill'), 
            array('CandidateSkillID','CandidateProfileID','SkillID','YearsExperience','LevelOfExperience')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = cask.CandidateProfileID',
            array('UserID')
        );
        $select->where("cask.CandidateSkillID = '$CandidateSkillID'");
        $records = PR_Database::fetchAll($select);
        if(count($records)>0){
            return $records[0];
        } else {
            return null;
        }
    }
    public function addCandidatePortfolio($userID,$title,$url,
        $description,$iconURL)
    {
        
        $candidateInfo = $this->getCandidateInfo($userID);
        if(count($candidateInfo)==0){
            return 0;
        }
        
        $candidateProfileID = $candidateInfo['CandidateProfileID'];
        if(empty($candidateProfileID)){
            $candidateProfileID = $this->createCandidateProfileID($userID);
        }
        
        $updateFields = array('CandidateProfileID'=>$candidateProfileID,
            'Title'=>$title,'URL'=>$url,'Description'=>$description,
            'IconURL'=>$iconURL);
        
        
        $CandidatePortfolioID = PR_Database::insert('candidate_portfolio',$updateFields,true);
        return $CandidatePortfolioID;
    }
   public function updateCandidatePortfolio($CandidatePortfolioID,$title,$url,
        $description,$iconURL)
    {
        $candidatePortfolioInfo = $this->getCandidatePortfolio($CandidatePortfolioID);  
        
        $updateFields = array('Title'=>$title, 'URL'=>$url,'Description'=>$description,"IconURL"=>$iconURL);
       
        
        $criteria = "CandidatePortfolioID = '$CandidatePortfolioID'";        
        $result = PR_Database::update('candidate_portfolio',$updateFields,$criteria);        
        return $result;
    }
    public function updateCandidatePortfolioUrl($CandidatePortfolioID,$iconURL)
    {
        
        PR_Database::update('candidate_portfolio',array('IconURL'=>$iconURL),
                "CandidatePortfolioID = '$CandidatePortfolioID'"
        );

        return;
    }
   public function deleteCandidatePortfolio($CandidatePortfolioID)
    {
        $db = PR_Database::getInstance();
        $criteria = "CandidatePortfolioID = '$CandidatePortfolioID'";
        $result = $db->delete('candidate_portfolio', $criteria);      
        return $result;  
    }
   public function getListCandidatePortfolio($userID)
    {        
        
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('capfl'=>'candidate_portfolio'), 
            array('CandidatePortfolioID','CandidateProfileID','Title','URL','Description','IconURL')
        );
        $select->join(array('u'=>'user'),
            'u.CandidateProfileID = capfl.CandidateProfileID',
            array('UserID')
        );
        $select->where("u.UserID = '$userID'");
        $select->where("u.usertype = 2");
        $records = PR_Database::fetchAll($select);
        return $records;        
    } 
   public function saveCandidateSkills($userID,$CandidateSkillID, $skillIDs)
   {
       $candidatesInfo = $this->getCandidateInfo($userID);
      // echo("testt:<pre>")  ;print_r($candidateskillInfo);echo("</pre>");
       $CandidateProfileID=$candidatesInfo["CandidateProfileID"];
      
      //--- select current skills
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('candidate_skill',array('CandidateSkillID','CandidateProfileID','SkillID','YearsExperience','LevelOfExperience'));
        $select->where("CandidateSkillID = '".$CandidateSkillID."' ");   
        $records = PR_Database::fetchAll($select);
        
        $currentSkills = array();
        if(count($records)>0){
            foreach($records as $rec){
                $currentSkills[]=$rec['SkillID'];
            }            
        }


        if(empty($skillIDs) || count($skillIDs)==0){
            $criteria = "CandidateSkillID = '$CandidateSkillID'";
            $result = $db->delete('candidate_skill', $criteria);
        } else if(count($currentSkills)==0){
            foreach($skillIDs as $id){
                echo ("tets");
                $updateFields=array('CandidateSkillID'=>$CandidateSkillID,
                            'SkillID'=>$id,'CandidateProfileID'=>$CandidateProfileID,'YearsExperience'=>'','LevelOfExperience'=>'');
                $result = PR_Database::insert("candidate_skill", $updateFields);                
            }
        } else {
            $arrayDiff_1 = array_diff($currentSkills,$skillIDs);
           // echo("diff:");print_r($arrayDiff_1);
            if(count($arrayDiff_1) > 0){
                $criteria = "CandidateSkillID = '$CandidateSkillID' AND SkillID IN (".implode(",",$arrayDiff_1).")";
                $result = $db->delete('candidate_skill', $criteria);
            }
            
            $arrayDiff_2 = array_diff($skillIDs,$currentSkills);
            if(count($arrayDiff_2) > 0){
                foreach($arrayDiff_2 as $id){
                    $updateFields=array('CandidateSkillID'=>$CandidateSkillID,
                                'SkillID'=>$id,"CandidateProfileID"=>$CandidateProfileID,"YearsExperience"=>"","LevelOfExperience"=>"");
                    $result = PR_Database::insert("candidate_skill", $updateFields);                
                }                
            }
            
        } 
   }
   public function getListAll_CandidateSkills($userID)  
   {
       $db = PR_Database::getInstance();
        //$select = $db->select();
        $sql= $db->select(); 
        $sql="SELECT DISTINCT sk.SkillID,sk.SkillName,sk.ParentSkillID,sk.Level,cask.CandidateProfileID,us.UserID
        FROM skill as sk
        LEFT JOIN candidate_skill as cask ON sk.SkillID=cask.SkillID
        LEFT JOIN user as us  ON cask.CandidateProfileID=us.CandidateProfileID
        WHERE (us.UserID IS NULL OR us.UserID='$userID') and sk.Level=0
        Order By sk.SkillName";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
           
            return $records;
        } else {
            return null;
        }
        
       
   }    
   
   public function getList_CandidateSkillsOnly($userID)  
   {
       $db = PR_Database::getInstance();
        //$select = $db->select();
        $sql= $db->select(); 
        $sql="SELECT DISTINCT sk.SkillID,sk.SkillName,sk.ParentSkillID,sk.Level,cask.SkillID,cask.CandidateProfileID,us.UserID
        FROM skill as sk
        INNER JOIN candidate_skill as cask ON sk.SkillID=cask.SkillID
        INNER JOIN user as us  ON cask.CandidateProfileID=us.CandidateProfileID
        WHERE (us.UserID='$userID') and sk.Level=0
        Order By sk.SkillName";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
           
            return $records;
        } else {
            return null;
        }
        
       
   }
   public function getSKillName($skillid)  
   {
        $db = PR_Database::getInstance();
        $sql= $db->select(); 
        $sql="select SkillName from skill where SkillID='$skillid'";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
            return  $records[0];
        }
         else {
            return null;
        }
   }    
   public function getList_Skills($skillid)  
   {
        $db = PR_Database::getInstance();
        $sql= $db->select(); 
        $sql="select  sk.SkillID,sk.SkillName,sk.ParentSkillID,sk.Level,cask.CandidateProfileID
         from skill as sk 
        LEFT JOIN candidate_skill as cask ON cask.SkillID=sk.SkillID
        where  ParentSkillID in (select SkillID from skill where SkillID = '$skillid')";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
            return  $records;
        }
         else {
            return null;
        }
   }  
    public function getList_CandidateSkillsDad($userID)  
   {
       $db = PR_Database::getInstance();
        //$select = $db->select();   getSKillName
        $sql= $db->select(); 
        $sql="SELECT DISTINCT sk.SkillID,sk.SkillName,sk.ParentSkillID,sk.Level,cask.CandidateProfileID,us.UserID
        FROM skill as sk
        LEFT JOIN candidate_skill as cask ON sk.SkillID=cask.SkillID
        LEFT JOIN user as us  ON cask.CandidateProfileID=us.CandidateProfileID
        WHERE (us.UserID IS NULL OR us.UserID='2') and sk.SkillID  IN (SELECT  ParentSkillID FROM skill)
        Order By sk.SkillName";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
            $aray=array();
            foreach($records as $values)
            {
                $aray[]=$values["SkillID"]; 
                
            }
            $record1=array();
            $record2=array();
            if(!empty($aray)|| $aray!="")
            {
                foreach ($aray as $skills)
                {
                   $record1["SkillID_dad"]=$skills; 
                   $skillname=$this->getSKillName($skills);
                   $record1["SkillName_dad"]=$skillname["SkillName"];
                   $record1["ParentSkillID"]=$this->getList_Skills($skills);  
                   array_push($record2,$record1);
                 
                }     
            }

            return $record2;
        } else {
            return null;
        }
        
       
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
    
    public function getWatchList($filters=NULL,$limit=0, $offset=0)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('o'=>'opportunity'),array('OpportunityID','postedby','posteddate','title','careerdescription','CompanyID'));
        $select->join(array('w'=>'watchlist'),
            'w.OpportunityID = o.OpportunityID',
            array('CandidateID')
        );

        $select->join(array('oca'=>'opportunity_candidate_apply'),
            ('oca.CandidateProfileID = w.CandidateID and oca.OpportunityID = w.OpportunityID') ,
            array('CandidateApplyDate')
        );

        $select->joinLeft(array('c'=>'company'),
            'c.CompanyID = o.CompanyID',
            array('Companyname')
        );

        if(count($filters)>0)
        {
            if(isset($filters)){
                $select->where("w.CandidateID = '".$filters['CandidateID']."'");
            }
        }

        //print_r($select->__toString());die();

        if ( $limit != 0 || $offset != 0)
            $select->limit($limit, $offset);

        $records = PR_Database::fetchAll($select);
        return $records;
    }

    public function deleteWatchList($OpportunityID, $CandidateProfileID)
    {
        if(empty($OpportunityID)){
            return true;
        }
        $db = PR_Database::getInstance();
        $db->delete('watchlist', array(
            'OpportunityID = ?' => $OpportunityID, 'CandidateID = ?' => $CandidateProfileID,
        ));

        $db->delete('opportunity_candidate_apply', array(
            'OpportunityID = ?' => $OpportunityID, 'CandidateProfileID = ?' => $CandidateProfileID,
        ));

        $db->delete('opportunity_candidate_match', array(
            'OpportunityID = ?' => $OpportunityID, 'CandidateProfileID = ?' => $CandidateProfileID,
        ));
        return true;

    }
       
    public function getOpportunitiesMatch($industry,$experienced,$country,$city,$opportunitiesSearchList)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('o'=>'opportunity'),array('OpportunityID'));

        $select->join(array('sk'=>'opportunity_skill'),
            'sk.OpportunityID = o.OpportunityID',
            array()
        );

        $select->join(array('k'=>'skill'),
            'sk.SkillID = k.SkillID',
            array()
        );

        if(!empty($industry)){
            $industry = trim($industry);
            $select->where("o.industry LIKE \"%$industry%\"");

        }

        if(!empty($experienced)){
            $experienced = trim($experienced);
            $select->where("o.experienced LIKE \"%$experienced%\"");
            //$select->where("p.keywords LIKE ?", "%".$keyword."%");
        }

        if(!empty($country)){
            $country = trim($country);
            $select->where("o.country LIKE \"%$country%\"");

        }

        if(!empty($city)){
            $city = trim($city);
            $select->where("o.city LIKE \"%$city%\"");

        }

        if(count($opportunitiesSearchList)>0){
            $select->where("k.SkillID IN (".implode(',',$opportunitiesSearchList).")");
        }

       // print_r($select->__toString());die();

        $select->distinct();
        $records = PR_Database::fetchAll($select);

        if(empty($records) && count($records)==0){
            return array();
        } else {
            $list = array();
            foreach($records as $rec){
                $list[] = $rec['OpportunityID'];
            }
            return $list;
        }
    }

    public function saveOpportunityCandidateApply($updateFields)
    {
        $db = PR_Database::getInstance();
        $result = 0;
        $select = $db->select();
        $select->from(array('oca'=>'opportunity_candidate_apply'),array('*'));

        if(!empty($updateFields['OpportunityID']) && !empty($updateFields['CandidateProfileID'])){
            $select->where("oca.OpportunityID = '".$updateFields['OpportunityID']."' && oca.CandidateProfileID = '".$updateFields['CandidateProfileID']."' ");

           // print_r($select->__toString());die();
            $records = PR_Database::fetchAll($select);
            if(empty($records) && count($records)==0){
                $updateFieldWatchList = array();
                $updateFieldWatchList['OpportunityID'] = $updateFields['OpportunityID'];
                $updateFieldWatchList['CandidateID'] = $updateFields['CandidateProfileID'];

                //params for opportunity_candidate_match

                //OpportunityID CandidateProfileID CandidateAppliedStatus CandidateScreenStatus CandidateScheduleStatus
               // CandidateFeedbackStatus  CandidateOfferStatus CandiateOnboardStatus CandidateFavorite CandidateHideStatus
                $updateFieldOCM['OpportunityID'] = $updateFields['OpportunityID'];
                $updateFieldOCM['CandidateProfileID'] = $updateFields['CandidateProfileID'];
                $updateFieldOCM['CandidateAppliedStatus'] = $updateFields['CandidateAppliedStatus'];
                $updateFieldOCM['CandidateScreenStatus'] = 0;
                $updateFieldOCM['CandidateScheduleStatus'] = 0;
                $updateFieldOCM['CandidateFeedbackStatus'] = 0;
                $updateFieldOCM['CandidateOfferStatus'] = 0;
                $updateFieldOCM['CandiateOnboardStatus'] = 0;
                $updateFieldOCM['CandidateFavorite'] = 0;
                $updateFieldOCM['CandidateHideStatus'] = $updateFields['CandidateHideStatus'];

                $objDateNow = new Zend_Date();
                $updateFields['CandidateApplyDate'] = $objDateNow->toString('yyyy-MM-dd hh:mm:ss');
                $result = PR_Database::insert("opportunity_candidate_apply", $updateFields);
                $resultW = PR_Database::insert("watchlist",$updateFieldWatchList);
                $resultOCM = PR_Database::insert("opportunity_candidate_match",$updateFieldOCM);
            } else {
                $result = 0;
            }

        }

        return $result;
    }

    public function opportunityCandidateHadApplied($opportunityID, $candidateProfileID)
    {
        $db = PR_Database::getInstance();
        $result = 0;
        $select = $db->select();
        $select->from(array('oca'=>'opportunity_candidate_apply'),array('*'));

        if(!empty($opportunityID) && !empty($candidateProfileID)){
            $select->where("oca.OpportunityID = '".$opportunityID."' && oca.CandidateProfileID = '".$candidateProfileID."' ");

            // print_r($select->__toString());die();
            $records = PR_Database::fetchAll($select);
            if(empty($records) && count($records)==0){
                $result = false;
            } else {
                $result = true;
            }

        }

        return $result;
    }

    public function getOpportunityCandidateMatchActivities($CandidateID=NULL,$limit=0, $offset=0)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('o'=>'opportunity'),array('OpportunityID','postedby','posteddate','title','careerdescription','CompanyID'));
        $select->join(array('ocm'=>'opportunity_candidate_match'),
            'ocm.OpportunityID = o.OpportunityID',
            array('*')
        );

        $select->join(array('oca'=>'opportunity_candidate_apply'),
            ('oca.CandidateProfileID = ocm.CandidateProfileID and oca.OpportunityID = ocm.OpportunityID') ,
            array('CandidateApplyDate')
        );

        $select->joinLeft(array('c'=>'company'),
            'c.CompanyID = o.CompanyID',
            array('Companyname')
        );

        if($CandidateID !="")
        {
                $select->where("ocm.CandidateProfileID = '".$CandidateID."'");
            }

        //print_r($select->__toString());die();

        if ( $limit != 0 || $offset != 0)
            $select->limit($limit, $offset);

        $records = PR_Database::fetchAll($select);
        return $records;
    }

	//find children of the node with candidate info.
	public function getList_CandidateSkillsChildren($skillid,$userID)  
	{
       $db = PR_Database::getInstance();
        $sql= $db->select(); 
        $sql="select distinct sk.SkillID,sk.SkillName,sk.ParentSkillID,sk.Level,
		(CASE WHEN u.UserID IS NOT NULL THEN 1 ELSE 0 END) Checked 
		from skill as sk 
		left join candidate_skill ck on ck.SkillID=sk.SkillID
		left join user u on u.CandidateProfileID = ck.CandidateProfileID
		where sk.ParentSkillID='$skillid' and (u.UserID is null or u.UserID='$userID');";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
            return  $records;
        }
         else {
            return null;
        }
	}   
       
    public function updateCandidateSkill($CandidateProfileID,$skillIDs){
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('cs'=>'candidate_skill'),
            array('*')
        );

        $select->where("cs.CandidateProfileID = '$CandidateProfileID'");
        $records = PR_Database::fetchAll($select);
        if(count($records)>0){
            if(empty($skillIDs) || count($skillIDs)==0){
                $criteria = "CandidateProfileID = '$CandidateProfileID'";
                $result = $db->delete('candidate_skill', $criteria);
            } else{
                $currentSkills = array();
                if(count($records)>0){
                    foreach($records as $rec){
                        $currentSkills[]=$rec['SkillID'];
                    }
                }
                $NotExistInUpdate = array_diff($currentSkills,$skillIDs);
                if($NotExistInUpdate !="" && !empty($NotExistInUpdate)){
                    foreach($NotExistInUpdate as $kk=>$CandidateSkillID){
                        $db->delete('candidate_skill', array(
                            'CandidateProfileID = ?' => $CandidateProfileID, 'SkillID = ?' => $CandidateSkillID,
                        ));
                    }
                }
                $diffSkillIDs = array_diff($skillIDs,$currentSkills);
                if($diffSkillIDs !="" && !empty($diffSkillIDs)){
                    foreach($diffSkillIDs as $kk=>$diffID){
                        $insertDiff=array('CandidateProfileID'=>$CandidateProfileID, 'SkillID'=>$diffID);
                        $result = PR_Database::insert("candidate_skill", $insertDiff);
                    }
                }

                $upSkillIDs = array_diff($skillIDs,$diffSkillIDs);
                if($upSkillIDs !="" && !empty($upSkillIDs)){
                    foreach($upSkillIDs as $kk=>$UpSkillID){
                        $updateFields=array('CandidateProfileID'=>$CandidateProfileID, 'SkillID'=>$UpSkillID);
                        $criteria = "CandidateSkillID = '$UpSkillID' AND CandidateProfileID = '$CandidateProfileID'";
                        $result = PR_Database::update("candidate_skill", $updateFields, $criteria);
                    }
                }
            }
        } else {
            if(empty($SkillID) || count($SkillID)==0){
                foreach($skillIDs as $id){
                    $updateFields=array('CandidateProfileID'=>$CandidateProfileID, 'SkillID'=>$id);
                    $result = PR_Database::insert("candidate_skill", $updateFields);
                }
            }
        }
        return true;
    }
       
    public function deleteActivities($OpportunityID, $CandidateProfileID)
    {
        if(empty($OpportunityID)){
            return true;
        }
        $db = PR_Database::getInstance();
        $db->delete('watchlist', array(
            'OpportunityID = ?' => $OpportunityID, 'CandidateID = ?' => $CandidateProfileID,
        ));

        $db->delete('opportunity_candidate_apply', array(
            'OpportunityID = ?' => $OpportunityID, 'CandidateProfileID = ?' => $CandidateProfileID,
        ));

        $db->delete('opportunity_candidate_match', array(
            'OpportunityID = ?' => $OpportunityID, 'CandidateProfileID = ?' => $CandidateProfileID,
        ));
        return true;
    }
       
    public function getCanSkills($candidateProfileID)
    {
        $db = PR_Database::getInstance();
        $result = 0;
        $select = $db->select();
        $select->from(array('ck'=>'candidate_skill'),array('*'));

        if($candidateProfileID !=""){
            $select->where("ck.CandidateProfileID = '".$candidateProfileID."' ");

            // print_r($select->__toString());die();
            $records = PR_Database::fetchAll($select);
            if(empty($records) && count($records)==0){
                $result = false;
            } else {
                $result = true;
            }

        }

        return $result;
    }
       
    public function getCountryList()
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('c'=>'country'),array('*'));

        $records = PR_Database::fetchAll($select);
        return $records;
    }
       
}
