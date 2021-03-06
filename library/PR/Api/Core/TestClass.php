<?php
class PR_Api_Core_TestClass
{
    public function getTestListForCompany($companyID)
    {
        $filters = array("CompanyID"=>$companyID);   
        $list = $this->getTestList($filters); 
        return $list;
    }
    
    public function getTestList($filters=NULL,$limit=0, $offset=0)
    {             
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from(array('t'=>'test'),
            array('TestID','CompanyID','posteddate','createdBy','TestName',
                "NumQuestions"=>"(SELECT COUNT(*) FROM test_question WHERE Test_TestID=t.TestID)"
            )
        );
        
        if(count($filters)>0)
        {
            if(isset($filters['CompanyID'])){
               $select->where("t.CompanyID = '".$filters['CompanyID']."'");
            }
        }
        if ( $limit != 0 || $offset != 0)
        {
            $select->limit($limit, $offset);            
        }
            
        $select->order("TestID");    
            
        $records = PR_Database::fetchAll($select);
        return $records;
    }
    public function getTestListByID($CompanyID)
    {
        $list = $this->getTestList(array('CompanyID'=>$CompanyID));
        if(count($list)==0){
            return array();
        } else {
            return $list[0];
        }
    }
    public function create($updateFields)
    {
        //--- insert
        $result = PR_Database::insert("test", $updateFields);
        return $result;
    }

    
    public function createTest($createdByUserID,$companyID,$testName)
    {
        //--- validate
        $errMsg = "";
        if (empty($testName)) {
            $errMsg = 'Test Name is empty';
        } 
        if (empty($createdByUserID)) {
            $errMsg = 'UserID is empty';
        } 
        if (empty($companyID)) {
            $errMsg = 'CompanyID is empty';
        } 
        
        $testID = $this->GetTestIDByTestName($testName,$companyID);
        if (!empty($testID)) {
            $errMsg = 'TestName already existed.';
        }
        
        if(!empty($errMsg)){
            $errors = PR_Api_Error::getInstance();
            $errors->addError(6, $errMsg);
            return array("success"=>0,"id"=>0,"errMsg"=>$errMsg);            
        }
        
        $updateFields=array('CompanyID'=>$companyID,
            'posteddate'=>date("Y-m-d"),
            'createdBy'=>$createdByUserID,
            'TestName'=>$testName);
        $result = $this->create($updateFields);     
        $testID = $this->GetTestIDByTestName($testName,$companyID);         
        return array("success"=>1,"id"=>$testID,"errMsg"=>''); 
    }

    public function updateTest($testID,$companyID,$testName)
    {
        //--- validate
        $errMsg = "";
        if (empty($testName)) {
            $errMsg = 'Test Name is empty';
        } 

        $otherID = $this->GetTestIDByTestName($testName,$companyID);
        if (!empty($otherID) && $otherID!=$testID) {
            $errMsg = 'TestName already existed.';
        }

        if(!empty($errMsg)){
            $errors = PR_Api_Error::getInstance();
            $errors->addError(6, $errMsg);
            return array("success"=>0,"errMsg"=>$errMsg);            
        }
//test
//TestID,CompanyID,posteddate,createdBy,TestName
        
        $updateFields=array('TestName'=>$testName);
        $criteria = "TestID = '$testID'";
        $result = PR_Database::update("test", $updateFields,$criteria);
        
        $result = $this->create($updateFields);     
        $testID = $this->GetTestIDByTestName($testName,$companyID);         
        return array("success"=>1,"errMsg"=>''); 
    }
    
