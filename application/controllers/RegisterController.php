<?php
   class RegisterController extends Application_Controller_Action {

    public function init() {
        parent::init();
        
    }

    public function indexAction() 
    {   
       /* $api=new PR_Api_Client();
        $firstname= "Nguyen";
        $lasttname= "Carot";
        $username= "CR";
        $email= "test@pr.com";
        $pass= "Test1234";
        
        $data=array(
                "FirstName" => $firstname,
                "LastName" => $lasttname,
                "UserName" => $username,
                "Email1" => $email,
                "Password" => $pass
                );
        $tets=$api->registerClient($data);
        if($tets)
        {
            echo ("isert thanh cong");do-register
        }
        */
        
    }
     public function doRegisterAction() 
    {   
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $params = $request->getParams();
        $return = array("success" => 0, "error" => "","usertype"=>"");
  // echo ("params:<pre>");print_r($params);echo("</pre>");die();

        if(!empty($params)){
                foreach($params['Register'] as $item){
                    if($item['name']=='firstname') $firstname = $item['value'];
                    if($item['name']=='lastname') $lasttname = $item['value'];
                    if($item['name']=='usertype')  $Acount_type = $item['value'];
                    if($item['name']=='emailaddress') $email = $item['value'];
                    if($item['name']=='password') $pass = $item['value'];
                    if($item['name']=='About_us')  $About_us = $item['value'];
                    if($item['name']=='accept')  $accept = $item['value'];
                    if($item['name']=='Companyname')  $Companyname = $item['value'];
                }
                              
            }
             
        $datacompany=array("Companyname"=>$Companyname );
             // echo ("data:<pre>");print_r($data);echo("</pre>");die();
       // $api=new PR_Api_Register();
       $api= new PR_Api_Core_Register();
       $core=new PR_Api_Core_ClientClass();
        
        $data=array(
                "firstname" => $firstname,
                "lastname" => $lasttname,
                "usertype" => $Acount_type,
                "emailaddress" => $email,
                "password" => $pass,
                "HeardFrom" => $About_us
                //"Companyname"=>$Companyname

                );
     
      
       
       if(isset($Companyname)&& $Companyname!=""){
          $record=$core->AddCompany($datacompany); 
          if($record["error"]!=''){
              $return['success'] = 0;
              $return['error'] =$record["error"] ;
          }
       }
       if(isset($accept)&&(($Acount_type==2)||($Acount_type==1&&$record["error"]=="")))
        {
                       
            $tets=$api->registerClient($data);
       if($tets["error"]=="")
       {
               if($Acount_type==2||($Acount_type==1&&$record["error"]=="")){
           $return['success'] = 1;
           $userApi = new PR_Api_User();
            $authData = array('emailaddress' => $data["emailaddress"], 'password' => $data["password"]);
            if ($User = $userApi->loadAndCheckAuthentication($authData))
            {
                PR_Session::setSession($User,PR_Session::SESSION_USER);
                $user = PR_Session::getSession(PR_Session::SESSION_USER);
               // echo("user:");print_r($user);hgj
                
                $return['success'] = 1;
                $return['usertype'] = $user["usertype"];
            } 
              }else{
            $return['success'] = 0;
                   $return['error'] =$record["error"] ;
               }
               
           }
          else
            {
                $return['success'] = 0;
            $return['error'] =$tets["error"] ;
        }
        }
     
       
       
        //return
        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $return = json_encode($return);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($return), true)
                ->setBody($return);  
     // echo("testt:".$tets["error"]);
        
        
    }
    public function doCompanyProfileAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $result = array("success" => 0, "error" => "","comID"=>"");
        $data = array();
        $data['Companyname'] = $request->getParam("Companyname", "");
        $data['Industry'] = $request->getParam("Industry", "");
        $data['Address'] = $request->getParam("Address", "");
        $data['Zipcode'] = "";
        $data['Description'] = $request->getParam("Description", "");
        $data['images'] = "";
        $data['PhoneNumber'] = $request->getParam("PhoneNumber", "");
        $data['country'] = $request->getParam("country", "");
        $data['emailinfo'] = $request->getParam("emailinfo", "");

        //$comID=array();
        $PR_Api_ClientClass = new PR_Api_Core_ClientClass();
        $comID = $PR_Api_ClientClass->AddCompany($data);
        
        if(isset($comID["error"])&& ($comID["error"]=="")){
           // echo "seccc";
            $result['success'] = 1;
            $result['comID'] =$comID ;  
         }
         else{
             $result['success'] = 0;
           //echo "error";
             $result['error'] =$comID["error"] ; 
         }
       

        $response = $this->getResponse();
        $response->clearAllHeaders()->clearBody();
        $result = json_encode($result);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('Content-Length', strlen($result), true)
            ->setBody($result);
    }

}
?>
