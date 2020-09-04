<?php
namespace App\Services;
use App\Services\WebsiteCheck;

class Communication extends WebsiteCheck {
  public function sendMessages($commTypes) {
    foreach ( $commTypes as $commType ) {
      $resp[] = $this->sendMessage($commType,$this);
    }
    return $resp;
  }

  public function sendMessage($commType,$props) {
    return $commType->process($props);
  }
}
?>