<?php

  class PR_Api_Core_Mail {
    protected $bodyText;
    protected $fromEmail;
    protected $fromName;
    protected $toEmail;
    protected $toName;
    protected $subject;
    
    public function setBodyText($value)
    {
        $this->bodyText = $value;
    }
    public function setFromEmail($value)
    {
        $this->fromEmail = $value;
    }

    public function setFromName($value)
    {
        $this->fromName = $value;
    }

    public function setToEmail($value)
    {
        $this->toEmail = $value;
    }

    public function setToName($value)
    {
        $this->toName = $value;
    }
    public function setSubject($value)
    {
        $this->subject = $value;
    }
     public function send()
    {
       
      //  include 'Zend\Mail\Transport\Smtp.php';
        $tr = new Zend_Mail_Transport_Smtp('smtp.sendgrid.net',
                    array('auth' => 'login',
                             'username' => 'Mkritzman',
                             'password' => 'jack1rae'));
        Zend_Mail::setDefaultTransport($tr);
        $mail = new Zend_Mail();
        $mail->setBodyText($this->bodyText);
        $mail->setFrom($this->fromEmail, $this->fromName); 
      
        $mail->addTo($this->toEmail, $this->toName);

        $mail->setSubject($this->subject);
       
        $mail->send();
  //     $tr = new Zend_Mail_Transport_Sendmail('-freturn_to_me@example.com');
//Zend_Mail::setDefaultTransport($tr);
 



//

    }
    
  }
?>
