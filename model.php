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
    
	public $email;
	public $street;
	public $postCode;
	public $city;
	public $role;
	public $password;
	public $salt;
	
	public function setPassword($password) {
		$this->salt = bin2hex(openssl_random_pseudo_bytes(8));
		$this->password = $this->getHash($password);
	}
	
	public function getHash($password) {
		return hash("sha256", $password . $this->salt);
	}
    
    public function applyValuesFromArray($newValues) {
        $this->email = $newValues["email"];			
        $this->street = $newValues["street"];
        $this->postCode = $newValues["postCode"];
        $this->city = $newValues["city"];
        $this->setPassword($newValues["password"]);	
    }
}

class Ingredient extends EntityBase {
	public $name;	
}

?>