    public function getTestInfo($testID)
    {
//test
//TestID,CompanyID,posteddate,createdBy,TestName        
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test', array('TestID','CompanyID','posteddate','createdBy','TestName'));
        $select->where("TestID = '$testID'");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return array();
        } else {
            $retArray = array();
            foreach($records[0] as $key=>$val){
                $retArray[$key] = $val;
            }    
            $retArray['Questions'] = $this->getQuestionsOfTest($testID);        
            return $retArray;
        }
    }

    public function getTestQuestionInfo($testQuestionID)
    {
//test_question
//TestQuestionID,Test_TestID,Question,Point
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test_question', array('TestQuestionID','Test_TestID','Question','Point'));
        $select->where("TestQuestionID = '$testQuestionID'");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return array();
        } else {
            $retArray = array();
            foreach($records[0] as $key=>$val){
                $retArray[$key] = $val;
            }    
            $retArray['QuestionAnswers'] = $this->getTestQuestionAnswersOfTestQuestion($testQuestionID);        
            return $retArray;
        }
    }
    
    public function getQuestionsOfTest($testID)
    {
//test_question
//TestQuestionID,Test_TestID,Question,Point
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test_question', array('TestQuestionID','Test_TestID','Question','Point'));
        $select->where("Test_TestID = '$testID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }

    public function getTestQuestionAnswersOfTestQuestion($testQuestionID)
    {
//        test_question_answer
//TestQuestionAnswerID,TestQuestion_TestQuestionID,AnswerText
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test_question_answer', array('TestQuestionAnswerID','TestQuestion_TestQuestionID','AnswerText'));
        $select->where("TestQuestion_TestQuestionID = '$testQuestionID'");
        $records = PR_Database::fetchAll($select);
        return $records;
    }

    public function createQuestion($testID,$quesName,$answerOptionArray)
    {
        if(empty($testID) || empty($quesName)){
            return;
        }
        if(empty($answerOptionArray)){
           $answerOptionArray=array(''); 
        }
        $testQuestionID = $this->GetQuestionIDByQuestionName($quesName,$testID);
        if(!empty($testQuestionID)){
            return; 
        }
        //test_question
//TestQuestionID,Test_TestID,Question,Point
                
        $updateFields=array('Test_TestID'=>$testID,
            'Question'=>$quesName);
        $result = PR_Database::insert("test_question", $updateFields);
        $testQuestionID = $this->GetQuestionIDByQuestionName($quesName,$testID);
        foreach($answerOptionArray as $answerOption)
        {
            $updateFields=array('TestQuestion_TestQuestionID'=>$testQuestionID,
                'AnswerText'=>$answerOption);
            $result = PR_Database::insert("test_question_answer", $updateFields);            
        }
        if($result){
            return $testQuestionID;
        }
    }
    
   /* public function updateQuestion($testQuestionID,$quesName,$answerOtionArray)
    {
        if(empty($testQuestionID) || empty($quesName)){
            return;
        }
        //test_question
//TestQuestionID,Test_TestID,Question,Point
        $updateFields=array('Question'=>$quesName);
        $criteria = "TestQuestionID = '$testQuestionID'";    
        $result = PR_Database::update("test_question", $updateFields,$criteria);
        
//        test_question_answer
//TestQuestionAnswerID,TestQuestion_TestQuestionID,AnswerText
        if(count($answerOtionArray) > 0){
            $currentAnswerTexts = $this->getAnswerTexts($testQuestionID);            
            foreach($answerOtionArray as $answerOption)
            {
                if(!in_array($answerOption,$currentAnswerTexts)){
                    $updateFields=array('TestQuestion_TestQuestionID'=>$testQuestionID,
                        'AnswerText'=>$answerOption);
                    $result = PR_Database::insert("test_question_answer", $updateFields);                    
                }
            }  
            
            $db = PR_Database::getInstance();
            $criteria = "TestQuestion_TestQuestionID = '$testQuestionID' AND AnswerText NOT IN ('".implode("','",$answerOtionArray)."')";
            $result = $db->delete('test_question_answer', $criteria);
                      
        } else if(is_array($answerOtionArray) && count($answerOtionArray)==0){
             $db = PR_Database::getInstance();
             $criteria = "TestQuestion_TestQuestionID = '$testQuestionID'";
             $result = $db->delete('test_question_answer', $criteria);
        }
    } */
    
    public function updateQuestion($testQuestionID,$quesName,$answerOtionArray,$qstListID)
    {
        if(empty($testQuestionID) || empty($quesName)){
            return;
        }
        //test_question
        //TestQuestionID,Test_TestID,Question,Point
        $updateFields=array('Question'=>$quesName);
         $criteria = "TestQuestionID = '$testQuestionID'";
        $result = PR_Database::update("test_question", $updateFields,$criteria);
        
        //test_question_answer
//TestQuestionAnswerID,TestQuestion_TestQuestionID,AnswerText
        if(count($qstListID) >0){
            $db = PR_Database::getInstance();
            $criteria = "TestQuestion_TestQuestionID = '$testQuestionID' AND TestQuestionAnswerID NOT IN ('".implode("','",$qstListID)."')";
            $result = $db->delete('test_question_answer', $criteria);
        }

        if(count($answerOtionArray) > 0){
            foreach($answerOtionArray as $key=>$answerOption)
            {
                if(empty($answerOption['answerID']) && $answerOption['answerID']==""){
                    $updateFields=array('TestQuestion_TestQuestionID'=>$testQuestionID, 'AnswerText'=>$answerOption['answerText']);
                        $result = PR_Database::insert("test_question_answer", $updateFields);
                } else {
                    $updateFields = array('AnswerText'=>$answerOption['answerText']);
                    $criteria = "TestQuestionAnswerID = '".$answerOption["answerID"]."' AND TestQuestion_TestQuestionID = '$testQuestionID'";
                    $result = PR_Database::update('test_question_answer',$updateFields,$criteria);
                }
            }

        } else if(is_array($answerOtionArray) && count($answerOtionArray)==0){
            $db = PR_Database::getInstance();
            $criteria = "TestQuestion_TestQuestionID = '$testQuestionID'";
            $result = $db->delete('test_question_answer', $criteria);
        }
    }
    
    public function deleteQuestion($testQuestionID)
    {
         $db = PR_Database::getInstance();
         $criteria = "TestQuestion_TestQuestionID = '$testQuestionID'";
         $result = $db->delete('test_question_answer', $criteria);

        //test_question
//TestQuestionID,Test_TestID,Question,Point
         
         $criteria = "TestQuestionID = '$testQuestionID'";
         $result = $db->delete('test_question', $criteria);
        
    }
    
    public function getAnswerTexts($testQuestionID)
    {        
//        test_question_answer
//TestQuestionAnswerID,TestQuestion_TestQuestionID,AnswerText
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test_question_answer', array('AnswerText'));
        $select->where("TestQuestion_TestQuestionID = '$testQuestionID'");
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return array();
        } else {
            $retArray = array();
            foreach($records as $rec){
                $retArray[] = $rec['AnswerText'];
            }
            return $retArray;
        }
    }
    
    public function GetQuestionIDByQuestionName($quesName,$testID)
    {
        //test_question
//TestQuestionID,Test_TestID,Question,Point
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test_question', array('TestQuestionID'));
        $select->where("Question = '$quesName'");
        $select->where("Test_TestID = '$testID'");        
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return 0;
        } else {
            return $records[0]['TestQuestionID'];
        }
    }
        
    
    public function GetTestIDByTestName($testName,$companyID)
    {
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('test', array('TestID'));
        $select->where("TestName = '$testName'");
        $select->where("CompanyID = '$companyID'");        
        $records = PR_Database::fetchAll($select);
        if(empty($records) || count($records)==0){
            return 0;
        } else {
            return $records[0]['TestID'];
        }
    }
    
    public function delete($testIDArray)
    {
        if(!is_array($testIDArray) || count($testIDArray)==0)
        {
            return;        
        }
        $criteria = "TestID IN (".implode(",",$testIDArray).")";
        $db = PR_Database::getInstance();
        $select = $db->select();
        $select->from('opportunity_test', array('TestID'));
        $select->where("TestID IN (".implode(",",$testIDArray).")");

        $records = PR_Database::fetchAll($select);
        $result =0;
        if(!empty($records) && count($records)>0){
            $result =0;
        } else{
        $result = $db->delete('test', $criteria);
    }
        return  $result;
}  

}  

