<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

require_once(__DIR__.'/../RPC.php');
require_once(__DIR__.'/../Storage.php');

$api = new API();

$api->handleRequest();

class API {
	private $_xcoinRPC = null;
	private $_storage = null;

	public function __construct() {
		$this->_xcoinRPC = new JsonRpcClient('http://user:password@127.0.0.1:17786');
		$this->_storage = new Storage(['host'=>'localhost', 'user'=>'root', 'password'=>'6$54IU65UIfdghFDG', 'dbname'=>'new_minexexplorer']);
	}



	/**
	 * Handle request.
	 */
	public function handleRequest() {
		if ($this->get('r') == null || !is_string($this->get('r')))
			return $this->sendError('Request not specified');

		switch ($this->get('r')) {
			case 'getaddressbalance' :
				return $this->getAddressBalance();
				break;
			case 'getblockcount' :
				return $this->getBlockCount();
				break;
			case 'getrichlist' :
                                return $this->getRichlist();
                                break;
			default :
				return $this->sendError('Requested method not found');
		}
	}

	public function getRichlist() {
		$addresses = $this->_storage->getAddresses();

		$riches = 0;
		foreach ($addresses as $address) {
			$balance = $this->_storage->getAddressBalance($address);
			if ($balance > 0)
				$riches++;
		}

		return $this->sendSuccess(['amount'=>$riches]);
	}



	/**
	 * Get parameter from POST.
	 */
	public function get($name) {
		if (!isset($_GET[$name]) || !$_GET[$name])
			return null;
		return $_GET[$name];
	}



	/**
	 * Handle RPC response.
	 *
	 * @param mixed $response
	 * @return array
	 */
	public function handleRPC($response) {
		if (!$response)
			return $this->sendError('RPC fault');

		return $response;
	}



	/**
	 * Get balance of address.
	 */
	public function getAddressBalance() {
		$address = $this->get('address');

		if (!$address || preg_match("/[^A-Za-z0-9]/", $address))
			return $this->sendError('Incorrect address provided');

		try {
			$validationData = $this->handleRPC($this->_xcoinRPC->validateaddress($address));
		} catch (Exception $e) {
			return $this->sendError('RPC error');
		}

		if ($validationData['isvalid'] == false)
			return $this->sendError('Address isn\'t valid');

		$balance = $this->_storage->getAddressBalance($address);

		return $this->sendSuccess($balance);
	}


	/**
	 * Get amount of all blocks.
	 */
	public function getBlockCount() {
		return $this->sendSuccess($this->handleRPC($this->_xcoinRPC->getblockcount()));
	}



	public function sendJSON($json, $code = 200) {
		header('Content-Type: application/json; charset=utf-8');
		http_response_code($code);
		echo $json;
		exit;
	}

	public function formErrorStatus($error = null) {
		$response = ['status'=>0];
		if ($error !== null) $response['error'] = $error;
		return $response;
	}

	public function formSuccessStatus($data = null) {
		$response = ['status'=>1];
		if ($data !== null) $response['data'] = $data;
		return $response;
	}

	public function sendError($error, $code = 200) {
		return $this->sendJSON(json_encode($this->formErrorStatus($error)), $code);
	}

	public function sendSuccess($data, $code = 200) {
		return $this->sendJSON(json_encode($this->formSuccessStatus($data)), $code);
	}
}
