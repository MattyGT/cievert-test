<?php
namespace App\Services;
use \Interfaces\WebsiteInterface;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteCheck {
  public $url = '';
  public $type = '';
  public $connection;
  public $code = '';
  
  public function __construct($url = "") {
    $this->url = $url;
    $this->connectURL();
  }
  
  private function connectURL() {
    $this->connection = new Client([
      'timeout'  => 30.0,
    ]);
  }
  
  public function getCode() {
    try {
      $response = $this->connection->request('GET', $this->url);
      $this->code = (int)$response->getStatusCode();
    } catch (ClientException $e) {
      $this->code = (int)$e->getCode();
    }
    return $this->code;
  }

  public function getTitle() {
    try {
      $response = $this->connection->request('GET', $this->url);
      $responseBody = (string)$response->getBody(true);
    } catch (ClientException $e) {
      $responseBody = (string)$e->getMessage();
    }
    $bodyText = new Crawler($responseBody);
    $this->title = $bodyText->filterXPath('//title')->text();

    return $this->title;
  }
}
?>