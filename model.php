<?php
class EntityBase
{
	public $id;
}

class Product extends EntityBase
{
    public $name;
    public $price;
    public $imgSmallPath;
	public $description;
    public $shortDescription;
	public $ingredients;
}

class Basket extends EntityBase
{
	public $deliveryStreet;
	public $deliveryPostCode;
	public $deliveryCity;
	public $invoiceStreet;
	public $invoicePostCode;
	public $invoiceCity;
}

class BasketItem extends EntityBase
{
	public $productId;
	public $productPrice;
	public $amount;
}

class User extends EntityBase
{
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
		return hash(’W00TThIsIsRANDOM78’, $password + $this->salt);
	}
}

class Ingredient extends EntityBase
{
	public $name;	
}
 ?>