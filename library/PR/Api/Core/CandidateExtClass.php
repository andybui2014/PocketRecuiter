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
       $avaiFields= array("firstname","lastname","CandidateProfileID","tagline","overview","minimumsalary","maximumsalary","CandidateSkillID","SkillID","keywords","servicedescription");
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
        $updateFields1=array('tagline'=>$updatefiles["tagline"], 'overview'=>$updatefiles["overview"],'minimumsalary'=>$updatefiles["minimumsalary"], 'maximumsalary'=>$updatefiles["maximumsalary"],
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
    public function updateEducation($CredentialExperienceID,$institutionName,$nameDegree,
            $startdate,$enddate,$comments,$display)
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
        if(!empty($startdate)){
            
           $month = date("m",strtotime($startdate));
           $year= (explode(",",$startdate));
           $updateFields['startdate'] = date("Y-m-d H:i:s",mktime(0,0,0,$month,1,$year[1]));  
           
        }
        if(!empty($enddate)){
           
            $month = date("m",strtotime($enddate));
            $year= (explode(",",$enddate));
            $updateFields['enddate']=date("Y-m-d H:i:s",mktime(0,0,0,$month,1,$year[1]));   
        }
        if(!empty($nameDegree)){
            $updateFields['title'] = $nameDegree;   
        }
        if(!empty($comments))
        {
             $updateFields['comments'] = $comments;
        }
         if(isset($display))
        {
             $updateFields['display'] = $display;
        }
        // echo("testt:<pre>");print_r($updateFields);echo("</pre>");    
        if(count($updateFields)>0){
            PR_Database::update('credentialexperience', $updateFields,
                  "CredentialExperienceID = '$CredentialExperienceID'"
            );            
        }
        
        return;
    }
   public function addEducation($userID,
            $institutionName,$nameDegree,
            $startdate,$enddate,$comments,$display)
    {
        //user: UserID,usertype,CandidateProfileID
        $candiInfo = $this->getCandidateInfo($userID);
        if(empty($candiInfo)){
            return 0;
        }
        
        $institutionName = trim($institutionName);        
        //--- institution:institution_id,institution_name,created_by_userid,created_datetime,last_updated_by_userid,last_updated_datetime,institution_type,comments
        $institutionInfo = $this->getInstitutionByName($institutionName);
        
        if(count($institutionInfo)==0){
            $institution_id = $this->addInstitution($userID,$institutionName);
        } else {
            $institution_id = $institutionInfo['institution_id'];
        }
        
        $updateFields = array('CandidateProfileID'=>$candiInfo['CandidateProfileID'],
                    'institution_id'=>$institution_id,
                    'title'=>$nameDegree,
                    'comments'=>$comments,
                    'display'=>$display 
        );        
        if(!empty($startdate)){
           $month = date("m",strtotime($startdate));
           $year= (explode(",",$startdate));
           $updateFields['startdate'] = date("Y-m-d H:i:s",mktime(0,0,0,$month,1,$year[1])); 
        }
        if(!empty($enddate)){
            $month = date("m",strtotime($enddate));
            $year= (explode(",",$enddate));
            $updateFields['enddate']=date("Y-m-d H:i:s",mktime(0,0,0,$month,1,$year[1]));   
        }
        
        $CredentialExperienceID =   PR_Database::insert('credentialexperience',$updateFields, true );
        return $CredentialExperienceID;                
    }
    public function setPortfolioid(){
        $db = PR_Database::getInstance();
        $maxIdSql = "SELECT MAX(CandidatePortfolioID) AS CandidatePortfolioID  FROM candidate_portfolio";
        $result = $db->fetchAll($maxIdSql);
        $portfolioID=$result[0]['CandidatePortfolioID']+1; 
        return $portfolioID;
    }
   public function saveImagesPortfolio($portfolioID,$image)
    {
               
        $updateFields = array('CandidatePortfolioID'=>$portfolioID,
            'images'=>$image);       
        $result = PR_Database::insert('upload_portfolio',$updateFields,true);
        return $result;
    }
   public function getImagesPortfolio($portfolioID)
    {
         $db = PR_Database::getInstance();      
         $select = $db->select();
         $select->from('upload_portfolio', array('*'));
         $select->where("CandidatePortfolioID = '$portfolioID'");
         $records = PR_Database::fetchAll($select);
         if(!empty($records))
         {
             return $records;  
         }   
         else return 0;   
    }
    public function deleteImagesPortfolio($id)
    {
         $db = PR_Database::getInstance();
         $criteria = "ID = '$id'";
         $result = $db->delete('upload_portfolio', $criteria);
    }
    public function updateCandidateProfileVideo($CandidateProfileID,$video)
    {
        
       $result= PR_Database::update('candidate_profile',array('video'=>$video),
                "CandidateProfileID = '$CandidateProfileID'"
        );

        return $result;
    }
   public function updateCandidateProfilePhoto($CandidateProfileID,$image)
    {
        
       $result= PR_Database::update('candidate_profile',array('image'=>$image),
                "CandidateProfileID = '$CandidateProfileID'"
        );

        return $result;
    }
   public function get_skill_array($SKILLID)
   {
       
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('sk'=>'skill'),array('*'));
        $select->join(array('c_sk'=>'candidate_skill'),
                    'c_sk.SkillID = sk.SkillID',
                    array('*')
        );
       
        $select->where("c_sk.SkillID = '$SKILLID'");
        $records = PR_Database::fetchAll($select);
        if(!empty($records))
        {
            return $records;
        }
        else
        {
            return 0;
        }
   }
   public function updateCandidate_skill($CandidateProfileID,$SkillID,$YearsExperience,$LevelOfExperience)
    {
        
       $result= PR_Database::update('candidate_skill',array('YearsExperience'=>$YearsExperience,'LevelOfExperience'=>                   $LevelOfExperience),
                array("SkillID = '$SkillID'","CandidateProfileID='$CandidateProfileID'")
        );

        return $result;
    }
	public function getcandidate_Interest($CandidateProfileID)
    {

        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('itr'=>'interest'),array('*'));
        
       
        $select->where("itr.CandidateProfileID = '$CandidateProfileID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }
	public function AddInterest($CandidateProfileID,$interesttext)
    {
		$db = PR_Database::getInstance();
		$select = $db->select();
		$select->from(array('itr'=>'interest'),array('*'));       
        $select->where("itr.interesttext = '$interesttext'");
		$select->where("itr.CandidateProfileID = '$CandidateProfileID'");
        $records = PR_Database::fetchAll($select);
		if(empty($records)){
			$updateFields=array("CandidateProfileID"=>$CandidateProfileID,"interesttext"=>$interesttext);
			$CredentialExperienceID = PR_Database::insert('interest',$updateFields, true );
			return $CredentialExperienceID; 
		}
		else{
		$erorr=array("Erorr"=>"interesttext is exit");
		return $erorr;
		}
        
    }
	public function deleteInterest($interestid)
    {
         $db = PR_Database::getInstance();
        // $criteria = "interestid = '$interestid'";
		 $criteria = "interestid IN (".implode(",",$interestid).")";
         $result = $db->delete('interest', $criteria);   
        return $result; 
    }
	 public function getInterest($interestid)
    {
         $db = PR_Database::getInstance();      
         $select = $db->select();
         $select->from('interest', array('*'));
         $select->where("interestid = '$interestid'");
         $records = PR_Database::fetchAll($select);
         if(!empty($records))
         {
             return $records;  
         }   
         else return 0;   
    }
	public function updateInterest($interestid,$interesttext)
    {
        
       $result= PR_Database::update('interest',array('interesttext'=>$interesttext),
                array("interestid = '$interestid'")
        );

        return $result;
    }
  public function getListInterest_candidateprofile($CandidateProfileID){
		 $db = PR_Database::getInstance();      
         $select = $db->select();
         $select->from('interest', array('*'));
         $select->where("CandidateProfileID = '$CandidateProfileID'");
         $records = PR_Database::fetchAll($select);
         if(!empty($records))
         {
             return $records;  
         }   
         else return 0;  
  }
  public function getListReferences_candidateprofile($CandidateProfileID){
		 $db = PR_Database::getInstance();      
         $select = $db->select();
         $select->from('reference', array('*'));
         $select->where("CandidateProfileID = '$CandidateProfileID'");
         $records = PR_Database::fetchAll($select);
         if(!empty($records))
         {
             return $records;  
         }   
         else return 0;  
  }
  public function addReferences($CandidateProfileID,$referencename,$referenceemail,$referencecomment){

		$reference = array("CandidateProfileID"=>$CandidateProfileID,"referencename"=>$referencename,"referenceemail"=>$referenceemail,"referencecomment"=>$referencecomment);
        
		$referenceid = PR_Database::insert('reference',$reference,true);
        return $referenceid;
  }
 public function get_jobfuntion($CandidateProfileID){
		
		$db = PR_Database::getInstance();
        $sql= $db->select(); 
        $sql="
		select jb.JobFunctionID,jb.JobFucntion,crjb.CandidateProfileID,crjb.Percentage
		from jobfunction as jb
		left join credentialexperiencejobfunction as crjb on crjb.JobFunctionID=jb.JobFunctionID
		where crjb.CandidateProfileID='$CandidateProfileID' and crjb.Percentage<=1;";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        if(count($records)>0){
            return  $records;
        }
         else {
            return null;
        }
	}
  public function getExperience($CandidateProfileID){
		$db = PR_Database::getInstance();
        $select= $db->select(); 
		$select->from("credentialexperience",array("*"));
		$select->where("CandidateProfileID = '$CandidateProfileID'");
		$records = PR_Database::fetchAll($select);
         if(!empty($records))
         {
             return $records;  
         }   
         else return null; 
  }
 /*public function addjobfunction($CandidateProfileID,$JobFucntion,$Percentage){
		
		$db = PR_Database::getInstance();		
		//$maxIdSql = "SELECT MAX(JobFunctionID) AS JobFunctionID  FROM jobfunction";
		//$result = $db->fetchAll($maxIdSql);
		//$JobFunctionID=$result[0]['JobFunctionID']+1;
		//$updatejobfunction=array("JobFunctionID"=>$JobFunctionID,"JobFucntion"=>$JobFucntion);
		//$JobFunctionID=PR_Database::insert('jobfunction',$updatejobfunction, true );
		//$updateexperiencejob=array("JobFunctionID"=>$JobFunctionID,"CredentialExperienceID"=>$CredentialExperienceID,"Percentage"=>$Percentage);
		$updateexperiencejob=array("JobFunctionID"=>$JobFucntion,"Percentage"=>$Percentage,"CandidateProfileID"=>$CandidateProfileID);
		PR_Database::insert('credentialexperiencejobfunction',$updateexperiencejob, true );
		//return $JobFunctionID;
		
	}*/
