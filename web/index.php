<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Kiev');

define('WEB_ROOT', __DIR__);
define('APP_ROOT', WEB_ROOT.'/..');

$config = [];
$config['db'] = require_once(__DIR__.'/../config/db.php');

$explorer = new Explorer();
try {
	$explorer->run($config);
} catch (Exception $e) {
	die('Error: '.$e->getMessage());
}

class Explorer {
	private $_defaultController = 'Explorer';
	private $_defaultAction = 'index';

	public function run($config) {
		$r = isset($_GET['r']) ? $_GET['r'] : $this->_defaultController.'/'.$this->_defaultAction;

		$path = explode('/', $r);
		if (count($path) == 0) {
			$controller = $this->_defaultController;
			$action = $this->_defaultAction;
		} elseif (count($path) == 1) {
			$controller = $path[0];
			$action = $this->_defaultAction;
		} else {
			$controller = $path[0];
			$action = $path[1];
		}

		$controllerName = strtolower($controller);
		$controller = ucfirst(strtolower($controller)).'Controller';

		$controllerPath = __DIR__.'/../controllers/'.$controller.'.php';
		if (!file_exists($controllerPath))
			throw new Exception('Controller file not found');

		require_once($controllerPath);

		if (!class_exists($controller))
				throw new Exception('Controller not found');

		$action = 'action'.ucfirst($action);
		if (!method_exists($controller, $action))
			throw new Exception('Action not found');

		$controller = new $controller($controllerName, $config);
		$respose = call_user_func(array($controller, $action));
	}
}
