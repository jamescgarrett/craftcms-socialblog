<?php
namespace JcG\Instagram;

use Exception;

class Instagram
{
  private $apiBase = 'https://api.instagram.com/';
  private $apiUrl = 'https://api.instagram.com/v1/';
    
  protected $clientId;
  protected $clientSecret;
  protected $accessToken;

  public function __construct($clientId='', $clientSecret='', $accessToken = '')
  {
    if (empty($clientId) || empty($clientSecret))
    {
      throw new Exception('You may have forgotten to enter in Instagram details under settings.');
    }

    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->accessToken = $accessToken;
  }

  private function urlEncodeParams($params)
  {
    $d = '';
    if (!empty($params)):
      foreach ($params as $key => $value):
        $d .= '&'.$key.'='.urlencode($value);
      endforeach;
    endif;

    return $d;
  }

  public function http($url, $params)
  {
    if (!$this->accessToken)
    {
      throw new Exception('Can\'t locate an access token');
    }

    $url = $url . '?access_token=' . $this->accessToken . $this->urlEncodeParams($params);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
    $res = json_decode(curl_exec($curl));

    if ($res == null)
    {
      throw new Exception('Instagram Servers Might Be Down.');
    }

    if (isset($res->meta->error_type))
    {
      throw new Exception($res->meta->error_message);
    }

    return $res;
    curl_close($curl);
  }

  public function get($endpoint, $params=array())
  {
    return $this->http($this->apiUrl.$endpoint, $params);
  }
}