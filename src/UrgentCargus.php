<?php 
namespace BTeodorWork;

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
		    'base_url' => self::API_URL,
		    'timeout'  => 2.0,
		    'connect_timeout' => 3.0
		]);

		$this->_getHeaders($api_subscription_key);
	}

	public function login($user, $pass)
	{
		$this->callMethod('LoginUser', 'POST', array('UserName' => $user, 'Password' => $pass));

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
			$request = $this->client->createRequest($requestType, $method, array('json' => $params, 'headers' => $this->headers));
			$this->response = $this->client->send($request);
		}
		catch(Exception $e) {
			// var_dump($e->getMessage());
		}
		
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function getResponseBody($json = true)
	{
		if($json):
			return json_decode($this->response->getBody());
		else:
			return $this->response->getBody()->getContents();
		endif;
	}

	private function _getHeaders($api_subscription_key)
	{
		$this->headers['Ocp-Apim-Subscription-Key'] = $api_subscription_key;
		$this->headers['Ocp-Apim-Trace'] = true;
	}
}