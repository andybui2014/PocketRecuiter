<?php

class ApiController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
    }


    public function addOpportunityAction()
    {
        $this->_helper->layout->disableLayout();
        $BYTES =8;
        $token  = openssl_random_pseudo_bytes($BYTES);
        $request = $this->getRequest();
        $username = $request->getParam("Email","");
        $password = $request->getParam("Password","");
        //$username = $_REQUEST['Email'];
        //$password = $_REQUEST['Password'];

        $errs = array();
        $userApi = new PR_Api_User();
        $authData = array('emailaddress' => $username, 'password' =>$password);
        if ($userApi->loadAndCheckAuthentication($authData))
        {
        } else{
            $errs[] =  array("err" => "Username and password must be required.");
        }

        $postedby = $request->getParam("UserID","");

        $posteddate = "";
        $title = $request->getParam("CareerName","");
        //$companyname = $_REQUEST['companyname'];
        $OppCompanyID = $request->getParam("CompanyID","");
        $careerdescription = $request->getParam("CareerDescription","");
        $status = 1;
        $industry = $request->getParam("Industry","");
        $jobtype = $request->getParam("JobType","");
        $duration = $request->getParam("Duration","");
        $location = $request->getParam("Location","");
        $zipcode = $request->getParam("ZipCode","");
        $minimuneducation = $request->getParam("MinimunEducation","");
        $degreetitle = $request->getParam("DegreeTitle","");
        $StaffFavorite ="";
        $SkillID = array();
        $SkillID[] = $request->getParam("SkillID","");
        $requiredExperience = $request->getParam("RequiredExperience","");
        $salaryRangeF = $request->getParam("SalaryRangeF","");
        $salaryRangeT = $request->getParam("SalaryRangeT","");

        $testid = array();
        $testid[] = $request->getParam("TestID","");

        $return = "";
        if($title==""){
            $errs[] = array("err" => "Career Name cannot be empty.");
        }

        if($postedby==""){
        $errs[] = array("err" => "UserID cannot be empty.");
    }

        if($careerdescription==""){
            $errs[] = array("err" => "Career description cannot be empty.");
        }

        if($industry==""){
            $errs[] = array("err" => "Career Industry cannot be empty.");
        }

        if($minimuneducation==""){
            $errs[] = array("err" => "Minimun Education cannot be empty.");
        }

        if($degreetitle==""){
            $errs[] = array("err" => "Degree Title cannot be empty.");
        }

        if($SkillID==""){
            $errs[] = array("err" => "Required Skills cannot be empty.");
        }

        if($requiredExperience==""){
            $errs[] = array("err" => "Required Experience cannot be empty.");
        }

        if($salaryRangeF=="" || $salaryRangeT ==""){
            $errs[] = array("err" => "Salary Range cannot be empty.");
        }

        if (empty($errs))
        {
            $career_PR_Api = new PR_Api_Career(null);

            $updateFields = array('CompanyID'=>$OppCompanyID,'postedby'=>$postedby, 'posteddate'=>$posteddate,
                'title'=>$title,'careerdescription'=>$careerdescription,'status'=>$status,'industry'=>$industry,'industry'=>$industry,'jobtype'=>$jobtype,
                'duration'=>$duration,'location'=>$location,'zipcode'=>$zipcode,'minimuneducation'=>$minimuneducation,'degreetitle'=>$degreetitle,'StaffFavorite'=>$StaffFavorite,
                'salaryrangefrom'=>$salaryRangeF, 'salaryrangeto'=>$salaryRangeT,'experienced'=>$requiredExperience
            );

            /*echo "<pre>";
            print_r($updateFields);
            echo "</pre>"; die(); */
            $result = $career_PR_Api->saveCareer($updateFields);

            if($result){
                $OpportunityID = $result->OpportunityID;
                $edit_PR_Api = new PR_Api_Core_CareerClass();
                $edit_PR_Api->saveCareerSkills($OpportunityID, $SkillID);
                $edit_PR_Api->saveCareerTests($OpportunityID, $testid) ;
                $return.="<opp>
                            <oppotunityID>" . ($result->OpportunityID !=0 ? "Success" : "No") . "</oppotunityID>
                       </opp>";



                $return = "
                <result>
                    <opportunity>" . $return . "</opportunity>
                </result>";
            } else{
                $return = "<result>no result</result>";
            }
        } else {
            $return = "
                    <result>
            <errors>";
            $i = 0;
            foreach ($errs as $err)
            {
                $return .="<error>" . $err['err'] . "</error>";
            }

            $return .="</errors></result>";
        }

        header('Content-type: application/xml');
        header("Content-Length: " . strlen($return));
        print_r($return);

    }

    public function addOpportunityByGuiAction()
    {
        $this->_helper->layout->disableLayout();
        $BYTES =8;
        $token  = openssl_random_pseudo_bytes($BYTES);

        $username = $_POST['Email'];
        $password = $_POST['Password'];
        $errs = array();
        $userApi = new PR_Api_User();
        $authData = array('emailaddress' => $username, 'password' =>$password);
        if ($userApi->loadAndCheckAuthentication($authData))
        {
        } else{
            $errs[] =  array("err" => "Username and password must be required.");
        }

        $postedby = $_POST['UserID'];

        $posteddate = "";
        $title = $_POST['CareerName'];

        //$companyname = $_REQUEST['companyname'];

        $OppCompanyID = $_POST['CompanyID'];
        $careerdescription = $_POST['CareerDescription'];
        $status = $_POST['Status'];
        $industry = $_POST['Industry'];
        $jobtype =$_POST['JobType'];
        $duration =$_POST['Duration'];
        $location =$_POST['Location'];
        $zipcode =$_POST['ZipCode'];
        $minimuneducation =$_POST['MinimunEducation'];
        $degreetitle =$_POST['DegreeTitle'];
        $StaffFavorite ="";
        $SkillID = array();
        $SkillID[] =$_POST['SkillID'];  //array

        $requiredExperience =$_POST['RequiredExperience'];
        $salaryRangeF =$_POST['SalaryRangeF'];
        $salaryRangeT =$_POST['SalaryRangeT'];
        $testid = array();
        $testid[] =$_POST['TestID'];  //array

        $return = "";
        if($title==""){
            $errs[] = array("err" => "Career Name cannot be empty.");
        }

        if($postedby==""){
            $errs[] = array("err" => "UserID cannot be empty.");
        }

        if($careerdescription==""){
            $errs[] = array("err" => "Career description cannot be empty.");
        }

        if($industry==""){
            $errs[] = array("err" => "Career Industry cannot be empty.");
        }

        if($minimuneducation==""){
            $errs[] = array("err" => "Minimun Education cannot be empty.");
        }

        if($degreetitle==""){
            $errs[] = array("err" => "Degree Title cannot be empty.");
        }

        if($SkillID==""){
            $errs[] = array("err" => "Required Skills cannot be empty.");
        }

        if($requiredExperience==""){
            $errs[] = array("err" => "Required Experience cannot be empty.");
        }

        if($salaryRangeF=="" || $salaryRangeT ==""){
            $errs[] = array("err" => "Salary Range cannot be empty.");
        }

        if (empty($errs))
        {
            $career_PR_Api = new PR_Api_Career(null);

            $updateFields = array('CompanyID'=>$OppCompanyID,'postedby'=>$postedby, 'posteddate'=>$posteddate,
                'title'=>$title,'careerdescription'=>$careerdescription,'status'=>$status,'industry'=>$industry,'industry'=>$industry,'jobtype'=>$jobtype,
                'duration'=>$duration,'location'=>$location,'zipcode'=>$zipcode,'minimuneducation'=>$minimuneducation,'degreetitle'=>$degreetitle,'StaffFavorite'=>$StaffFavorite,
                'salaryrangefrom'=>$salaryRangeF, 'salaryrangeto'=>$salaryRangeT,'experienced'=>$requiredExperience
            );

            $result = $career_PR_Api->saveCareer($updateFields);

            if($result){
                $OpportunityID = $result->OpportunityID;
                $edit_PR_Api = new PR_Api_Core_CareerClass();
                $edit_PR_Api->saveCareerSkills($OpportunityID, $SkillID);
                $edit_PR_Api->saveCareerTests($OpportunityID, $testid) ;
                $return.="<opp>
                            <oppotunityID>" . ($result->OpportunityID !=0 ? "Success" : "No") . "</oppotunityID>
                       </opp>";



                $return = "
                <result>
                    <opportunity>" . $return . "</opportunity>
                </result>";
            } else{
                $return = "<result>no result</result>";
            }
        } else {
            $return = "
                    <result>
            <errors>";
            $i = 0;
            foreach ($errs as $err)
            {
                $return .="<error>" . $err['err'] . "</error>";
            }

            $return .="</errors></result>";
        }
        header('Content-type: application/xml');
        header("Content-Length: " . strlen($return));
        print_r($return);


    }

    public function testoppAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);

    }
}
