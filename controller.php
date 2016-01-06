<?php
require_once("model.php");
require_once("repository.php");
require_once("helper.php");

class ControllerFactory {
	private $controllers = array();
	
	function __construct() {
		$this->registerController(new ProductController(new ProductRepository()));
		$this->registerController(new UserController(new UserRepository()));
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
    
    protected function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
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

    public function register() {
        $user = new User();
        $user->applyValuesFromArray($this->getJsonInput());
        $this->userRepository->addUser($user);
    }
    
    public function login()	{
        $request = $this->getJsonInput();
        $user = $this->userRepository->getUserByEmail($request["email"]);	
        					
        if (isset($user) && $user->password === $user->getHash($request["password"])) {	
            $this->renderJsonResult(true);
        }
        else {
            $this->renderJsonResult(false);
        }
    }
}

$controllerFactory = new ControllerFactory();
$controllerFactory->resolveController($_GET["controller"])->invokeAction($_GET["action"]);

?>