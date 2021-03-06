<?php

class CandidateController extends Application_Controller_Action
{
    public function init()
    {
        parent::init();
       // $client = PR_Session::getSession(PR_Session::SESSION_CLIENT);
        $candidate = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("client");print_r($client);
        if(empty($candidate))
        {
            $this->_helper->redirector("index","login");
        }  
    }

    public function indexAction(){

    }

    public function doLogoutAction()
    {
        $this->_helper->layout->disableLayout();

        PR_Session::clearSessions();
        
        $this->_helper->redirector("index","login");
    }

    public function detailEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = isset($params['id']) ? $params['id'] : null;
            $core = new PR_Api_Core_CandidateClass();
            $ajaxRes['info'] = $core->getCandidateEmployment($id);
           // $this->view->list = $ajaxRes['info'];
           // $jobfunctions=$core->get_jobfuntion($id);
            // $this->view->jobfunctions = $jobfunctions;
           // $this->view->id=$id;
             //print_r($params);
            //Convert m/d/Y StartDate
            $startDate = $ajaxRes['info']['StartDate'];
            if (($timestamp = strtotime($startDate)) !== false)
                $startDate = date("m/d/Y", $timestamp);
            $ajaxRes['info']['StartDate'] = $startDate;

            //Convert m/d/Y EndDate
            $endDate = $ajaxRes['info']['EndDate'];
            if (($timestamp = strtotime($endDate)) !== false)
                $endDate = date("m/d/Y", $timestamp);
            $ajaxRes['info']['EndDate'] = $endDate;

            if(sizeof($ajaxRes['info']) > 0) $ajaxRes['success'] = 1;

        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
   
    public function contactInfoAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $core = new PR_Api_Core_CandidateClass();
        $info = $core->getContactInfo($client['UserID']);
        $this->view->info = $info;
        
