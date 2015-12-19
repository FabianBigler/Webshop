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
					getProductWithIngredients(
						WebshopContext::getLanguage(), $productId);
		$this->renderJsonResult($result);
	}
}

class UserController extends ControllerBase {
	private $userRepository;
	
		function __construct($userRepository) {
			parent::__construct("user");
			$this->userRepository = $userRepository;
			$this->registerAction("register", function() { $this->register(); });
			$this->registerAction("login", function() { $this->login(); });
		}
	
		public function register()
		{
			$user = new User();
			$user->email = $_POST["email"];			
			$user->role = 2; //default role: customer^
			$user->street = $_POST["street"];
			$user->postCode = $_POST["postCode"];
			$user->city = $_POST["city"];
			$user->setPassword($_POST["password"]);			
			$userRepository.insertUser($email, $role, $password, $salt, $street, $postCode, $city)
		}
		
		public function login()
		{
			$email = $_POST["email"];
			$pwd = $_POST["password"];			
			$user = $userRepository.getUserByEmail($email);						
			if(ISSET($user)) {				
				if ($user->password === $user->getHash($pwd))
				{
					$this->renderJsonResult(true);
				} else {
					$this->renderJsonResult(false);
				}				
			} else {
				$this->renderJsonResult(false);
			}
		}
}

$controllerFactory = new ControllerFactory();
$controllerFactory->resolveController($_GET["controller"])->invokeAction($_GET["action"]);

?>