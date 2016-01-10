<?php

class EntityBase {
    public $id;
}

class Product extends EntityBase {
    public $name;
    public $price;
    public $imgSmallPath;
    public $description;
    public $shortDescription;
    public $ingredients;
}

class Ingredient extends EntityBase {
    public $name;
}

class Basket extends EntityBase {
    function __construct($user) {
        if (isset($user)) {
            $this->userId = $user->id;
            $this->deliveryStreet = $user->street;
            $this->deliveryPostCode = $user->postCode;
            $this->deliveryCity = $user->city;
            $this->invoiceStreet = $user->street;
            $this->invoicePostCode = $user->postCode;
            $this->invoiceCity = $user->city;
        }
    }
    
    public $userId;
    public $deliveryStreet;
    public $deliveryPostCode;
    public $deliveryCity;
    public $invoiceStreet;
    public $invoicePostCode;
    public $invoiceCity;
    public $lines = array();
    
    public function addLine($productId, $amount, $language, $productRepository) {
        $found = false;
        foreach ($this->lines as $line) {
            if($line->productId === $productId) {
                $found = true;
                $line->amount += $amount;
            }
        }

        if($found === false) {
            $product = $productRepository->getById($productId, $language);
            $this->lines[] = new BasketLine($product, $amount);
        }
    }
    
    public function removeLine($productId) {
        $condition = function($line) use ($productId) { 
            return $line->productId !== $productId; 
        };
        
        $this->lines = array_values(array_filter($this->lines, $condition));
    }
    
    public function completeOrder($basketRepository) {
        $this->id = $basketRepository->insertHeader($this);
        
        foreach ($this->lines as $line) { 
            $line->id = $basketRepository->insertLine($this->id, $line->productId, $line->productPrice, $line->amount);
        }
    }
}

class BasketLine extends EntityBase {
    function __construct($product, $amount) {
        if (isset($product) && isset($amount)) {
            $this->productId = $product->id;
            $this->productPrice = $product->price;        
            $this->productName = $product->name;
            $this->amount = $amount;
        }
    }
    
    public $productId;
    public $productName;
    public $productPrice;
    public $amount;
}

class User extends EntityBase {
    function __construct() {
        // 1 = superuser
        // 2 = customer
        $this->role = 2;
    }
    
    public $givenname;
    public $surname;
    public $email;
    public $street;
    public $postCode;
    public $city;
    public $role;
    public $password;
    public $salt;
    public $basket;
    
    public function getBasket() {
        if ($this->basket === null) {
            $this->basket = new Basket($this);
        }
        
        return $this->basket;
    }
    
    public static function current() {
        if (isset($_SESSION["currentUser"])) {
            return $_SESSION["currentUser"];
        }
        else {
            return null;
        }
    }
    
    public static function isAuthenticated() {
        return User::current() !== null;
    }
    
    public static function login($userRepository, $email, $password) {
        $user = $userRepository->getByEmail($email);
        
        if (isset($user) && $user->isPasswordValid($password)) {
            unset($user->salt);
            unset($user->password);
            $_SESSION["currentUser"] = $user;
            
            return true;
        }
        else {
            return false;
        }
    }
    
    public static function logout() {
        $_SESSION["currentUser"] = null;
    }
    
    public function setPassword($password) {
        $this->salt = bin2hex(openssl_random_pseudo_bytes(8));
        $this->password = $this->getHash($password);
    }
    
    public function applyValuesFromArray($newValues) {
        $this->givenname = $newValues["givenname"];
        $this->surname = $newValues["surname"];
        $this->email = $newValues["email"];
        $this->street = $newValues["street"];
        $this->postCode = $newValues["postCode"];
        $this->city = $newValues["city"];
        $this->setPassword($newValues["password"]);
    }
    
    private function isPasswordValid($password) {
        return $this->password === $this->getHash($password);
    }
    
    private function getHash($password) {
        return hash("sha256", $password . $this->salt);
    }
}

class Language {
    public $code;
    public $name;
}

?>