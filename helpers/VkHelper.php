<?php

/**
 * VKAPI class for vk.com social network
 *
 * @package server API methods
 * @link http://vk.com/developers.php
 * @autor Oleg Illarionov
 * @version 1.0
 */
 
class VkHelper {
	private $api_secret;
	private $app_id;
	private $api_url;

	function __construct($app_id, $api_secret, $api_url = 'api.vk.com/api.php'){
		$this->app_id = $app_id;
		$this->api_secret = $api_secret;
		if (!strstr($api_url, 'http://')) $api_url = 'http://'.$api_url;
		$this->api_url = $api_url;
	}

    /**
	 * Посылает запрос на сервер VK
	 *
     * @param string $method
     * @param array|null $params
     * @return array
     */
	public function api($method, array $params = null) {
		if (is_null($params)) $params = [];
		$params['api_id'] = $this->app_id;
		$params['v'] = '3.0';
		$params['method'] = $method;
		$params['timestamp'] = time();
		$params['format'] = 'json';
		$params['random'] = rand(0,10000);
		ksort($params);
		$sig = '';
		foreach($params as $k=>$v) {
			$sig .= $k.'='.$v;
		}
		$sig .= $this->api_secret;
		$params['sig'] = md5($sig);

		$response = RequestHelper::Get($this->api_url, $params);
		return $response;
	}
}

