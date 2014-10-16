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
        $username = $_REQUEST['email'];
        $password = $_REQUEST['password'];

        $errs = array();
        $userApi = new PR_Api_User();
        $authData = array('emailaddress' => $username, 'password' =>$password);
        if ($userApi->loadAndCheckAuthenticationForAPI($authData))
        {
        } else{
            $errs[] =  array("err" => "Username and password must be required.");
        }

        $postedby = $_REQUEST['UserID'];
        $posteddate = "";
        $title = $_REQUEST['careername'];

        $companyname = $_REQUEST['companyname'];

        $OppCompanyID = $_REQUEST['CompanyID'];
        $careerdescription = $_REQUEST['careerdescription'];
        $status = $_REQUEST['status'];
        $industry = $_REQUEST['industry'];
        $jobtype =$_REQUEST['jobtype'];
        $duration =$_REQUEST['duration'];
        $location =$_REQUEST['location'];
        $zipcode =$_REQUEST['zipcode'];
        $minimuneducation =$_REQUEST['minimuneducation'];
        $degreetitle =$_REQUEST['degreetitle'];
        $StaffFavorite ="";
        $SkillID = array();
        $SkillID[] =$_REQUEST['SkillID'];  //array
        /*echo "<pre>";
        print_r($SkillID);
        echo "</pre>"; die(); */
        $requiredExperience =$_REQUEST['requiredExperience'];
        $salaryRangeF =$_REQUEST['salaryRangeF'];
        $salaryRangeT =$_REQUEST['salaryRangeT'];
        $testid = array();
        $testid[] =$_REQUEST['testid'];  //array


        $return = "";
        if($title==""){
            $errs[] = array("err" => "Career Name cannot be empty.");
        }

        if($companyname==""){
            $errs[] = array("err" => "Company Name cannot be empty.");
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

       /* $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/xml');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);*/

    }
    public function testoppAction()
    {
        $request = $this->getRequest();
        $sestionClient = PR_Session::getSession(PR_Session::SESSION_USER);

    }
}
