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
	
	public function addItemToBasket()
	{
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
	
	public function removeItemfromBasket()
	{
		//$basket = getBasket();
		//$request = $this->getJsonInput();			
		//unset($x[0]);
	}
	
	public function completeOrder()
	{
		$basket = $this->basket();
		//persist basket, unset session basket
		$basketRepository.CompleteOrder($basket);
		$_SESSION["basket"] = null;
	}
	
	public function basket() {
		if (!isset($_SESSION["basket"])) {
			$basket = new Basket();
			//TODO @BENI: User aus  Session abholen!
			$user = new User();
			$basket->userId = $user->userId;
			$basket->deliveryStreet = $user->street;
			$basket->deliveryPostCode = $user->postCode;
			$basket->deliveryCity = $user->city;
			$basket->invoiceStreet = $user->street;
			$basket->invoicePostCode = $user->postCode;
			$basket->invoiceCity = $user->city;		
								
			$_SESSION["basket"] = $basket;			
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