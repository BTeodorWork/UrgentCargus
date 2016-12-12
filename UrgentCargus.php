<?php 
namespace BTeodorWork\UrgentCargus;

use GuzzleHttp\Client;

class UrgentCargus {
	
	CONST API_URL = 'https://urgentcargus.azure-api.net/api/';

	private $client = null;
	private $response = null;
	private $token = null;
	private $headers = [];

	public function __construct($api_subscription_key)
	{
		$this->client = new Client([
		    'base_uri' => self::API_URL,
		    'timeout'  => 2.0,
		    'connect_timeout' => 3.0
		]);

		$this->_getHeaders($api_subscription_key);
	}

	public function login($user, $pass)
	{
		$this->callMethod('LoginUser', 'POST', ['UserName' => $user, 'Password' => $pass]);

		if($this->response):
			$this->token = json_decode($this->response->getBody());
			if($this->token):
				$this->headers['Authorization'] = 'Bearer '.$this->token;
			endif;
		endif;
	}
	
	public function callMethod($method, $requestType, $params = [])
	{

		try {
			$this->response = $this->client->request($requestType, $method, ['json' => $params, 'headers' => $this->headers]);

			var_dump($this->response->getBody()->getContents());
		}
		catch(Exception $e) {
			var_dump($e->getMessage());
		}
		
	}

	private function _getHeaders($api_subscription_key)
	{
		$this->headers['Ocp-Apim-Subscription-Key'] = $api_subscription_key;
		$this->headers['Ocp-Apim-Trace'] = true;
	}
}