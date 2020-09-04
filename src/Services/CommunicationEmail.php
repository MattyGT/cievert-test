<?php
namespace App\Services;

use \Interfaces\CommunicationInterface;
use \Interfaces\CommunicationEmailInterface;

use App\Swiftmailer\Swiftmailer\SwiftMailer;

class CommunicationEmail {
  private $toAddress = "matthewgtyler@gmail.com";
  private $toName = "Robert Hall";
  private $fromAddress = "mtyler@4thdim.co.za";
  private $fromName = "Matthew Tyler";
  private $smtp = "smtp.test.co.za";
  private $smtpPort = 25;
  private $webProps;

  public function sendEmail() {
    if ( isset($this->fromAddress) && $this->fromAddress != "" && isset($this->toAddress) && $this->toAddress != "" ) {
      $transport = (new \Swift_SmtpTransport((string)$this->smtp, (int)$this->smtpPort))
      ->setUsername((string)$this->fromAddress)
      ->setPassword('password');
      
      $mailer = new \Swift_Mailer($transport);
      $message = (new \Swift_Message('Website status code: ' . (int)$this->webProps->code))
        ->setFrom([(string)$this->fromAddress => (string)$this->fromName])
        ->setTo([(string)$this->toAddress => (string)$this->toName])
        ->setBody("The website url '" . (string)$this->webProps->url . "' returned an unexpected status code (" . (int)$this->webProps->code . ")");
  
      return ( $mailer->send($message) ) ? " - E-mail sent to " . (string)$this->toAddress . "." : " - An error occured while sending the e-mail.";
    } else {
      return " - E-mail address missing.";
    }
  }

  public function process($props){
    $this->webProps = $props;
    return $this->sendEmail();
  }
}
?>