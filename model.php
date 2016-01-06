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

class Basket extends EntityBase {
	public $userId;
	public $deliveryStreet;
	public $deliveryPostCode;
	public $deliveryCity;
	public $invoiceStreet;
	public $invoicePostCode;
	public $invoiceCity;
	
	public $lines =array();
}

class BasketLine extends EntityBase {
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
	
    public static function current() {
        if (isset($_SESSION["currentUser"])) {
            return $_SESSION["currentUser"];
        }
        else {
            return null;
        }
    }
    
    public static function login($userRepository, $email, $password) {
        $user = $userRepository->getUserByEmail($email);	
        
        if (isset($user) && $user->isPasswordValid($password)) {	
            unset($user->salt);
            unset($user->password);
            $_SESSION["currentUser"] = $user;
            
            return true;
        }
        else {
            return true;
        }
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
        return $this->password === $user->getHash($password);
    }
	
	private function getHash($password) {
		return hash("sha256", $password . $this->salt);
	}
}

class Ingredient extends EntityBase {
	public $name;	
}

?>