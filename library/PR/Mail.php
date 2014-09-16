<?php

class PR_Mail {

    protected $bodyText;
    protected $fromEmail;
    protected $fromName;
    protected $toEmail;
    protected $toName;
    protected $subject;
    protected $Attachments;

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

    public function addAttachments($value)
    {
        $this->Attachments = $value;
    }

    public function send()
    {
//        $mail = new Zend_Mail();
//        $mail->setBodyText($this->bodyText);
//        $mail->setFrom($this->fromEmail, $this->fromName);
//        $mail->addTo($this->toEmail, $this->toName);
//        $mail->setSubject($this->subject);
//        $mail->send();
//        if (!empty($this->Attachments))
//        {
//            $mail->addAttachment($this->Attachments);
//        }
//
//        $mail->send();
        $frommailtrim = trim( $this->fromEmail);
//        $Headers = "Content-type: text/html\r\n";
//        $Headers.= "From: " . $frommailtrim . "\r\n";
//        $Headers.= "X-Mailer:PHP/" . phpversion() . "\r\n";
//        $Headers.= "X-Priority: 3\r\n";
//        $Headers.= "Reply-To: " . $frommailtrim . "\r\n";
        $Headers = "Content-type: text/html\r\n";
        $Headers.= "From: $frommailtrim \r\n";
        $Headers.= "X-Mailer:PHP/" . phpversion() . "\r\n";
        $Headers.= "X-Priority: 3\r\n";
        $Headers.= "Reply-To: $frommailtrim \r\n";
        $to = trim($this->toEmail) ;
//        print_r($this);
//        @mail($to, $this->subject, $this->bodyText, $Headers);
        @mail($to, $this->subject, $this->bodyText, $Headers);
        
    }

}

?>