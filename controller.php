<?php

require_once "model.php";
require_once "helper.php";
require_once "repository.php";

session_start();
register_shutdown_function("setFatalErrorResponse");

class ControllerFactory {
    private $controllers = array();
    
    function __construct() {
        $productRepo = new ProductRepository();
        $this->registerController(new ProductController($productRepo));
        $this->registerController(new UserController(new UserRepository(), new LanguageRepository()));
        $this->registerController(new BasketController(new BasketRepository(), $productRepo));
    }
    
    public function resolveController() {
        $normalizedControllerName = strtolower(getStringFromUrl("controller"));
        return $this->controllers[$normalizedControllerName];
    }
    
    private function registerController($controller) {
        $normalizedControllerName = strtolower($controller->name());
        $this->controllers[$normalizedControllerName] = $controller;
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
    
    public function invokeAction() {
        $normalizedActionName = strtolower(getStringFromUrl("action"));
        $this->actions[$normalizedActionName]();
    }
    
    protected function registerAction($actionName, $action) {
        $normalizedActionName = strtolower($actionName);
        $this->actions[$normalizedActionName] = $action;
    }
    
    protected function verifyAuthenticated() {
        if (User::isAuthenticated() === false) {
            setForbiddenResponse();
        }
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
        $products = $this->productRepository->getAll(getLangFromCookie());
        setJsonResponse($products);
    }
    
    public function get() {
        $productId = intval(getStringFromUrl("productId"));
        $currentLang = getLangFromCookie();
        
        $product = $this->productRepository->getById($productId, $currentLang);
        if ($product === null) {
            setNotFoundResponse();
        }
        else {
            $product->ingredients = $this->productRepository->getIngredients($productId, $currentLang);
            setJsonResponse($product);
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
        $this->registerAction("getBasket", function() { $this->getBasket(); });            
        $this->registerAction("addLineToBasket", function() { $this->addLineToBasket(); });
        $this->registerAction("removeLinefromBasket", function() { $this->removeLinefromBasket(); });
        $this->registerAction("completeOrder", function() { $this->completeOrder(); });
    }
    
    public function getBasket() {
        setJsonResponse(User::current()->getBasket());
    }
    
    public function addLineToBasket() {
        $this->verifyAuthenticated();
        $request = getJsonInput();
        
        $basket = User::current()->getBasket();
        $basket->addLine($request["productId"], $request["amount"], getLangFromCookie(), $this->productRepository);
    }
    
    public function removeLinefromBasket() {
        $this->verifyAuthenticated();
        $request = getJsonInput();
        
        $basket = User::current()->getBasket();
        $basket->removeLine($request["productId"]);
    }
    
    public function completeOrder() {
        $this->verifyAuthenticated();
        $basket = User::current()->getBasket();
        $basket->completeOrder($this->basketRepository);
    
        User::current()->basket = null;
        
        setJsonResponse($basket->id);
    }
}

class UserController extends ControllerBase {
    private $userRepository;
    private $languageRepository;
    
    function __construct($userRepository, $languageRepository) {
        parent::__construct("user");
        $this->userRepository = $userRepository;
        $this->languageRepository = $languageRepository;
        $this->registerAction("register", function() { $this->register(); });
        $this->registerAction("existsUser", function() { $this->existsUser(); });
        $this->registerAction("login", function() { $this->login(); });
        $this->registerAction("logout", function() { $this->logout(); });
        $this->registerAction("getCurrent", function() { $this->getCurrent(); });
        $this->registerAction("languages", function() { $this->languages(); });
    }

    public function register() {
        $user = new User();
        $user->applyValuesFromArray(getJsonInput());
        
        if ($this->userRepository->existsByEmail($user->email)) {
            setErrorResponse('User already exists.');
        }
        else {
            $this->userRepository->insert($user);
        }
    }
    
    public function existsUser() {
        $userExists = $this->userRepository->existsByEmail(getStringFromUrl("email"));
        setJsonResponse($userExists);
    }
    
    public function login() {
        $credentials = getJsonInput();
        $success = User::login($this->userRepository, $credentials["email"], $credentials["password"]);
        setJsonResponse($success);
    }
    
    public function logout() {
        $this->verifyAuthenticated();
        User::logout();
    }
    
    public function getCurrent() {
        $this->verifyAuthenticated();
        setJsonResponse(User::current());
    }
        
    public function languages() {
        $result = $this->languageRepository->getAll();
        setJsonResponse($result);
    }
}

$controllerFactory = new ControllerFactory();
$controllerFactory->resolveController()->invokeAction();

?>