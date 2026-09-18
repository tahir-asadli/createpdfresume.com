<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

/**
 * @pluginName Epoint woocommerce payment gateway
 * @pluginUrl https://epoint.az/
 * @varion 1.0.0
 * @author Rauf ABBASZADE <rafo.abbas@gmail.com>
 * @authorURI: https://abbasazade.dev/
 */

class EpointClient
{

  /**
   * Epoint signature
   * @var string
   */
  public $signature = '';

  public function __construct(
    protected $publicKey = null,
    protected $privateKey = null,
    protected $baseUrl = null,

  ) {
  }

  public function request($url, $data = [])
  {
    $POSTFIELDS = http_build_query($data);
    $_ch = curl_init();
    curl_setopt($_ch, CURLOPT_URL, $this->baseUrl . $url);
    curl_setopt($_ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
    curl_setopt($_ch, CURLOPT_RETURNTRANSFER, TRUE);
    return curl_exec($_ch);
  }

  public function payload($payload)
  {
    $data = base64_encode(json_encode($payload));

    $this->generateSignature($payload);
    return [
      'data' => $data,
      'signature' => $this->getSignature(),
    ];
  }

  public function generateSignature($payload)
  {
    $data = base64_encode(json_encode($payload));
    $this->setSignature(base64_encode(sha1($this->getPrivateKey() . $data . $this->getPrivateKey(), 1)));
  }

  public function getSignature()
  {
    return $this->signature;
  }

  public function setSignature($signature)
  {
    $this->signature = $signature;
    return $this;
  }

  protected function getPrivateKey()
  {
    return $this->privateKey;
  }

  protected function getPublicKey()
  {
    return $this->publicKey;
  }

}