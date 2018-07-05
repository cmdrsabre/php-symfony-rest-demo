<?php
// src/Service/FixerIOService.php

namespace App\Service;

class FixerIOService
{
    protected $error = null;

    private $_apiKey;
    private $_httpClient;

    private $_API_URL = "http://data.fixer.io/api";

    public function __construct($apiKey)
    {
        $this->_apiKey      = $apiKey;
        $this->_httpClient  = new \GuzzleHttp\Client();
    }

    public function getErrorCode()
    {
        return ($this->error != null ? $this->error->code : 0);
    }

    public function getErrorMessage()
    {
        return ($this->error != null ? $this->error->type : '');
    }

    public function symbols()
    {
        $params = array(
            'access_key'    => $this->_apiKey
        );
        $query = http_build_query($params);

        $requestURL = $this->_API_URL .                      
                      "/symbols?".
                      $query;
                        
        $res = $this->_httpClient->request('GET', $requestURL);
        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody());
            if ($data->success) 
            {                
                return array_flip((array) $data->symbols);
            }
        }
        return array("EUR","USD");
    }

    public function convert( $baseCurrency, $targetCurreny, $baseAmount)
    {
        $amount = 0;
        $this->error = null;

        $params = array(
            'access_key'    => $this->_apiKey,
            'base'          => $baseCurrency,
            'symbols'            => $targetCurreny
        );
        $query = http_build_query($params);

        $requestURL = $this->_API_URL .                      
                      "/latest?".
                      $query;
                   
        
        $res = $this->_httpClient->request('GET', $requestURL);
        if ($res->getStatusCode() == 200)
        {
            $data = json_decode($res->getBody());
            if ($data->success)
            {
                $amount = $baseAmount * $data->rates->{$targetCurreny};
            }
            else
            {
                $this->error = $data->error;
            }
        }
        return $amount;
    }

    public function debug()
    {
        return $this->_apiKey;
    }
}