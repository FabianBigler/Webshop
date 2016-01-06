<?php

include_once 'database.php';
include_once 'model.php';

class RepositoryBase {
	protected $conn;
	protected function initConnection()	{
		$db = new Database();
		$this->conn = $db->getConnection();
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		} 				
	}
}

class BasketRepository extends RepositoryBase
{	
	public function addLine($headerId, $productId, $amount)
	{
		$this->initConnection();
		$sql = "INSERT INTO `basketLine`(`headerId`, `productId`, `productPrice`, `amount`) 
				VALUES (?,?,SELECT price FROM product WHERE product.id = productId,?)";						
		$stmt = $this->conn->prepare($sql);		
		$stmt->bind_param('iid', $headerId, $productId, $amount);
		
		if($stmt === false) {
		  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
		}		
		$stmt->execute();
	}
	
	public function getOrCreate($userId)
	{
		$this->initConnection();
		if($this->get($userId) == null)
		{
			//create a new basket!
			
		} else {
			
		}
		//INSERT INTO `basketHeader`(`id`, `userId`, `deliveryStreet`, `deliveryPostCode`, `deliveryCity`, `invoiceStreet`, `invoicePostCode`, `invoiceCity`) 
		//VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8])
	}
	
	public function completeOrder($basket)
	{
		if(count($basket->items) == 0)
		{
			trigger_error('No Basketlines in Basket!');
		}
		
		$this->initConnection();
		
		
	}
	
	
	
	//not fully functional!
	private function get($userId)
	{
		$this->initConnection();
		$sql = "SELECT `id`, `userId`, `deliveryStreet`, `deliveryPostCode`, `deliveryCity`, `invoiceStreet`, 
				`invoicePostCode`, `invoiceCity` WHERE userID = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param('i', $userId);
		if($stmt === false)
		{
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
		}
				$stmt->execute();		
		$stmt->bind_result(	$row_id, 
							$row_userid, 
							$row_deliveryStreet, 
							$row_deliveryPostCode, 
							$row_deliveryCity, 
							$row_invoiceStreet, 
							$row_invoicePostCode, 
							$row_invoiceCity);
		
		if($stmt->fetch())
		{
			$basket = new Basket();
			$basket->id = $row_id;
			$basket->userId = $row_userid;
			$basket->deliveryStreet = $row_deliveryStreet;
			$basket->deliveryPostCode = $row_deliveryPostCode;
			$basket->deliveryCity = $row_deliveryCity;
			$basket->invoiceStreet = $row_invoiceStreet;
			$basket->invoicePostCode = $row_invoicePostCode;
			$basket->invoiceCity = $row_invoiceCity;	
			return $basket;
		}
		return null;	
	}
}

class UserRepository extends RepositoryBase
{
	public function getUserByEmail($email)
	{
		$this->initConnection();
		$sql = "SELECT `id`, `email`, `role`, `password`, `salt`, `street`, `postCode`, `city` FROM `user` WHERE `email`=?";		
		$stmt = $this->conn->prepare($sql);		
		$stmt->bind_param('s', $email);		
		if($stmt === false) {
		  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
		}
		
		$stmt->execute();		
		$stmt->bind_result($row_id, $row_email, $row_role, $row_pw, $row_salt, $row_street, $row_postCode, $row_city);
		
		if($stmt->fetch())
		{
			$user = new User();
			$user->id = $row_id;
			$user->email = $row_email;
			$user->street = $row_street;
			$user->postCode = $row_postCode;
			$user->city = $row_city;
			$user->role = $row_role;
			$user->password = $row_pw;
			$user->salt = $row_salt;	
			return $user;
		}
		return null;		
	}
	

	public function addUser($user) {
		$this->initConnection();
		$sql = "INSERT INTO `user` (`email`, `role`, `password`, `salt`, `street`, `postCode`, `city`) VALUES (?,?,?,?,?,?,?)";
		$stmt = $this->conn->prepare($sql) or die($this->conn->error);      		
		$stmt->bind_param('sisssss', $user->email, $user->role, $user->password, $user->salt, $user->street, $user->postCode, $user->city);
		$stmt->execute();				
	}
}

class ProductRepository extends RepositoryBase
{		
	public function getAll($language)
	{
		return $this->getProducts($language);
	}
	
	public function getProductWithIngredients($language, $id)
	{		
		$products = $this->getProducts($language, $id);
		if(count($products) == 1)
		{
			$product = $products[0];
			$product->ingredients = $this->getIngredients($language, $id);		
			return $product;
		}
		return null;
	}	
	
	public function getProduct($language, $id)
	{
		$products = $this->getProducts($language, $id);
		$product = $products[0];
		if(count($products) == 1)
		{
			$product = $products[0];
			return $product;
		}
	}
	
	private function getProducts($language, $id = NULL)
	{		
		$this->initConnection();
		$sql = "SELECT product.id, name, price, imgSmallPath, description, 
		`short-description` FROM `product` INNER JOIN `productText` 
        ON (product.id=`productText`.`product-id` 
		AND `language-code`=?";
		
		if(isset($id)) {
			$sql = $sql . ' AND `product`.`id`=?)';
		} else {
			$sql = $sql . ')';
		}
		$stmt = $this->conn->prepare($sql);		
		
		
		if(isset($id)) {
			$stmt->bind_param('ss', htmlspecialchars($language), intval($id));
		} else {
			$stmt->bind_param('s', htmlspecialchars($language));
		}
		
		if($stmt === false) {
		  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
		}
		
		$stmt->execute();		
		$stmt->bind_result($row_id, $row_name, $row_price, $row_img, $row_description, $row_shortDescription);
		while($stmt->fetch())
		{
			//if(isset($row_name))
			//{				
				$product = new Product();
				$product->id = $row_id;
				$product->name = utf8_encode($row_name);
				$product->price = $row_price;
				$product->imgSmallPath = $row_img;
				$product->description = utf8_encode($row_description);
				$product->shortDescription = utf8_encode($row_shortDescription);
				$products[] = $product;
			//}
		}
		return $products;
		//echo json_encode($products);		
	}
	
	private function getIngredients($language, $productId)
	{
		$this->initConnection();		
		$sql = "SELECT ingredient.id, ingredient.name FROM `productIngredient` 
				INNER JOIN ingredient ON (ingredient.id=`productIngredient`.`ingredient-id` 
				AND ingredient.`language-code`=? AND `productIngredient`.`product-id`=?) ORDER BY `productIngredient`.`position`";
		//echo $sql;
		$stmt = $this->conn->prepare($sql);
		if($stmt === false) {
		  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
		}
		//echo 'id:' . $productId;
		//echo 'lang:' .$language;
		$stmt->bind_param('si', htmlspecialchars($language), intval($productId));
		$stmt->execute();		
		$stmt->bind_result($row_id, $row_name);
		while($stmt->fetch())
		{
			$ingredient = new Ingredient();
			$ingredient->id = $row_id;
			$ingredient->name = utf8_encode($row_name);
			$ingredients[] = $ingredient;
		}	
		return $ingredients;		
	}
}
?>