public function addjobfunction($CandidateProfileID,$JobFucntion,$Percentage)
    {
        //echo "Percentage:".$Percentage;die();       
        $updateFields = array("JobFunctionID"=>$JobFucntion,"Percentage"=>$Percentage,"CandidateProfileID"=>$CandidateProfileID);       
        $result = PR_Database::insert('credentialexperiencejobfunction',$updateFields);
        return $result;
    }
 public function getJobFunction($JobFunctionID){
		$db = PR_Database::getInstance();      
		$select = $db->select();
		$select->from(array('jb'=>'jobfunction'), array('*'));
		$select->join(array('crj'=>'credentialexperiencejobfunction'),
                    'crj.JobFunctionID = jb.JobFunctionID',
                    array('*')
        );
		$select->where("jb.JobFunctionID = '$JobFunctionID'");
		//print_r($select->__tostring());
		$records = PR_Database::fetchAll($select);
		if(!empty($records))
		{
			return $records[0];  
		}   
		else return 0;  
	}
 public function updateJobFunction($JobFunctionID,$CandidateProfileID,$Percentage){
		$updatefield=array('JobFunctionID'=>$JobFunctionID);
		$updatefield1=array('JobFunctionID'=>$JobFunctionID,"CandidateProfileID"=>$CandidateProfileID,"Percentage"=>$Percentage);
		$result=PR_Database::update('jobfunction',$updatefield, array("JobFunctionID = '$JobFunctionID'"));
		$result1=PR_Database::update('credentialexperiencejobfunction',$updatefield1,array("JobFunctionID = '$JobFunctionID'"));
        return $result.$result1;

        
	}
 public function deleteJobFunction($JobFunctionID){
		$db = PR_Database::getInstance();
         $criteria = "JobFunctionID = '$JobFunctionID'";
		 //$criteria = "JobFunctionID IN (".implode(",",$JobFunctionID).")";
         $result = $db->delete('credentialexperiencejobfunction', $criteria);   
		 
       // return $result.$result1; 
 }
 public function totalPercentage($CandidateProfileID){
		$db = PR_Database::getInstance();
        $sql= $db->select(); 
        $sql="
		select SUM(crjb.Percentage) as totalPercentage
		from credentialexperiencejobfunction as crjb		
		where crjb.CandidateProfileID='$CandidateProfileID';";
        $select = $db->query($sql);
       
        $records = $select->fetchAll();
        return $records[0];
  } 
 public function getjobfunctions(){
		$db = PR_Database::getInstance();
		$select = $db->select();
		$select->from(array('jb'=>'jobfunction'), array('*'));
		$records = PR_Database::fetchAll($select);
		if(!empty($records))
		{
			return $records;  
		}   
		else return null;  
 }
 public function getjobfunctionsid($JobFunctionID){
		$db = PR_Database::getInstance();
		$select = $db->select();
		$select->from(array('jb'=>'jobfunction'), array('*'));
		$select->where("JobFunctionID = '$JobFunctionID'");
		$records = PR_Database::fetchAll($select);
		if(!empty($records))
		{
			return $records[0];  
		}   
		else return null;  
 }
 
}
  

 

