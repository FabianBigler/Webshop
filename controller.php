<?php
require_once("model.php");
require_once("repository.php");
require_once("helper.php");

class ControllerFactory {
	private $controllers = array();
	
	function __construct() {
		$this->registerController(new ProductController(new ProductRepository()));
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
	
	protected function renderJsonResult($result) {
		header('Content-Type: application/json');
		echo json_encode($result);
	}
}

class ProductController extends ControllerBase {
	private $productRepository;
	
	function __construct($productRepository) {
		parent::__construct("product");
		$this->productRepository = $productRepository;
		$this->registerAction("getAll", function() { $this->getAll(); });
		$this->registerAction("get", function() { $this->get(); });
	}
	
	public function getAll() {		
		$result = $this->productRepository->GetAll(WebshopContext::GetLanguage());
		$this->renderJsonResult($result);
	}
	
	public function get()
	{
		$productId = htmlspecialchars($_GET["productId"]);
		$result = $this->productRepository->
					GetProductWithIngredients(
						WebshopContext::GetLanguage(), $productId);
		$this->renderJsonResult($result);
	}
}

$controllerFactory = new ControllerFactory();
$controllerFactory->resolveController($_GET["controller"])->invokeAction($_GET["action"]);

?>