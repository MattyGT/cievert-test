<?php
namespace App\Services;
use \Interfaces\CommunicationInterface;
use \Interfaces\CommunicationSMSInterface;

use Twilio\Rest\Client;

class CommunicationSMS {
  private $contactNum = 1234567891;
  private $webProps;

  public function sendSMS() {
    try {
      $sid = 'ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
      $token = 'your_auth_token';
      $client = new Client($sid, $token);

      $smsResp = $client->messages->create(
          $this->contactNum,
          [
              'from' => '+1234567891',
              'body' => "The website url '" . (string)$this->webProps->url . "' returned an unexpected status code (" . (int)$this->webProps->code . ")"
          ]
      );
      $msg = " - SMS sent to " . $this->contactNum . ".";
    } catch (\Services_Twilio_RestException $e) {
      $msg = " - An error occured while sending the sms.";
    }

    return $msg;
  }

  public function process($props){
    $this->webProps = $props;
    return $this->sendSMS();
  }
}
?>