        $getUserArray=$core->getCandidateInfo($client['UserID']);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$core->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
        $this->render('contact-info');
    }
    public function updateContactInfoAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $errors = array();
                foreach($params['data'] as $item){
                    if($item['name']=='firstname'){
                        if(empty($item['value'])){
                            $errors['firstname'] = 1;
                        }else{
                            $data['firstname'] = $item['value'];
                        }
                    }
                    if($item['name']=='lastname'){
                        if(empty($item['value'])){
                            $errors['lastname'] = 1;
                        }else{
                            $data['lastname'] = $item['value'];
                        }
                    }
                    if($item['name']=='email'){
                        if(empty($item['value'])){
                            $errors['email'] = 1;

                        }else{
                            if (!filter_var($item['value'], FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 1;
                            }else{
                                $data['emailaddress'] = $item['value'];
                            }
                        }
                    }
                    if($item['name']=='country'){
                        if(empty($item['value'])){
                            $errors['country'] = 1;
                        }else{
                            $data['Country']  = $item['value'];
                        }
                    }
                    if($item['name']=='addResLine'){
                        if(empty($item['value'])){
                            $errors['addResLine'] = 1;
                        }else{
                            $data['Address1']  = $item['value'];
                        }
                    }
                    if($item['name']=='addResLine2'){
                        if(empty($item['value'])){
                            $errors['addResLine2'] = 1;
                        }else{
                            $data['Address2']  = $item['value'];
                        }
                    }
                    if($item['name']=='city'){
                        if(empty($item['value'])){
                            $errors['city'] = 1;
                        }else{
                            $data['City']  = $item['value'];
                        }
                    }
                    if($item['name']=='stateProvince'){
                        if(empty($item['value'])){
                            $errors['stateProvince'] = 1;
                        }else{
                            $data['State']  = $item['value'];
                        }
                    }
                    if($item['name']=='zipcode'){
                        if(empty($item['value'])){
                            $errors['zipcode'] = 1;
                        }else{
                            $data['PostalCode']  = $item['value'];
                        }
                    }

                    if($item['name']=='phone')          $data['PhoneNumber']  = $item['value'];
                    if($item['name']=='url')            $data['URL']  = $item['value'];
                    //if($item['name']=='city')           $data['City']  = $item['value'];
                    //if($item['name']=='country')        $data['Country']  = $item['value'];
                    //if($item['name']=='zipcode')        $data['PostalCode']  = $item['value'];
                    if($item['name']=='fax')            $data['faxnumber']  = $item['value'];
                    //if($item['name']=='addResLine')     $data['Address1']  = $item['value'];
                    //if($item['name']=='addResLine2')    $data['Address2']  = $item['value'];
                    if($item['name']=='snu')            $data['URlnetwork']  = $item['value'];
                    if($item['name']=='insMsg')         $data['instanmessaing']  = $item['value'];

                }

                if(empty($errors)){
                    $core = new PR_Api_Core_CandidateClass();
                    $core->saveContactInfo($client['UserID'],$data);
                    $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = $errors;
                }

            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    private function skillChilds($ParentSkillID, $level) {
        $source = '';
        if(!empty($ParentSkillID)){
            $core = new PR_Api_Core_CandidateClass();
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
            $Child = $core->getList_CandidateSkillsChildren($ParentSkillID,$client['UserID']);

            if(!empty($Child)){
                $source = '<ul>';
                foreach($Child as $item){
                    if ($item['Level'] > 0) {
                        $checked = $item['Checked'] ? 'select':'deselect';
                        $st = $item['Checked'] ? 'ico_colapse_sm.png':'ico_expand_sm.png';
                        $src = URL_THEMES.'images/trees/'.$st;
                        $toggle = URL_THEMES .'images/trees/ico_sub_sm.png';
                        $sub = $this->skillChilds($item['SkillID'], $level + 1);
                        if(!empty($sub)){
                            $source .= "<li>";
                            $source .= "<img data-id='".$item['SkillID']."' data-status='".$checked."' class='img-item' src='".$src."'>
                                   <a href='#'> ".$item['SkillName']."</a><span><img class='img-toggle' src='".$toggle."'/></span>
                                   ";
                            $source .=  $sub;
                            $source .=  "</li>";
                        }else{
                            $source .= "<li>";
                            $source .= "<span><img data-id='".$item['SkillID']."' data-status='".$checked."' class='img-item' src='".$src."'></span>
                                   <a href='#'> ".$item['SkillName']."</a>
                                   ";
                            $source .=  $sub;
                            $source .=  "</li>";
                        }

                    }else{
                        //$source .= "<li><a href='#'>".$item['SkillName']."</a></li>";

                    }
                }
                $source .= "</ul>";
            }
        }
        return $source;
    }

    private function attribute_child($candiate_id, $ID, $disable) {
        $source1 = "";
        if(!empty($ID)){
            $core = new PR_Api_Core_CandidateClass();
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
            $Child = $core->get_attribute_child($candiate_id,$ID);
            if(!empty($Child)){

                foreach($Child as $item){
                    $sub = $this->attribute_child($candiate_id, $item['ID'],$disable);
                    $sel1 ="<div class='col-md-2'></div>";
                    $Track1 ='';
                    if(!empty($item['TemplateID'])){
                        $option = "";

                        foreach($item['TemplateID'] as $itemInfo){
                            if($itemInfo['Value'] == $item['Value']){
                                $selected11 = 'selected="selected"';
                            } else{
                                $selected11 ='';
                            }
                            $option .= "<option value='".$itemInfo['Value']."' ".$selected11.">".$itemInfo['Description']."</option>";
                        }
                        $sel1 = " <select class='form-control attr-value'  style='color:#5d629c' ".$disable."> ".$option."</select>";
                    }

                    if($item['TrackYearsOfExperience'] && $item['TrackLevelofInterest']){
                        $selected = '';
                        $option_exp ='';

                        for($i=1; $i<6; $i++){
                            if($i==$item['LevelofInterest']){
                                $selected = 'selected="selected"';
                            } else{
                                $selected ='';
                            }
                            $option_exp .= "<option value='".$i."' ".$selected.">".$i."</option>";
                        }

                        $Track1 = "<div class='col-md-2' style='padding-right:0; color:#706d67;line-height:34px'>Years of Experience </div>
                                                                  <div class='col-md-1' style='padding:0'><input type='text' style='color:#706d67' ".$disable." class='form-control attr-YoE' value='".$item['YearsofExperience']."'></div>
                                                                  <div class='col-md-2' style='text-align:right; color:#706d67;line-height:34px'>Level of Interest</div>
                                                                  <div class='col-md-1' style='padding:0'>
                                                                    <select class='form-control attr-LevelofInterest' ".$disable."  style='color:#706d67'>
                                                                            '.$option_exp.'
                                                                    </select>
                                                                  </div>";
                   }

                    $source1 .="<ul style='margin-top:10px' class='attr-attr'>
                                                            <li>
                                                                <div class='col-md-12' style='padding:0'>
                                                                     <div style='line-height:34px;padding:0;margin-bottom:10px' attr-id=".$item['ID']." class='col-md-4 attrid'>".$item['Attribute']."</div>
                                                                    <div class='col-md-2' style='padding:0; margin-bottom:10px'>
                                                                        ".$sel1."
                                                                    </div>
                                                                    ".$Track1."
                                                                </div>
                                                            </li>

                                                        </ul>";
                    $source1 .=  $sub;
                }

            }
        }
        return $source1;
    }

    public function doUpdateSkillsAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            /*
            if(!empty($params['data']) && sizeof($params['data']) > 0){
                $core = new PR_Api_Core_CandidateClass();
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                if($core->updateCandidateSkill($client['CandidateProfileID'],$params['data'])){
                    $ajaxRes['success'] = 1;
                }
            } */

            if(isset($params['data'])){
                $attributeIDs = $params['data'];
            } else{
                $attributeIDs = array();
            }

           /* echo "<pre>";
            echo "test = "; print_r($attributeIDs);
            echo "</pre>"; die(); */
                $core = new PR_Api_Core_CandidateClass();
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                if($core->updateCandidateAttribute($client['CandidateProfileID'],$attributeIDs)){
                    $ajaxRes['success'] = 1;
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);

    }
    public function skillsAction(){
        $params = $this->getRequest()->getParams();
        if(!empty($params) && isset($params['utm_source'])){
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
			//
            $UserID=$client["UserID"];                  
            $api_candidate= new PR_Api_Core_CandidateClass();                
            $getUserArray=$api_candidate->getCandidateInfo($UserID);
            $this->view->UserArray = $getUserArray;  
            $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
            $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
            $this->view->getCandidates=$getCandidates;
            //
            $this->view->step = $params['utm_source'];
            switch($params['utm_source']){
                case 'index':
                    $tree = '';
                    $core = new PR_Api_Core_CandidateClass();
                    $skills = $core->getListAll_CandidateSkills($client['UserID']);


                    if(!empty($skills)){
                        foreach($skills as $item){
                            $select = (!empty($item['CandidateProfileID']) && !empty($item['UserID'])) ? 'select':'deselect';
                            $src = (!empty($item['CandidateProfileID']) && !empty($item['UserID'])) ?  URL_THEMES.'images/trees/ico_colapse.png' : URL_THEMES.'images/trees/ico_expand.png';
                            $toggle = URL_THEMES .'images/trees/ico_sub_sm.png';
                            //Tree View
							$id=$item['SkillID'];  
                            $tree .= "<div class='col-md-4' style='margin:0;padding:0'><div class='tree'><ul>";
                            $tree .= "<li>
                                        <img data-id='".$item['SkillID']."' data-status='".$select."' class='img-parent' src='".$src."'/>
                                        <a href='#'><strong>" . $item['SkillName'] . "</strong></a>
                                        <span><img class='img-toggle' src='".$toggle."'/></span>";
                            $tree .= $this->skillChilds($item['SkillID'], $item['Level']+1);
                            $tree .= "</li></ul></div></div>";
                        }
                    }
                    $this->view->tree = $tree;
                    $this->render('skills/index');
                    break;
                default:
            $this->render('skills/index');
                    break;
            }
        }else{
            $this->render('skills/index');
        }
    }
    public function profileBuilderAction(){
        $params = $this->getRequest()->getParams();
        if(!empty($params) && isset($params['utm_source'])){
            $client = PR_Session::getSession(PR_Session::SESSION_USER);
            $core = new PR_Api_Core_CandidateClass();
            $getUserArray=$core->getCandidateInfo($client['UserID']);
            $this->view->client = $getUserArray;  
            $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
            $getCandidates=$core->getCandidateProfile($Candidateprofile_ID);
            $this->view->getCandidates=$getCandidates;
            $this->view->step = $params['utm_source'];
            switch($params['utm_source']){
                case 'contact':
                    $core = new PR_Api_Core_CandidateClass();
                   
                    $info = $core->getContactInfo($client['UserID']);
                    $this->view->stepCount = '1/5 Steps';
                    $this->view->info = $info;
                    $this->render('profile-builder/contact');
                    break;
                case 'education':
                    $core = new PR_Api_Core_CandidateClass();
                   
                    $list = $core->getCandidateEducationList($client['UserID']);
                    $this->view->list = $list;
                    $this->view->stepCount = '2/5 Steps';
                    $this->render('profile-builder/education');
                    break;
                case 'employment':
                    $core = new PR_Api_Core_CandidateClass();
                    //$core->addjobfunction("9","JobFucntion","0.9");
                    $list = $core->getCandidateEmployments($client['UserID']);
                    $this->view->list = $list;
                    if(isset($params["id"])||!empty($params["id"])){
                    $id=$params["id"];
                    $jobfunctions=$core->get_jobfuntion($id);
                    $employment=$core->getCandidateEmployment($id);
                    $this->view->jobfunctions=$jobfunctions;
                    $this->view->employment=$employment;
                    $totalPercentage=$core->totalPercentage($id);
                    $totalprcent=round($totalPercentage["totalPercentage"]*100,2);
                    
                    $this->view->totalPercentage=$totalprcent;
                    }
					
					//$core->deleteJobFunction(5,2);
					$getjob=$core->getjobfunctions();
					$this->getjob=$getjob;
                    $this->view->stepCount = '3/5 Steps';
                    $this->render('profile-builder/employment');
					//echo "tetst:<pre>";print_r($employment);echo("</pre>");
                    break;
                case 'skills':


                    $tree = '';
                    $core = new PR_Api_Core_CandidateClass();
                    // -----------------Attribute-----------------------------

                    $html ='';
                    $attr_p0_list = $core->get_attribute_p0($Candidateprofile_ID);
                    if(!empty($attr_p0_list)){

                        foreach($attr_p0_list as $attr_p0Info){
                            //$select = (!empty($attr_p0Info['Candidate_ProfileID']))? 'select':'deselect';
                            if(!empty($attr_p0Info['Candidate_ProfileID'])){
                                $select = 'select';
                                $disable = '';
                            } else {
                                $select = 'deselect';
                                $disable = 'disabled';
                            }
                            $src = (!empty($attr_p0Info['Candidate_ProfileID'])) ?  URL_THEMES.'images/trees/ico_colapse.png' : URL_THEMES.'images/trees/ico_expand.png';

                            $toggle = URL_THEMES .'images/trees/ico_sub_sm.png';

                                $html .= "<div class='col-md-12' style='margin:0;padding:0; color: #5d629c'><div class='tree'><ul>";

                                $html .= "<li>
                                            <img data-id='".$attr_p0Info['ID']."' data-status='".$select."' class='img-parent-attr' src='".$src."'/>
                                            <a href='#' style='color:#5d629c'><strong>" . $attr_p0Info['AttributeCategory'] . "</strong></a>
                                        <span><img class='img-toggle' src='".$toggle."'/></span>";

                                           $AttributeID_Parents = $core->get_attribute_parent($Candidateprofile_ID, $attr_p0Info['ParentAttributeID'], $attr_p0Info['AtttributeCategoryID']);
                                            //$AttributeID_Parents = $core->get_attribute_parent($Candidateprofile_ID, 0, 5);
                                            if(!empty($AttributeID_Parents)){
                                                foreach ($AttributeID_Parents as $attrInfo){
                                                    $sel ="<div class='col-md-2'></div>";

                                                    if(!empty($attrInfo['TemplateID'])){
                                                        $option = "";
                                                        foreach($attrInfo['TemplateID'] as $TemplateIDInfo){
                                                            if($TemplateIDInfo['Value'] == $attrInfo['Value']){
                                                                $selected1 = 'selected="selected"';
                                                            } else{
                                                                $selected1 ='';
                                                            }
                                                            $option .= "<option value='".$TemplateIDInfo['Value']."' ".$selected1." >".$TemplateIDInfo['Description']."</option>";
                                                        }
                                                        $sel = " <select class='form-control attr-value'  style='color:#5d629c' ".$disable."> ".$option."</select>";
                                                    }

                                                    $Track ='';
                                                    if($attrInfo['TrackYearsOfExperience'] && $attrInfo['TrackLevelofInterest']){
                                                        $selected = '';
                                                        $option_exp ='';

                                                        for($i=1; $i<6; $i++){
                                                            if($i==$attrInfo['LevelofInterest']){
                                                                $selected = 'selected="selected"';
                                                            } else{
                                                                $selected ='';
                                                            }
                                                            $option_exp .= "<option value='".$i."' ".$selected.">".$i."</option>";
                                                        }

                                                        $Track = "<div class='col-md-2' style='padding-right:0; color:#706d67;line-height:34px'>Years of Experience </div>
                                                                  <div class='col-md-1' style='padding:0'><input type='text' style='color:#706d67' ".$disable." class='form-control attr-YoE' value='".$attrInfo['YearsofExperience']."'></div>
                                                                  <div class='col-md-2' style='text-align:right;color:#706d67;line-height:34px'>Level of Interest</div>
                                                                  <div class='col-md-1' style='padding:0'>
                                                                    <select class='form-control attr-LevelofInterest' style='color:#706d67' ".$disable.">
                                                                            '.$option_exp.'
                                                                    </select>
                                                                  </div>";
                                                    }



                                                    $html .="<ul style='margin-top:10px' class='attr-attr'>
                                                            <li>
                                                                <div class='col-md-12' style='padding:0'>
                                                                    <div style='line-height:34px;padding:0;margin-bottom:10px' attr-id=".$attrInfo['ID']." class='col-md-4 attrid'>".$attrInfo['Attribute']."</div>
                                                                    <div class='col-md-2' style='padding:0; margin-bottom:10px'>
                                                                        ".$sel."
                                                                    </div>
                                                                    ".$Track."
                                                               </div>

                                                            </li>

                                                        </ul>";

                                                   $html .= $this->attribute_child($Candidateprofile_ID,$attrInfo['ID'],$disable);

                                                }
                                            }

                                $html .= "</li></ul></div></div>";
                        }

                    }
                    $this->view->html = $html;
                    /*echo "<pre>";
                    print_r($skills);
                    echo "</pre>"; die(); */
                   //-------------------- end attribute---------------------//
                    /*$skills = $core->getListAll_CandidateSkills($client['UserID']);
                    if(!empty($skills)){
                        foreach($skills as $item){
                            $select = (!empty($item['CandidateProfileID']) && !empty($item['UserID'])) ? 'select':'deselect';
                            $src = (!empty($item['CandidateProfileID']) && !empty($item['UserID'])) ?  URL_THEMES.'images/trees/ico_colapse.png' : URL_THEMES.'images/trees/ico_expand.png';
                            $toggle = URL_THEMES .'images/trees/ico_sub_sm.png';
                            //Tree View
                            $tree .= "<div class='col-md-4' style='margin:0;padding:0'><div class='tree'><ul>";
                            $tree .= "<li>
                                        <img data-id='".$item['SkillID']."' data-status='".$select."' class='img-parent' src='".$src."'/>
                                        <a href='#'><strong>" . $item['SkillName'] . "</strong></a>
                                        <span><img class='img-toggle' src='".$toggle."'/></span>";
                            $tree .= $this->skillChilds($item['SkillID'], $item['Level']+1);
                            $tree .= "</li></ul></div></div>";
                        }
                    }
                    $this->view->tree = $tree; */
                    $this->view->stepCount = '4/5 Steps';
                    $this->render('profile-builder/skills');
                    break;
                case 'portfolio':
                    $core = new PR_Api_Core_CandidateClass();
                    
                    $list = $core->getListCandidatePortfolio($client['UserID']);
                    $this->view->list = $list;
                    $this->view->stepCount = '5/5 Steps';
                    $this->render('profile-builder/portfolio');
                    break;
                case 'success':
                    $this->view->stepCount = 'Completed';
                    $this->render('profile-builder/success');
                    break;
                default:
                    $this->render('profile-builder/index');
                    break;
            }
        }else{
            $this->render('profile-builder/index');
        }
    }

    public function startProfileAction(){

    }
    public function doUpdateEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            if(!empty($params['data']) && sizeof($params['data'])){
                //$client = PR_Session::getSession(PR_Session::SESSION_USER);

                $instName = null;
                $degreeName = null;
                $startYear = null;
                $endYear = null;
                $eduId = null;
                foreach($params['data'] as $item){

                    if($item['name']=='eduId')          $eduId = $item['value'];
                    if($item['name']=='inst-name')      $instName = $item['value'];
                    if($item['name']=='degree-name')    $degreeName = $item['value'];
                    if($item['name']=='start-year')     $startYear = $item['value'];
                    if($item['name']=='end-year')       $endYear = $item['value'];
                }
                if(empty($eduId)) $errors['eduId'] = 1;
                if(empty($instName)) $errors['inst-name'] = 1;
                if(empty($degreeName)) $errors['degree-name'] = 1;
                if(empty($startYear)) $errors['start-year'] = 1;
                if(empty($endYear)) $errors['end-year'] = 1;

                if(empty($errors)){
                    $core = new PR_Api_Core_CandidateClass();
                    $core->updateCandidateEducation($eduId,$instName,$degreeName,$startYear,$endYear);
                    $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }
            }
            $response = $this->getResponse();
            $response->clearAllHeaders()->clearBody();
            $ajaxRes = json_encode($ajaxRes);
            $response->setHeader('Content-type', 'application/json');
            $response->setHeader('Content-Length', strlen($ajaxRes), true)
                ->setBody($ajaxRes);
        }
    }
    public function detailEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = !empty($params['id']) ? $params['id'] : 0;
            if($id > 0){
                //$client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $res = $core->getCandidateEducation($id);
                $startdate = $res['startdate'];
                $enddate = $res['enddate'];
                if (($timestamp = strtotime($startdate)) !== false)
                    $res['startdate'] = date("Y", $timestamp);
                if (($timestamp = strtotime($enddate)) !== false)
                    $res['enddate'] = date("Y", $timestamp);

               $ajaxRes['info'] = $res;
               $ajaxRes['success']  = 1;
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function stepNextContactAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $data = array();
                $errors = array();
                foreach($params['data'] as $item){
                    if($item['name']=='firstname'){
                        if(empty($item['value'])){
                            $errors['firstname'] = 1;
                        }else{
                            $data['firstname'] = $item['value'];
                        }
                    }
                    if($item['name']=='lastname'){
                        if(empty($item['value'])){
                            $errors['lastname'] = 1;
                        }else{
                            $data['lastname'] = $item['value'];
                        }
                    }
                    if($item['name']=='email'){
                        if(empty($item['value'])){
                            $errors['email'] = 1;

                        }else{
                            if (!filter_var($item['value'], FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 1;
                            }else{
                                $data['emailaddress'] = $item['value'];
                            }
                        }
                    }

                    if($item['name']=='phone')          $data['PhoneNumber']  = $item['value'];
                    if($item['name']=='url')            $data['URL']  = $item['value'];
                    if($item['name']=='city')           $data['City']  = $item['value'];
                    if($item['name']=='country')        $data['Country']  = $item['value'];
                    if($item['name']=='zipcode')        $data['PostalCode']  = $item['value'];

                }

                if(empty($errors)){

                    $core = new PR_Api_Core_CandidateClass();
                    $core->saveContactInfo($client['UserID'],$data);
                    $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = $errors;
                }

            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function doDeletePortfolioAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $core = new PR_Api_Core_CandidateClass();
            if(!empty($params['data']) && sizeof($params['data']) > 0){
                foreach($params['data'] as $key=>$value){
                    $core->deleteCandidatePortfolio($value);
                }
            }
        }
        $ajaxRes['success'] = 1;
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function doAddPortfolioAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            $title = null;
            $url = null;
            $description = null;

            $client = PR_Session::getSession(PR_Session::SESSION_USER);

            foreach($params['data'] as $item){
                if($item['name']=='title')         $title = $item['value'];
                if($item['name']=='url')           $url = trim($item['value']);
                if($item['name']=='description')   $description = $item['value'];
            }
            if(empty($title))       $errors['title'] = 1;
            if(empty($url))         $errors['url'] = 1;
            //if(empty($description)) $errors['description'] = 1;

            if(empty($errors)){
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidatePortfolio($client['UserID'],$title,$url,$description,null);
                if($isSuccess) $ajaxRes['success'] = 1;
            }else{
                $ajaxRes['info'] = $errors;
            }


        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doAddEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && sizeof($params['data'])){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $errors = array();
                $instName = null;
                $degreeName = null;
                $startYear = null;
                $endYear = null;

                foreach($params['data'] as $item){
                    if($item['name']=='inst-name')      $instName = $item['value'];
                    if($item['name']=='degree-name')    $degreeName = $item['value'];
                    if($item['name']=='start-year')     $startYear = $item['value'];
                    if($item['name']=='end-year')       $endYear = $item['value'];
                }

                if(empty($instName)) $errors['inst-name'] = 1;
                if(empty($degreeName)) $errors['degree-name'] = 1;
                if(empty($startYear)) $errors['start-year'] = 1;
                if(empty($endYear)) $errors['end-year'] = 1;

                if(empty($errors)){
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->addCandidateEducation($client['UserID'],$instName,$degreeName,$startYear,$endYear);
                    if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
        }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doRemoveEducationAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = $params['id'];
            $core = new PR_Api_Core_CandidateClass();
            $core->deleteCandidateEducation($id);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doRemoveEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = $params['id'];
            $core = new PR_Api_Core_CandidateClass();
            $core->deleteCandidateEmployment($id);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }

    public function doUpdateEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $empId = null;
            $companyName = null;
            $positionHeld = null;
            $startDate = null;
            $endDate = null;
            $description = null;
            $errors = array();

            if(!empty($params['data']) && sizeof($params['data'])){
                $Text=array("JobFucntion1"=>"","Percentage1"=>"");
                foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')          $empId = $item['value'];
                    if($item['name']=='companyName')    $companyName  = $item['value'];
                    if($item['name']=='posotionHeld')   $positionHeld = $item['value'];
                    if($item['name']=='startDate')      $startDate = $item['value'];
                    if($item['name']=='endDate')        $endDate = $item['value'];
                    if($item['name']=='description')    $description = $item['value'];
                    if($item['name']=='JobID')          $jobid = $item['value'];
                    if($item['name']=='JobFunctionID')  $JobFunctionID = $item['value'];
                    if($item['name']=='JobFucntion')    $JobFucntion  = $item['value'];
                    if($item['name']=='Percentage')     $Percentage = $item['value'];
                    
                    if($item['name']=='JobFucntion1[]')
                    {
                       $Text["JobFucntion1"][]=$item['value'];
                    }
                    if($item['name']=='Percentage1[]')
                    {
                       $Text["Percentage1"][]=$Percentage=round($item['value']/100,2);
                    }
                }
                if(empty($empId)) $errors['empId'] = 1;
                if(empty($companyName)) $errors['companyName'] = 1;
                if(empty($positionHeld)) $errors['posotionHeld'] = 1;
                if(empty($startDate)) $errors['startDate'] = 1;
                //if(empty($endDate)) $errors['endDate'] = 1;
                //if(empty($description)) $errors['description'] = 1;
               // die();
                if(empty($errors)){
                    //$client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->updateCandidateEmployment($empId,$companyName,$positionHeld,$startDate,$endDate,$description);
                $Percentage=round($Percentage/100,2);
               
                if(isset($jobid) && !empty($jobid)){
               // $core->updateJobFunction($JobFucntion,$empId,$Percentage,$jobid);
                $core->deleteJobFunction($jobid);
                }
                
               // else{
              //      $core->addjobfunction($empId,$Text);
                    
              //  }
                if(!empty($Text["JobFucntion1"])&&!empty($Text["Percentage1"]))
                {
                    $core->addjobfunction($empId,$Text);
                }
                //if($isSuccess)
				$ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doAddEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            $companyName = null;
            $posotionHeld = null;
            $startDate = null;
            $endDate = null;
            $description = null;
            $errors = array();
            if(!empty($params['data']) && sizeof($params['data'])){
               
                $Text=array("JobFucntion1"=>"","Percentage1"=>"");
                foreach($params['data'] as $key=>$item){
                    if($item['name']=='companyName')    $companyName  = $item['value'];
                    if($item['name']=='posotionHeld')   $posotionHeld = $item['value'];
                    if($item['name']=='startDate')      $startDate = $item['value'];
                    if($item['name']=='endDate')        $endDate = $item['value'];
                    if($item['name']=='description')    $description = $item['value'];
                    if($item['name']=='JobID')          $jobid = $item['value'];
                    if($item['name']=='JobFunctionID')  $JobFunctionID = $item['value'];
                    if($item['name']=='JobFucntion')    $JobFucntion  = $item['value'];
                    if($item['name']=='Percentage')     $Percentage = $item['value'];
                    
                    if($item['name']=='JobFucntion1[]')
                    {
                       $Text["JobFucntion1"][]=$item['value'];
                    }
                    if($item['name']=='Percentage1[]')
                    {
                       $Text["Percentage1"][]=$Percentage=round($item['value']/100,2);
                    }
                  
                    
                }
                //echo "test:<pre>";print_r($Text);echo("</pre>") ;
               // echo "test:<pre>";print_r(count($Text["JobFucntion1"]));echo("</pre>") ;

                if(empty($companyName)) $errors['companyName'] = 1;
                if(empty($posotionHeld)) $errors['posotionHeld'] = 1;
                if(empty($startDate)) $errors['startDate'] = 1;
                //if(empty($endDate)) $errors['endDate'] = 1;
                //if(empty($description)) $errors['description'] = 1;

                if(empty($errors)){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $Percentage=round($Percentage/100,2);
                //$isSuccess = $core->addCandidateEmployment($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description);
               // $isSuccess = $core->addCandidateEmploymentJob($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description,$JobFucntion,$Percentage);
                $isSuccess = $core->addCandidateEmploymentJob($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description,$Text);
                
               // $core->addjobfunction($empId,$JobFucntion,$Percentage);
                if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }


            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
   
    public function watchListAction(){
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        
        $CandidateID =$user['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $wlist = $PR_Api->getWatchList(array('CandidateID'=>$CandidateID));

        $this->view->wlist = $wlist;
    }

    public function deleteWatchListAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $OpportunityID = $request->getParam("OpportunityID","");

        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $res = $PR_Api->deleteWatchList($OpportunityID, $CandidateProfileID);

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $res = json_encode($res);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($res), true)
            ->setBody($res);
    }
	 public function profileAction()
    {           
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
                
        $api_candidate= new PR_Api_Core_CandidateClass();
        
        $getUserArray=$api_candidate->getCandidateInfo($UserID);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=$api_candidate->getList_CandidateSkillsOnly($UserID);
        $Skills=array();
        if(!empty($SkillName)||$SkillName!=""){             
        
            foreach($SkillName as $key=>$values )
            {
               $Skills[$key]=$values;   
            }
        }
        $this->view->SkillName=$Skills;
        $CandidateEmployment=array();
        if(!empty($getCandidates["CandidateEmploymentID"])||$getCandidates["CandidateEmploymentID"]!="")
        {
              foreach($getCandidates["CandidateEmploymentID"] as $key=>$values )
                {
                   $CandidateEmployment[$key]=$values;   
                }
        }
        
        $this->view->CandidateEmployment=$CandidateEmployment;
        $Education = $api_candidate->getCandidateEducationList(2); 
        $this->view->Education=$Education;
         
     //  echo ("getUserArray:<pre>");print_r($a);echo("</pre>");
        $this->render('profile');                  
                     
    }
     public function addSkillsAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params= $request->getParams();
        $skills=$params["skills"]; 
       // echo("testt:<pre>");print_r($skills);echo("</pre>");  die();
        $core=new PR_Api_Core_CandidateClass();
        $skillname=array();
        $skillid=array(); 
        $listskill=array();
        if($skills!="" || !empty($skills))
        {
            foreach ($skills as $skill)
            {
                $skillID=$core->get_skill($skill);
                
                 array_push($skillname,$skillID[0]["SkillName"]);  

               
            }
        }
        $result["SkillName"]=$skillname;         
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);
    }
    public function editoverviewAction()
    {           
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=$core->getList_CandidateSkillsOnly($UserID);
        $Skills=array();
        if($SkillName!="")
        {
            foreach($SkillName as $key=>$values )
            {
               $Skills[$key]=$values;   
            }
        }
        $this->view->SkillName=$Skills;
        $allskills=$core->getListAll_CandidateSkills($UserID);
        $this->view->allskills=$allskills;        
             
        $this->render('edit-overview');          
                     
    }
   public function doEditoverviewAction()
    {           
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $request = $this->getRequest();
        $params= $request->getParams();
        $return = array("success" => 0, "error" => "");
        $updateFields=array();
        foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
       
        $core=new PR_Api_Core_CandidateClass();
       // $skillID=array();
       if(isset($updateFields["SkillName"]))
        {
            foreach($updateFields["SkillName"] as $key=> $skills)
            {
                $skillID[$key]=$core->getskillID($skills);
               
            }
        }
        $updateFields["SkillID"]=$skillID;
        $core-> updateCandidateProfile($UserID,$updateFields);
        $return["success"]=1;
                     
    }
    
    public function vieweducationAction()
    {           
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);
        $this->view->getCandidates=$getCandidates;   
        $listEducation=$core->getCandidateEducationList($UserID);
        $this->view->listEducation=$listEducation;
       // echo("testt:<pre>");print_r($listEducation);echo("</pre>");
        $this->render('view-education');          
                     
    }
    public function editvieweducationAction()
    {   
              
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];  
        $request = $this->getRequest();
        $params= $request->getParams();
        $CredentialExperienceID=$request->getParam("CredentialExperienceID");     
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);

        $Educationlist=$core->getCandidateEducation($CredentialExperienceID);
        $this->view->getCandidates=$getCandidates;   
        $this->view->Educationlist=$Educationlist; 
        $this->render('edit-view-education');          
                     
    }

    public function employmentAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
        $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        $getUserArray=$Api->getUserArray($authData);

        $core = new PR_Api_Core_CandidateClass();
        $list = $core->getCandidateEmployments($UserID);
        $CandidateprofileID=$client["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);
        $this->view->getCandidates=$getCandidates;
        $this->view->client = $getUserArray;
        $this->view->list = $list;

        // $this->render('profile');
    }

    public function doCandidateEmploymentAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            $companyName = null;
            $posotionHeld = null;
            $startDate = null;
            $endDate = null;
            $description = null;
            $errors = array();

            $CandidateEmploymentID = $params['CandidateEmploymentID'];
            //add new employment
            if($CandidateEmploymentID==""){
                $companyName  = $params['CompanyName'];
                $posotionHeld = $params['PostionHeld'];
                $startDate = $params['StartDate'];
                $endDate = $params['EndDate'];
                $description = $params['Description'];

                if(empty($companyName)) $errors['CompanyName'] = 1;
                if(empty($posotionHeld)) $errors['PostionHeld'] = 1;
                if(empty($startDate)) $errors['StartDate'] = 1;
                if(empty($endDate)) $errors['EndDate'] = 1;

                if(empty($errors)){
                    $client = PR_Session::getSession(PR_Session::SESSION_USER);
                    $core = new PR_Api_Core_CandidateClass();
                    $isSuccess = $core->addCandidateEmployment($client['UserID'],$companyName,$posotionHeld,$startDate,$endDate,$description);
                    if($isSuccess) $ajaxRes['success'] = 1;
                }else{
                    $ajaxRes['info'] = $errors;
                }
            } //end new
            else{
                $companyName  = $params['CompanyName'];
                $posotionHeld = $params['PostionHeld'];
                $startDate = $params['StartDate'];
                $endDate = $params['EndDate'];
                $description = $params['Description'];

                if(empty($companyName)) $errors['CompanyName'] = 1;
                if(empty($posotionHeld)) $errors['PostionHeld'] = 1;
                if(empty($startDate)) $errors['StartDate'] = 1;
                if(empty($endDate)) $errors['EndDate'] = 1;

                $core = new PR_Api_Core_CandidateClass();
                $isSuccess = $core->updateCandidateEmployment($CandidateEmploymentID,$companyName,$posotionHeld,$startDate,$endDate,$description);

                if($isSuccess){ $ajaxRes['success'] = 1;
                }else{
                $ajaxRes['info'] = $errors;
                 }
            }


        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	 public function doEdieducationAction()
    {           
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $request = $this->getRequest();
        $params= $request->getParams();
        $return = array("success" => 0, "error" => "");
        $updateFields=array();
        foreach ($params as $key => $value) {
            $updateFields[$key]=$value;
            
            }
            
        if(isset($updateFields["display"]))
        {
              $updateFields["display"]=1;
        }
        else{
             $updateFields["display"]=0;  
        }
        $core=new PR_Api_Core_CandidateClass();
        $core->updateEducation($updateFields["CredentialExperienceID"],$updateFields["institution_name"],
        $updateFields["title"],$updateFields["startdate"],$updateFields["enddate"],$updateFields["comments"],$updateFields["display"]);
       // echo("tetstt:<pre>");print_r($updateFields);echo("</pre>");die();   
        $return["success"]=1;
        
                     
    }
	 public function addEducationAction()
    {           
       
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
        $CredentialExperienceID=$request->getParam("CredentialExperienceID");     
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);   
        $this->view->getCandidates=$getCandidates;
       // $a=$core->getList_CandidateSkillsDad($UserID);
       // echo("tetst:<pre>");print_r($a);echo("</pre>");
        $this->render('add-education');
                     
    }
	 public function doAddnewEducationAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest();
        $params = $request->getParams();
        $core=new PR_Api_Core_CandidateClass();
             
        if(isset($params["display"]))
        {
              $params["display"]=1;
        }
        else{
             $params["display"]=0;  
        }
        $result=$core->addEducation($UserID,$params["institution_name"],$params["title"],$params["startdate"],$params["enddate"],$params["comments"],$params["display"]);
        $return["success"]=1; 
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);                    
        // echo ("tetstt:<pre>");print_r($params);echo("</pre>");
     }
	  public function portfolioAction()
     {
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
            
        $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
		$CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);   
        $this->view->getCandidates=$getCandidates;
        $list = $core->getListCandidatePortfolio($UserID);
        $this->view->list = $list;
        $this->render('portfolio');
     }
	 public function addportfolioAction()
     {
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
        $CredentialExperienceID=$request->getParam("CredentialExperienceID");     
         $core=new PR_Api_Core_CandidateClass();
        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);   
        $this->view->getCandidates=$getCandidates;
        $this->render('add-portfolio');
        //
     }
	 public function doAddnewportfolioAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest();
        $params = $request->getParams();
        $core=new PR_Api_Core_CandidateClass();
        $portfolioId=$core->setPortfolioid();
        $images=array();
        for($i=0; $i<count($_FILES['file']['name']); $i++) {
          //Get the temp file path
          $tmpFilePath = $_FILES['file']['tmp_name'][$i];
          
          //Make sure we have a filepath
          if ($tmpFilePath != ""){
            //Setup our new file path
            $filename = uniqid() ."_". $_FILES["file"]["name"][$i];   
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], DIR_MEDIA_PORTFOLIO . $filename); 
             $url=URL_MEDIA_PORTFOLIO . $filename;  
             $core->saveImagesPortfolio($portfolioId,$filename);             
          //echo "<img src='$url' width='120' /><br />";                   
           }
          }
        //  echo ("testt:<pre>");print_r($images);echo("</pre>");die();
          $core->addCandidatePortfolio($UserID,$params["Title"],$params["URL"],
        $params["Description"],"");
        if(isset($params["AddPorfolio"])&& $params["AddPorfolio"]!="" )
        {
            header("Location: portfolio");
        }
        if(isset($params["AddAndAnothorPortfolio"])&& $params["AddAndAnothorPortfolio"]!="" )
        {
            header("Location: addportfolio");
        }
          $return["success"]=1; 
     }

    public function getPortfolioAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            if(!empty($params['data']) && count($params['data'])==1){
                if(!empty($params['data'][0])){
                    $core=new PR_Api_Core_CandidateClass();
                    $ajaxRes['data'] =$core->getCandidatePortfolio($params['data'][0]);
                    $ajaxRes['success'] = 1;
                }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doUpdatePortfolio2Action(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();

            $portId = null;
            $title = null;
            $url = null;
            $description = null;

            if(!empty($params)){
                foreach($params['data'] as $item){
                    if($item['name']=='portId') $portId = $item['value'];
                    if($item['name']=='title')  $title = $item['value'];
                    if($item['name']=='url')    $url = $item['value'];
                    if($item['name']=='description') $description = $item['value'];
                }
                $core = new PR_Api_Core_CandidateClass();
                if($core -> updateCandidatePortfolio( $portId, $title, $url, $description,null)){
                    $ajaxRes['success'] = 1;
                }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);


    }

    public function editportfolioAction(){
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest(); 
        $CandidatePortfolioID=$request->getParam("id");     
        $core=new PR_Api_Core_CandidateClass();
        $portfolio=$core->getCandidatePortfolio($CandidatePortfolioID);
        $images=$core->getImagesPortfolio($CandidatePortfolioID);
        $this->view->Portfolio=$portfolio;
        $this->view->ListImages=$images;

        $getUserArray=$core->getCandidateInfo($UserID);   
        $this->view->UserArray=$getUserArray;
        $CandidateprofileID=$user["CandidateProfileID"];
        $getCandidates=$core->getCandidateProfile($CandidateprofileID);   
        $this->view->getCandidates=$getCandidates;
       // echo("Testt:<pre>");print_r($images);echo("</pre>");
        $this->render('edit-portfolio');
     }
	  public function deleteimagesAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $request = $this->getRequest(); 
        $id=$request->getParam("id");    
        $portfolioid=$request->getParam("portfolioid"); 
          
        $core=new PR_Api_Core_CandidateClass();
        //echo "tetst:";print_r($id);die();
        $result=$core->deleteImagesPortfolio($id);
        $ajaxRes['success'] = 1;
        //$ajaxRes['portfolioid']=$id;
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
        
     }
	 public function doUpdateportfolioAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];   
        $request = $this->getRequest();
        $params = $request->getParams();
        $core=new PR_Api_Core_CandidateClass();
        $portfolioId=$request->getParam("CandidatePortfolioID");
        //echo("Testt:<pre>");print_r($portfolioId);echo("</pre>");die();
        if(isset($_FILES['file']['name'])){
        for($i=0; $i<count($_FILES['file']['name']); $i++) {
          //Get the temp file path
          $tmpFilePath = $_FILES['file']['tmp_name'][$i];
          
          //Make sure we have a filepath
          if ($tmpFilePath != ""){
            //Setup our new file path
            $filename = uniqid() ."_". $_FILES["file"]["name"][$i];   
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], DIR_MEDIA_PORTFOLIO . $filename); 
             $url=URL_MEDIA_PORTFOLIO . $filename;  
             $core->saveImagesPortfolio($portfolioId,$filename);             
                           
           }
          }}

          $core->updateCandidatePortfolio($portfolioId,$params["Title"],$params["URL"],
        $params["Description"],"");
        if(isset($params["AddPorfolio"])&& $params["AddPorfolio"]!="" )
        {
            header("Location: portfolio");
        }
        if(isset($params["AddAndAnothorPortfolio"])&& $params["AddAndAnothorPortfolio"]!="" )
        {
            header("Location: addportfolio");
        }
          $return["success"]=1; 
     }
	  public function portfoliodetailAction()
    {           
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
                
        $api_candidate= new PR_Api_Core_CandidateClass();
        
        $getUserArray=$api_candidate->getCandidateInfo($UserID);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=$api_candidate->getList_CandidateSkillsOnly($UserID);
        $Skills=array();
        if(!empty($SkillName)||$SkillName!=""){             
        
            foreach($SkillName as $key=>$values )
            {
               $Skills[$key]=$values;   
            }
        }
        $this->view->SkillName=$Skills;
        $CandidateEmployment=array();
        if(!empty($getCandidates["CandidateEmploymentID"])||$getCandidates["CandidateEmploymentID"]!="")
        {
              foreach($getCandidates["CandidateEmploymentID"] as $key=>$values )
                {
                   $CandidateEmployment[$key]=$values;   
                }
        }
        
        $request = $this->getRequest();
        $CandidatePortfolioID = $request->getParam("id");
        $core=new PR_Api_Core_CandidateClass();
        $portfolio=$core->getCandidatePortfolio($CandidatePortfolioID);
        $images=$core->getImagesPortfolio($CandidatePortfolioID);
        $this->view->Portfolio=$portfolio;
        $this->view->ListImages=$images;
         
     // echo ("getUserArray:<pre>");print_r($portfolio);echo("</pre>");
        $this->render('portfoliodetail');          
                     
    }

    public function matchopportunitiesAction(){
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];
        $PR_Api = new PR_Api_Core_CareerClass();
        $skillList = $PR_Api->getListSkill();
        $result = $PR_Api->getListOpportunity();

        $industryListUnique = array();
        $industryList = array();
       // $experiencedUnique = array();
        $experienced_List = array();
        $countryUnique = array();
        //$country_List = array();
        $cityUnique = array();
        $city_List = array();
        $country_code = array();

        $core = new PR_Api_Core_CandidateClass();

        $oppList = array();

        if($result !=""){
            foreach ($result as $kk=>$oppListInfo){
                if($oppListInfo['status'] ==1){
                $industryListUnique[] = trim(strtolower($oppListInfo['industry']));
                    if($oppListInfo['country'] !="" || !empty($oppListInfo['country'])){
                $countryUnique[] = $oppListInfo['country'];
                    }

                    if($oppListInfo['city'] !="" || !empty($oppListInfo['city'])){
                $cityUnique[] =trim(strtolower($oppListInfo['city']));
                    }

               /* $hadApplied = $core->opportunityCandidateHadApplied($oppListInfo['OpportunityID'],$CandidateProfileID);
                if($hadApplied){
                    $oppListInfo['hadApplied'] =true ;
                    $oppList[] = $oppListInfo;
                } else {
                    $oppListInfo['hadApplied'] =false ;
                    $oppList[] = $oppListInfo;
                } */
                }
               // $experiencedUnique[] = $oppListInfo['experienced'];
            }

            $industryListUnique = array_unique($industryListUnique);
            $cityUnique = array_unique($cityUnique);

            foreach ($industryListUnique as $industryInfo) {
                $industryList[] = ucwords($industryInfo);
            }

            foreach ($cityUnique as $cityInfo) {
                $city_List[] = ucwords($cityInfo);
            }

            $country_code = array_unique($countryUnique);
        }

        $experienced_List = $PR_Api->getListExperiencedTime();
        $country_List = $core->getCountryList($country_code);

        $candID = $client['CandidateProfileID'];
        $rslCanID = $core->get_candidate_list($candID);
        /*echo "<pre>";
        print_r($rslCanID[0]);
        echo "<pre>"; die(); */
        $this->view->candidateInfo = $rslCanID[0];

        $this->view->skillList = $skillList;
        //$this->view->oppList = $oppList;
        $this->view->industryList = $industryList;
        $this->view->experienced_List = $experienced_List;
        $this->view->country_List = $country_List;
        $this->view->city_List = $city_List;
        $this->view->CandidateProfileID = $client['CandidateProfileID'];
        // $this->render('profile');
    }

    public function matchOpportunityAction(){
        $this->_helper->layout->disableLayout();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];
        $request = $this->getRequest();
        $params = $this->getRequest()->getParams();

        $industry = $request->getParam("technology_id","");
        $experienced = $request->getParam("experience_name","");
        $country = $request->getParam("country_name","");
        $city = $request->getParam("city_name","");

        if(isset($params['matchopportunitySear'])){
            $opportunitiesSearchList = $request->getParam("matchopportunitySear","");
        } else{
            $opportunitiesSearchList = array();
        }

        $core = new PR_Api_Core_CandidateClass();
        $opportunityList1 = $core->getOpportunitiesMatch($industry,$experienced,$country,$city,$opportunitiesSearchList);

        $list_candidate = $core->get_candidate_list($CandidateProfileID);
        $candidateImg = $list_candidate[0];

        $PR_Api = new PR_Api_Core_CareerClass();

        $oppList = array();
        //$result = array();
       if($opportunityList1 !=""){
            foreach($opportunityList1 as $key=>$opportunityList1Info){
                if($opportunityList1Info['status']==1){
                   $result = $PR_Api->getOpportunityInfoByID($opportunityList1Info['OpportunityID']);
                   $hadApplied = $core->opportunityCandidateHadApplied($opportunityList1Info['OpportunityID'],$CandidateProfileID);

               if($hadApplied){
                    $result['hadApplied'] =true ;
                    $result['image'] = $candidateImg['image'];
                    $oppList[] = $result;
               } else {
                   $result['hadApplied'] =false ;
                   $result['image'] = "";
                   $oppList[] = $result;
            }
               }

        }
        }
       /*echo "<pre>";
            print_r($oppList); echo "<br>";
        echo "</pre>"; die();*/
        $this->view->oppList = $oppList;
        $this->view->CandidateProfileID = $client['CandidateProfileID'];

    }

    public function doSearchOpportunitiesAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];
        $request = $this->getRequest();
        $params = $this->getRequest()->getParams();
        $industry = $request->getParam("technology_id","");
        $experienced = $request->getParam("experience_name","");
        $country = $request->getParam("country_name","");
        $city = $request->getParam("city_name","");

        if(isset($params['matchopportunitySear'])){
            $opportunitiesSearchList = $request->getParam("matchopportunitySear","");
        } else{
            $opportunitiesSearchList = array();
        }

        $core = new PR_Api_Core_CandidateClass();
        $opportunityListID = $core->getOpportunitiesMatch($industry,$experienced,$country,$city,$opportunitiesSearchList);

        $PR_Api = new PR_Api_Core_CareerClass();

        $oppList = array();
        //$result = array();
       if($opportunityListID !=""){
            foreach($opportunityListID as $key=>$opportunityID){
                $result = $PR_Api->getOpportunityInfoByID($opportunityID);
               $hadApplied = $core->opportunityCandidateHadApplied($opportunityID,$CandidateProfileID);

               if($hadApplied){
                    $result['hadApplied'] =true ;
                    $oppList[] = $result;
               } else {
                   $result['hadApplied'] =false ;
                   $oppList[] = $result;
               }
            }
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $oppList = json_encode($oppList);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($oppList), true)
            ->setBody($oppList);
       //$this->_helper->viewRenderer('matchopportunities');

    }

    public function candidateApplyAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $params = $this->getRequest()->getParams();
        $CandidateProfileID = $params['CandidateProfileID'];
        $OpportunityID = $params['OpportunityID'];
        $OppCandidateAppliedStatus = 1;
        $CandidateHideStatus = 1;

        $updateFields = array('OpportunityID'=>$OpportunityID, 'CandidateProfileID'=>$CandidateProfileID,'CandidateAppliedStatus'=>$OppCandidateAppliedStatus,
            'CandidateHideStatus'=>$CandidateHideStatus);
        $core = new PR_Api_Core_CandidateClass();
        $result = $core->saveOpportunityCandidateApply($updateFields);
        /*echo "<pre>";
            print_r($OpportunityCandidateApplyID);
        echo "</pre>"; die(); */
        if($result){
            $return = array("success" => 1, "error" => "Apply successfully");
        } else{
            $return = array("success" => 0, "error" => "You had applied the job");
        }

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
            ->setBody($return);
    }
	
	    public function activitiesAction(){
        $user = PR_Session::getSession(PR_Session::SESSION_USER);

        $CandidateID =$user['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $activelist = $PR_Api->getOpportunityCandidateMatchActivities($CandidateID);
        $this->view->activelist = $activelist;
    }
	
     public function myprofileAction()
    {           
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
                
        $api_candidate= new PR_Api_Core_CandidateClass();
        
        $getUserArray=$api_candidate->getCandidateInfo($UserID);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
        $SkillName=$api_candidate->getList_CandidateSkillsOnly($UserID);
        $Skills=array();
        if(!empty($SkillName)||$SkillName!=""){             
        
            foreach($SkillName as $key=>$values )
            {
               $Skills[$key]=$values;   
            }
        }
        $this->view->SkillName=$Skills;
        $portfolio = $api_candidate->getListCandidatePortfolio($UserID);
        $this->view->Portfolio=$portfolio;
		$interest=$api_candidate->getListInterest_candidateprofile($Candidateprofile_ID);
		$this->view->interest=$interest;
       // $CandidateEmployment=array();
       // if(!empty($getCandidates["CandidateEmploymentID"])||$getCandidates["CandidateEmploymentID"]!="")
       // {
         //     foreach($getCandidates["CandidateEmploymentID"] as $key=>$values )
            //    {
               //    $CandidateEmployment[$key]=$values;   
               // }
        //}
        
       // $this->view->CandidateEmployment=$CandidateEmployment;
        //$Education = $api_candidate->getCandidateEducationList(2); 
        //$this->view->Education=$Education;
         
       //echo ("interest:<pre>");print_r($interest);echo("</pre>");
        $this->render('myprofile');                  
                     
    }

    public function deleteActivitiesAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $OpportunityID = $request->getParam("OpportunityID","");

        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $CandidateProfileID = $client['CandidateProfileID'];

        $PR_Api = new PR_Api_Core_CandidateClass();
        $res = $PR_Api->deleteActivities($OpportunityID, $CandidateProfileID);

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $res = json_encode($res);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($res), true)
            ->setBody($res);
    }
	public function uploadVideoAction()
    {           
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
                
        $api_candidate= new PR_Api_Core_CandidateClass();
        
        $getUserArray=$api_candidate->getCandidateInfo($UserID);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
       
        $this->render('upload-video');                  
                     
    }
	 public function uploadfileAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("testt:");print_r($user);
        $core=new PR_Api_Core_CandidateClass();  
        $CandidateProfileID=$user["CandidateProfileID"];
        $return = array("status" => "", "message" => "");    
        $allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma","flv","mpeg","mov","m4v","dat","aac","avi");
        $extension = pathinfo($_FILES['myfile']['name'],PATHINFO_EXTENSION);
        $mimes = array(
        'image/jpeg', 'image/png', 'image/gif', 'image/pjpeg', 'video/mp4', 'audio/mp3', 'audio/wma'
        );
        if(in_array($extension, $allowedExts)){  
        sleep(2);
        
        if (isset($_FILES['myfile'])) {
            $fileName = $_FILES['myfile']['name'];
            $fileType = $_FILES['myfile']['type'];
            $fileError = $_FILES['myfile']['error'];
            $fileStatus = array(
                'status' => 0,
                'message' => '' 
            );
            if ($fileError== 1) { 
                $fileStatus['message'] = 'Size over the allowed limit';
                $fileStatus['status'] = 0;
            //} //elseif (!in_array($fileType, $mimes)) { 
              //  $fileStatus['message'] = 'format error';
              //  $fileStatus['status'] = 0; 
            } else { 
                move_uploaded_file($_FILES['myfile']['tmp_name'], DIR_MEDIA_VIDEO.$fileName);
                $fileStatus['status'] = 1;
                $fileStatus['message'] = "Completed";
                $core->updateCandidateProfileVideo($CandidateProfileID,$fileName);
            }   
                $return["status"]= $fileStatus['status'];
                $return["message"]= $fileStatus['message'];
               // header("Location: profile");  
             echo json_encode($return);
            
  

        
            }    } else echo "format error";  
             
       
            
     }
     public function uploadPhotoAction()
     {           
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
                
        $api_candidate= new PR_Api_Core_CandidateClass();
        
        $getUserArray=$api_candidate->getCandidateInfo($UserID);
        $this->view->client = $getUserArray;  
        $Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
        $getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
        $this->view->getCandidates=$getCandidates;
       
        $this->render('upload-photo');                  
                     
     }
      public function doUploadPhotoAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        //echo("testt:");print_r($user);
        $core=new PR_Api_Core_CandidateClass();  
        $CandidateProfileID=$user["CandidateProfileID"];
        $return = array("status" => "", "message" => "");    
        $allowedExts = array("jpg", "jpeg", "gif", "png");
        $extension = pathinfo($_FILES['myfile']['name'],PATHINFO_EXTENSION);
        $mimes = array(
        'image/jpeg', 'image/png', 'image/gif', 'image/pjpeg');
        if(in_array($extension, $allowedExts)){  
        sleep(2);
        
        if (isset($_FILES['myfile'])) {
            $fileName = $_FILES['myfile']['name'];
            $fileType = $_FILES['myfile']['type'];
            $fileError = $_FILES['myfile']['error'];
            $fileStatus = array(
                'status' => 0,
                'message' => '' 
            );
            if ($fileError== 1) { 
                $fileStatus['message'] = 'Size over the allowed limit';
                $fileStatus['status'] = 0;
            //} //elseif (!in_array($fileType, $mimes)) { 
              //  $fileStatus['message'] = 'format error';
              //  $fileStatus['status'] = 0; 
            } else { 
                move_uploaded_file($_FILES['myfile']['tmp_name'], DIR_MEDIA_PHOTO.$fileName);
                $fileStatus['status'] = 1;
                $fileStatus['message'] = "Completed";
                $core->updateCandidateProfilePhoto($CandidateProfileID,$fileName);
            }   
                $return["status"]= $fileStatus['status'];
                $return["message"]= $fileStatus['message'];
               // header("Location: profile");  
             echo json_encode($return);
            
  

        
            }    } else echo "format error";  
             
       
            
     }
	public function skillsEditAction()  
	{
		$user = PR_Session::getSession(PR_Session::SESSION_USER);
		$UserID=$user["UserID"];
		$request = $this->getRequest();
		$params = $request->getParams();        
		$api_candidate= new PR_Api_Core_CandidateClass();
		$SkillID=$params["id"]; 
		$this->view->SkillID= $SkillID;
		$getUserArray=$api_candidate->getCandidateInfo($UserID);
		$this->view->UserArray = $getUserArray;  
		$Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
		$getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
		$this->view->getCandidates=$getCandidates;
		$skills = $api_candidate->getListAll_CandidateSkills($UserID);
		$this->view->Skills=$skills;
		$this->view->Candidateprofile_ID=$Candidateprofile_ID;
		$this->render("skills/editskill");
       // $api_candidate->updateCandidate_skill(4,11,7,'Intermediate');
	   // echo("testt:<pre>");print_r($params);echo("</pre>");
		   
	  }  
     public function doEditSkillAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $user = PR_Session::getSession(PR_Session::SESSION_USER); 
        $CandidateProfileID=$user["CandidateProfileID"];
        $ajaxRes = array('success'=>0,'info'=>null);
       // echo("testt:<prre>");print_r($params);echo("</pre>");die();
         if(!empty($params)){
                foreach($params['data'] as $item){
                    if($item['name']=='skill_id') $SkillID = $item['value'];
                    if($item['name']=='YearsExperience') $YearsExperience = $item['value'];
                    if($item['name']=='LevelOfExperience')  $LevelOfExperience = $item['value'];
					if($item['name']=='CandidateSkillID')  $CandidateSkillID = $item['value'];
                }
                $core = new PR_Api_Core_CandidateClass();
               if($core->updateCandidate_skill($CandidateSkillID,$SkillID,$YearsExperience,$LevelOfExperience)){
                    $ajaxRes['success'] = 1;
                }
            }
            
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
       
        
     }

    public function skilltestAction()
    {
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];
        $emailaddress = $client["emailaddress"];
        $password = $client["password"];
        $Api = new PR_Api_User();
        $authData = array('emailaddress' => $emailaddress, 'password' => $password);
        $getUserArray=$Api->getUserArray($authData);
        $this->view->client = $getUserArray;

        $CandidateID = $client["CandidateProfileID"];
        $PR_Api = new PR_Api_Core_CandidateClass();
	
        $candidate_applied = $PR_Api->getOpportunityCandidateMatchActivities($CandidateID);
        $candidate_applied_list = array();

        if(!empty($candidate_applied) && count($candidate_applied) >0){
            foreach($candidate_applied as $kk=>$candidate_appliedInfo){
                $candidate_applied_list[] =  $candidate_appliedInfo['OpportunityID'];
            }

            $listTestID=$PR_Api->getTestIDbyOpportunity($candidate_applied_list);
        }


        $getCandidates=$PR_Api->getCandidateProfile($CandidateID);
        $this->view->getCandidates=$getCandidates;
       /*echo "<pre>";
        print_r($listTestID);
        echo "</pre>"; die();*/
        $this->view->listTestID = $listTestID;

    }

    public function skillTestViewAction()
    {
        $this->_helper->layout->disableLayout();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);

        $CandidateID = $client["CandidateProfileID"];
        $PR_Api = new PR_Api_Core_CandidateClass();
        $request = $this->getRequest();
        $params = $this->getRequest()->getParams();
        $TestID = $params['testID'];
        $testName = $params['testName'];
        if(isset($params['SaveTestAnswer'])){
            if($params['SaveTestAnswer']){
                $TestQuestionAnswerID = $params['TestQuestionAnswerID'];
                $PR_Api->saveAnswerTest($CandidateID, $TestQuestionAnswerID);
            }
        }

        $page = $params['page'];
        $size = 1;
        if (!empty($page)){
            $page = $page;
        }  else {
            $page = 1;
        }
        $offset = ($page * $size) - $size;

        $questionAnswerList = "";
        $countRows ="";
        $paginator="";
        $rsRow = "";

            if($TestID !=""){
                $questionAnswerList =$PR_Api->getQuestionsAnswer($TestID,$CandidateID,0,0);
                $rsRow =$PR_Api->getQuestionsAnswer($TestID,$CandidateID, $size,$offset);
                $countRows = count($questionAnswerList);
               // $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($rsRow));
               // $paginator = Zend_Paginator::factory($rsRow);
               // $paginator->setItemCountPerPage(1)
                //    ->setPageRange(1)
                //    ->setCurrentPageNumber($page);
            }

       /*echo "<pre>";
            print_r($questionAnswerList);
        echo "</pre>"; die(); */
        $this->view->questionAnswerList = $questionAnswerList;
       // $this->view->paginator = $paginator;
        $this->view->paginator = $rsRow;
        $this->view->numberTestQuests = $countRows;
        $this->view->page = $page;
        $this->view->TestID = $TestID;
        $this->view->testName = $testName;
    }
	public function interestAction()  
	{
		$user = PR_Session::getSession(PR_Session::SESSION_USER);
		$UserID=$user["UserID"];
		$request = $this->getRequest();
		$params = $request->getParams();        
		$api_candidate= new PR_Api_Core_CandidateClass();
		$getUserArray=$api_candidate->getCandidateInfo($UserID);
		$this->view->UserArray = $getUserArray;  
		$Candidateprofile_ID=$getUserArray["CandidateProfileID"]; 
		$getCandidates=$api_candidate->getCandidateProfile($Candidateprofile_ID);
		$this->view->getCandidates=$getCandidates;
		$listInterest=$api_candidate->getcandidate_Interest($Candidateprofile_ID);
		$this->view->listInterest=$listInterest;
		
		$this->render("interest");
		//echo("testt:<pre>");print_r($listInterest);echo("</pre>");
		   
	  }  

    public function skillTestResultAction()
    {
        $this->_helper->layout->disableLayout();
        $client = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$client["UserID"];

        $CandidateID = $client["CandidateProfileID"];
        $PR_Api = new PR_Api_Core_CandidateClass();

        $params = $this->getRequest()->getParams();
        $TestID = $params['testID'];

        $result=$PR_Api->get_TestID($TestID);
        /*echo "<pre>";
         print_r($result);
         echo "</pre>"; die();*/
        $this->view->result = $result;

    }
	
	public function deleteInterestAction(){
         $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $tid = $params['dataTId'];
        if($tid > 0){
            $arrTid = array();
            array_push($arrTid,$tid);
            $core = new  PR_Api_Core_CandidateClass();
            $core->deleteInterest($arrTid);
            $ajaxRes['info'] = '';
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	public function removeInterestsAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $params = $this->getRequest()->getParams();
        $arrTest = $params['dataTIds'];
		//echo "tetstt:".$arrTest;
        if(is_array($arrTest) && sizeof($arrTest) > 0){
            $core = new  PR_Api_Core_CandidateClass();
            $core->deleteInterest($arrTest);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	public function editInterestAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'interestid'=>null,'interesttext'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
			
			$id = $params['id'][0];
			
            if($id > 0){
                //$client = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
                $res = $core->getInterest($id);
				//echo("testt:<pre>");print_r($res);echo("</pre>");
				foreach ($res as $record)
				{
					$ajaxRes['interestid'] = $record["interestid"];
					$ajaxRes['interesttext'] = $record["interesttext"];
				}
               
               $ajaxRes['success']  = 1;
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	public function addInterestAction()  
	{
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $user = PR_Session::getSession(PR_Session::SESSION_USER); 
        $CandidateProfileID=$user["CandidateProfileID"];
        $ajaxRes = array('success'=>0,'info'=>null);
		if(!empty($params["interest"])){
		 $core = new PR_Api_Core_CandidateClass();
		 $interestid=$core->AddInterest($CandidateProfileID,$params["interest"]);
		// echo "Testtt:<pre>";print_r($interestid);echo("</pre>");die();
               if(empty($interestid["Erorr"])){
                    $ajaxRes['success'] = 1;
					 
                }
				else{
				$ajaxRes['success'] = 0;
				$ajaxRes['info'] = $interestid["Erorr"];
				}
				header("Location: interest");  
		}
		$response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
		//echo ("Testt:<pre>");print_r($params);echo("</pre>");die();
		   
	}
	public function doEditInterestAction()
     {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        
        $ajaxRes = array('success'=>0,'info'=>null);
       // echo("testt:<prre>");print_r($params);echo("</pre>");die();
         if(!empty($params)){
                $interestid=$params["interestid"];
				$interesttext=$params["interest"];
				
                $core = new PR_Api_Core_CandidateClass();
               if($core->updateInterest($interestid,$interesttext)){
                    $ajaxRes['success'] = 1;
                }
            }
            
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
       
        
     }
	 public function doInterestAction()
     {
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
		$user = PR_Session::getSession(PR_Session::SESSION_USER); 
        $CandidateProfileID=$user["CandidateProfileID"];
		$ajaxRes = array('success'=>0,'info'=>null);
		if(!empty($params["interestid"])){
                $interestid=$params["interestid"];
				$interesttext=$params["interest"];
				
                $core = new PR_Api_Core_CandidateClass();
               if($core->updateInterest($interestid,$interesttext)){
                    $ajaxRes['success'] = 1;
					
                }
				header("Location: interest"); 
            }
		else{
		if(!empty($params["interest"])){
		 $core = new PR_Api_Core_CandidateClass();
		 $interestid=$core->AddInterest($CandidateProfileID,$params["interest"]);
		// echo "Testtt:<pre>";print_r($interestid);echo("</pre>");die();
               if(empty($interestid["Erorr"])){
                    $ajaxRes['success'] = 1;
					 
                }
				else{
				$ajaxRes['success'] = 0;
				$ajaxRes['info'] = $interestid["Erorr"];
				}
				header("Location: interest");  
		}
		}
		header("Location: interest");  
		
		
		   
	 }
	 public function referencesAction(){
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $core = new PR_Api_Core_CandidateClass();
        $CandidateprofileID=$user["CandidateProfileID"];	
		$getUserArray=$core->getCandidateInfo($UserID);
		$this->view->UserArray = $getUserArray;
		$getCandidates=$core->getCandidateProfile($CandidateprofileID);
		$this->view->getCandidates=$getCandidates;
        $getReferences=$core->getListReferences_candidateprofile($CandidateprofileID);
        $this->view->getReferences=$getReferences;
		
		//echo "Test:<pre>";print_r($CandidateprofileID);echo("</pre>");

         $this->render('references');
    }

    public function doAddReferencesAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$user = PR_Session::getSession(PR_Session::SESSION_USER);
        $UserID=$user["UserID"];       
        $core = new PR_Api_Core_CandidateClass();
        $CandidateprofileID=$user["CandidateProfileID"];
        $ajaxRes = array('success'=>0,'info'=>null);
        $request = $this->getRequest();
        $params = $request->getParams();
		//echo "testt:<pre>";print_r($params);echo("</pre>");
		if(!empty($params["referencename"]) && !empty($params["referenceemail"]))
		{
			$core->addReferences($CandidateprofileID,$params["referencename"],$params["referenceemail"],$params["referencecomment"]);
			$ajaxRes['success'] = 1;
		}
		else{
			if(empty($params["referencename"])){
				$ajaxRes['success'] = 0;
				$ajaxRes['info'] = "References name not empty";
			}
			if(empty($params["referenceemail"])){
				$ajaxRes['success'] = 0;
				$ajaxRes['info'] = "References mail not empty";
			}
			
		}
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function addJobFunctionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $user = PR_Session::getSession(PR_Session::SESSION_USER);
        $ajaxRes = array('success'=>0,'pecent'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $Text=array("JobFucntion1"=>"","Percentage1"=>"","Percentage_old"=>"");
            
            foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')    $empId  = $item['value'];
                    if($item['name']=='JobFucntion_add')    $JobFucntion  = $item['value'];
                    
                    if($item['name']=='Percentage_add')      $Percentage = $item['value'];

                    
                    if($item['name']=='Percentage1[]')
                    {
                       $Text["Percentage1"][]=$item['value'];
                    }
                    if($item['name']=='JobFucntion1[]')
                    {
                       $Text["JobFucntion1"][]=$item['value'];
                    }
                   if($item['name']=='Percentage1_old[]')   
                   {
                       $Text["Percentage_old"][]=$item['value'];
                   }   //$Percentage1_old = $item['value'];
                  
                }
                
                $pecent=0;
                $core = new PR_Api_Core_CandidateClass();
                $totalPercentage=$core->totalPercentage($empId);
                $totalPercentage=round($totalPercentage["totalPercentage"]*100,2);
               
                $Percentage1=0;
                if(!empty($Text["Percentage1"])){
                   for($i=0;$i<count($Text["Percentage1"]);$i++){
                       
                       $Percentage1=$Percentage1+$Text["Percentage1"][$i];
                       
                   } 
                   
                }
                $Percentage_old=0;
                 if(!empty($Text["Percentage_old"])){
                   for($i=0;$i<count($Text["Percentage_old"]);$i++){
                       
                       $Percentage_old=$Percentage_old+round($Text["Percentage_old"][$i]*100,2);
                       
                   } 
                   
                }
               // $Percentage_old=$Percentage_old*100;
                $pecent=$totalPercentage+$Percentage1-$Percentage_old;
                //echo "tetst:".$pecent;
                $ajaxRes['pecent'] = 100-$pecent;
                 
              //  $selectarray=array();
               // if(!empty($Text["JobFucntion1"])){
              //  foreach($Text["JobFucntion1"] as $Values){
                //    $selectarray[]=$Values;
               // }}
               // echo "tetst:";print_r($selectarray);die();
    }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	 public function doAddJobFunctionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$user = PR_Session::getSession(PR_Session::SESSION_USER);
        $ajaxRes = array('success'=>0,'info'=>null,'pecent'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            //echo "testt:<pre>";print_r($params);echo("</pre>");die();
            $JobFucntion = null;
            $CredentialExperienceID = null;
            $Percentage = null;
            $pecent=0;
            $Percentage1=0;
            $Text=array("JobFucntion1"=>"");
            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')    $empId  = $item['value'];
                    if($item['name']=='JobFucntion_add')    $JobFucntion  = $item['value'];
                    //if($item['name']=='JobFucntion1[]')      $JobFucntion1 = $item['value'];
                    if($item['name']=='Percentage_add')      $Percentage = $item['value'];
                    if($item['name']=='Percentage1[]')      $Percentage1 = $item['value']+$Percentage1;
                    
                     if($item['name']=='JobFucntion1[]')
                    {
                       $Text["JobFucntion1"][]=$item['value'];
                    }
                   
                  
                }
				if($Percentage ==""){
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Percentage not empty";
                }
				if($JobFucntion ==""){
					$ajaxRes['success'] = 0;
					$ajaxRes['info'] = "Job Fucntion not empty";
				}
				if(!is_numeric ($Percentage )){
					$ajaxRes['success'] = 0;
					$ajaxRes['info'] = "Percentage must be integer";
					
				}
                $selectarray=array();
                if(!empty($Text["JobFucntion1"])){
                foreach($Text["JobFucntion1"] as $Values){
                    $selectarray[]=$Values;
                }}
                $core = new PR_Api_Core_CandidateClass();
                $jobdb=$core->get_jobfuntion($empId);
                $jobaray=array();
                if(!empty($jobdb)){
                foreach($jobdb as $job){
                   $jobaray[] =$job["JobFunctionID"];
                }}
                if(in_array($JobFucntion,$selectarray) || in_array($JobFucntion,$jobaray)){
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Job Fucntion is exit.";
                }
				//print_r($selectarray);
                //if(empty($JobFucntion)) $errors['JobFucntion'] = 1;
               
                $pecent=$Percentage+$pecent+$Percentage1;
                
				//echo "tetst:".$pecent;		
                if($Percentage!="" && $JobFucntion!=""){
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                
				$totalPercentage=$core->totalPercentage($empId);
				$totalPercentage=round($totalPercentage["totalPercentage"],2);
				//$Percentage=($Percentage/100);
                $pecent=($pecent/100);
				$total=$totalPercentage+$pecent;
                
               // echo "tetst:<pre>";print_r($total);echo("</pre>");die();
				if($total>1)
				{
					$ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Total Percentage greater than 100%";
				}else{
                    $ajaxRes['success'] = 1;
                     $ajaxRes['pecent']=$total;
                    
                }
                
                }else{
					
                   // $ajaxRes['info'] = $errors;
                }
				


            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	public function detailJobFunctionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$ajaxRes = array('success'=>0,'info'=>null);
        
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = isset($params['id']) ? $params['id'] : null;
            $empId=$params['empId'];
			
            $core = new PR_Api_Core_CandidateClass();
           
			$ajaxRes['info'] = $core->getJobFunction($id,$empId);
			$Percentage=$ajaxRes['info']['Percentage'];
			$ajaxRes['info']['Percentage']=round($Percentage*100,2);
			
			
            if(sizeof($ajaxRes['info']) > 0) $ajaxRes['success'] = 1;

        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function editJobFunctionNewAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = isset($params['id']) ? $params['id'] : null;
            
            
            $core = new PR_Api_Core_CandidateClass();
           
            $ajaxRes['info'] = $core->getjobfunctionsid($id);
           // echo "tetst:<pre>";print_r($ajaxRes['info']);echo("</pre>");
          //  $Percentage=$ajaxRes['info']['Percentage'];
           // $ajaxRes['info']['Percentage']=round($Percentage*100,2);
            
            
            if(sizeof($ajaxRes['info']) > 0) $ajaxRes['success'] = 1;

        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	public function doUpdateJobFunctionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $JobFunctionID = null;
            $JobFucntion = null;
            $CredentialExperienceID = null;
            $Percentage = null;      
            $errors = array();
           // echo "tetst:<pre>";print_r($params);echo("</pre>");
            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')    $empId  = $item['value'];
                    if($item['name']=='JobFunctionID')  $JobFunctionID = $item['value'];
                    if($item['name']=='JobFucntion')    $JobFucntion  = $item['value'];
                    if($item['name']=='JobID')          $JobID = $item['value'];
                    if($item['name']=='Percentage')      $Percentage = $item['value'];
                   
                }
				
				if($Percentage ==""){
					$ajaxRes['success'] = 0;
					$ajaxRes['info'] = "Percentage not empty";
				}
                if($JobFucntion ==""){
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Job Fucntion not empty";
                }            

                if($Percentage!="" && $JobFucntion!=""){
                $user = PR_Session::getSession(PR_Session::SESSION_USER);
                $core = new PR_Api_Core_CandidateClass();
				$job=$core->getJobFunction($JobFunctionID,$empId);
				//db value
				$percent=round($job["Percentage"],2);
				//total db values
				$totalPercentage=$core->totalPercentage($empId);
				$totalPercentage=round($totalPercentage["totalPercentage"],2);
				//new value
				$Percentage=round($Percentage/100,2);
				
				$total=$totalPercentage-$percent+$Percentage;
				if($total>1){
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Total Percentage greater than 100%";
					
					
				}else{
                   // $core->deleteJobFunction($JobID);
					$ajaxRes['success'] = 1;
                    $ajaxRes['info']["JobFucntion_ID"]=$JobFucntion;
                    $ajaxRes['info']["JobFunctionID_old"]=$JobFunctionID;
                    $ajaxRes['info']["Percentage"]=$Percentage*100;
                    $ajaxRes['info']["Percentage_old"]=$percent;
					
					
				 }
                
                }
            }
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
    public function doUpdateJobFunctionNewAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        $Text=array("JobFucntion1"=>"","Percentage1"=>"");
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $JobFunctionID = null;
            $JobFucntion = null;
            $CredentialExperienceID = null;
            $Percentage = null;      
            $errors = array();
           // echo "tetst:<pre>";print_r($params);echo("</pre>");
            if(!empty($params['data']) && sizeof($params['data'])){

                foreach($params['data'] as $key=>$item){
                    if($item['name']=='empId')    $empId  = $item['value'];
                    if($item['name']=='JobFunctionID_Edit')  $JobFunctionID_old = $item['value'];
                    if($item['name']=='JobFucntion_Edit')    $JobFucntion  = $item['value'];
                    if($item['name']=='pecent_edit')          $pecent_edit = $item['value'];
                    if($item['name']=='Percentage_Edit')      $Percentage = $item['value'];
                    
                    if($item['name']=='Percentage1[]')
                    {
                       $Text["Percentage1"][]=$item['value'];
                    }
                    if($item['name']=='JobFucntion1[]')
                    {
                       $Text["JobFucntion1"][]=$item['value'];
                    }
                   
                }
                
                if($Percentage ==""){
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Percentage not empty";
                }
                if($JobFucntion ==""){
                    $ajaxRes['success'] = 0;
                    $ajaxRes['info'] = "Job Fucntion not empty";
                }            

            
                
                $pecent=0;
                $core = new PR_Api_Core_CandidateClass();
                $totalPercentage=$core->totalPercentage($empId);
                $totalPercentage=round($totalPercentage["totalPercentage"],2);
               
                $Percentage1=0;
                if(!empty($Text["Percentage1"])){
                   for($i=0;$i<count($Text["Percentage1"]);$i++){
                       
                       $Percentage1=$Percentage1+$Text["Percentage1"][$i];
                       
                   } 
                   
                }
                $total=$totalPercentage+round($Percentage1/100,2)+round($Percentage/100,2)-round($pecent_edit/100,2);
        
              //  if($total>1){
                   // $ajaxRes['success'] = 0;
                   // $ajaxRes['info'] = "Total Percentage greater than 100%";
                    
                   
              //  }else{
                    $ajaxRes['success'] = 1;
                    $ajaxRes['info']["JobFunctionID_old"]=$JobFunctionID_old;
                    $ajaxRes['info']["Percentage"]=$Percentage;
                    $ajaxRes['info']["JobFucntion_ID"]=$JobFucntion;
                  /*  for($i=0;$i<count($Text["JobFucntion1"]);$i++)
                    {
                        if($Text["JobFucntion1"][$i]==$JobFunctionID_old){
                            $Text["JobFucntion1"][$i]=$JobFucntion;
                        }
                        for($j=0;$j<count($Text["Percentage1"]);$j++)
                        {
                            if($Text["Percentage1"][$j]==$pecent_edit && $i=$j){
                                $Text["Percentage1"][$j]=$Percentage;
                            }
                        }
                    }*/
                    
                    
                    
               //  }
                
                }
            }
        //}
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
	public function doRemoveJobFunctionAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $id = $params['id'];
            $core = new PR_Api_Core_CandidateClass();
			//echo "Testt:";echo $id;die();
            $core->deleteJobFunction($id);
            $ajaxRes['success'] = 1;
        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);
    }
     public function doUpdateSkillsnewAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ajaxRes = array('success'=>0,'info'=>null);
        if($this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            
            if(!empty($params['data']) && sizeof($params['data']) > 0){
                $core = new PR_Api_Core_CandidateClass();
                $client = PR_Session::getSession(PR_Session::SESSION_USER);
                if($core->updateCandidateSkill($client['CandidateProfileID'],$params['data'])){
                    $ajaxRes['success'] = 1;
                }
            } 


        }
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $ajaxRes = json_encode($ajaxRes);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($ajaxRes), true)
            ->setBody($ajaxRes);

    }

}
