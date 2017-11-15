<?php

require_once(__DIR__.'/../components/Storage.php');

class Controller {
	protected $_storage = null;
	protected $_controllerName = '';
	public $pageTitle = 'Minexcoin Explorer';

	public function __construct($name, $config) {
		$this->_storage = new Storage($config['db']);
		$this->_controllerName = $name;
	}

	public function render($view, $params = null, $returnValue = false) {
		if (!file_exists(__DIR__.'/../views/'.$this->_controllerName.'/'.$view.'.php'))
			throw new Exception('View file "'.$view.'" not found');

		if ($params != null)
			extract($params);

		ob_start();
		include(__DIR__.'/../views/'.$this->_controllerName.'/'.$view.'.php');
		$content = ob_get_contents();
		ob_end_clean();

		if ($returnValue)
			return $content;

		echo $content;
	}

	public function __call($methodName, $params) {
		throw new Exception('Action "'.$methodName.'" not found');
	}



	/**
	 * Redirect.
	 */
	public function redirect($url = '/') {
		header('Location: '.$url);
		exit;
	}
}
