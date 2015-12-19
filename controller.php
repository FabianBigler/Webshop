<?php
class ControllerFactory {
	private $controllers = array();
	
	function __construct() {
		$this->registerController(new ProductController());
	}
	
	public function resolveController($controllerName) {
		return $this->controllers[$controllerName];
	}
	
	private function registerController($controller) {
		$this->controllers[$controller->name()] = $controller;
	}
}

class ControllerBase {
	private $actions = array();
	private $controllerName;
	
	function __construct($controllerName) {
		$this->controllerName = $controllerName;
	}
	
	public function name() {
		return $this->controllerName;
	}
	
	public function invokeAction($actionName) {
		$this->actions[$actionName]();
	}
	
	protected function registerAction($actionName, $action) {
		$this->actions[$actionName] = $action;
	}
}

class ProductController extends ControllerBase {
	function __construct() {
		parent::__construct("product");
		$this->registerAction("getAll", function() { $this->getAll(); });
	}
	
	public function getAll() {
		echo 'ALL products';
	}
}

$controllerFactory = new ControllerFactory();
$controllerFactory->resolveController($_GET["controller"])->invokeAction($_GET["action"]);

?>