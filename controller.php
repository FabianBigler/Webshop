<?php

require_once("model.php");
require_once("repository.php");
require_once("helper.php");

session_start();

class ControllerFactory {
	private $controllers = array();
	
	function __construct() {
		$this->registerController(new ProductController(new ProductRepository()));
		$this->registerController(new UserController(new UserRepository()));
		$this->registerController(new BasketController(new BasketRepository(), new ProductRepository()));
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
    
    protected function verifyAuthenticated() {
        if (User::isAuthenticated() === false) {
            throw new Exception("Not authorized");
        }
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
	
	public function get() {
		$productId = htmlspecialchars($_GET["productId"]);
		$result = $this->productRepository->getProductWithIngredients(WebshopContext::getLanguage(), $productId);
		$this->renderJsonResult($result);
	}
}

class UserController extends ControllerBase {
	private $userRepository;
    
    function __construct($userRepository) {
        parent::__construct("user");
        $this->userRepository = $userRepository;
        $this->registerAction("register", function() { $this->register(); });
        $this->registerAction("existsUser", function() { $this->existsUser(); });
        $this->registerAction("login", function() { $this->login(); });
        $this->registerAction("logout", function() { $this->logout(); });
        $this->registerAction("getCurrentUser", function() { $this->getCurrentUser(); });
    }

    public function register() {
        $user = new User();
        $user->applyValuesFromArray($this->getJsonInput());
        if ($this->userRepository->existsUserByEmail($user->email)) {
            throw new Exception('User already exists.');
        }
        
        $this->userRepository->addUser($user);
    }
    
    public function existsUser() {
        $userExists = $this->userRepository->existsUserByEmail($_GET["email"]);
        
        $this->renderJsonResult($userExists);
    }
    
    public function login()	{
        $request = $this->getJsonInput();
        $success = User::login($this->userRepository, $request["email"], $request["password"]);
        
        $this->renderJsonResult($success);
    }
    
    public function logout() {
        User::logout();
    }
    
    public function getCurrentUser() {
        $this->renderJsonResult(User::current());
    }
}

class BasketController extends ControllerBase {	
	private $basketRepository;
	private $productRepository;
    
    function __construct($basketRepository, $productRepository) {
        parent::__construct("basket");
        $this->basketRepository = $basketRepository;
		$this->productRepository = $productRepository;	
        $this->registerAction("addItemToBasket", function() { $this->addItemToBasket(); });
        $this->registerAction("removeItemfromBasket", function() { $this->removeItemfromBasket(); });
		$this->registerAction("completeOrder", function() { $this->completeOrder(); });
		$this->registerAction("getBasket", function() { $this->getBasket(); });			
    }
	
	public function addItemToBasket() {
        $this->verifyAuthenticated();
        
		$basket = $this->basket();
		$request = $this->getJsonInput();		
		$found = false;
		foreach ($basket->lines as $line) {
			if($line->productId == $request["productId"])
			{
				$found = true;
				$line->amount += $request["amount"];
			}
		}
		
		if(!$found) {
			$product = $this->productRepository->getProduct(WebshopContext::GetLanguage(), $request["productId"]);
			$basketLine = new BasketLine();
			$basketLine->productId = $request["productId"];
			$basketLine->amount = $request["amount"];
			$basketLine->productPrice = $product->price;		
			$basketLine->productName = $product->name;
			$basket->lines[] = $basketLine;
		}
	}
	
	public function removeItemfromBasket() {
        $this->verifyAuthenticated();
        
		//$basket = getBasket();
		//$request = $this->getJsonInput();			
		//unset($x[0]);
	}
	
	public function completeOrder() {
        $this->verifyAuthenticated();
        
		$basket = $this->basket();
		//persist basket, unset session basket
		$basketRepository.CompleteOrder($basket);
		$_SESSION["basket"] = null;
	}
	
	public function basket() {
		if (!isset($_SESSION["basket"])) {
			$_SESSION["basket"] = new Basket(User::current());;			
		}
        
		return $_SESSION["basket"];		
	}
	
	public function getBasket() {
		$this->renderJsonResult($this->basket());
	}
}

$controllerFactory = new ControllerFactory();
$controllerFactory->resolveController($_GET["controller"])->invokeAction($_GET["action"]);

?>