<?php
include_once 'database.php';
include_once 'model.php';

class RepositoryBase
{
	protected $conn;
	protected function initConnection()
	{
		$db = new Database();
		$this->conn = $db->getConnection();
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		} 				
	}
}

class BasketRepository extends RepositoryBase
{	
	public function AddLine($userid, $productId)
	{
		
	}
	
	public function GetOrCreate($userId)
	{
		
	}
}

class ProductRepository extends RepositoryBase
{		
	public function GetAll($language)
	{
		return $this->getProducts($language);
	}
	
	public function GetProductWithIngredients($language, $id)
	{
		$this->getProducts($language, $id);
	}	
	
	private function getProducts($language, $id = NULL)
	{
		$this->initConnection();
		$sql = "SELECT `product`.`id`, `price`, `imgSmallPath`, name, description, 
		`short-description` FROM `product` LEFT JOIN `productText` 
        ON (product.id=`productText`.`product-id` 
		AND `language-code`='" . htmlspecialchars($language) . "')";
		if(isset($id)) {
			$sql = $sql . ' AND `product`.`id` =" '. $id .'"' ;
		}
		
		$stmt = $this->conn->query($sql);		
		if (!stmt)
		$products = array();
		while($row = $stmt->fetch_assoc())
		{
			$product = new Product();
			$product->id = $row["id"];
			$product->name = utf8_encode($row["name"]);
			$product->price = $row["price"];
			$product->imgSmallPath = $row["imgSmallPath"];
			$product->description = utf8_encode($row["description"]);
			$product->shortDescription = utf8_encode($row["short-description"]);
			$products[] = $product;			
		}		
		return $products;
		//echo json_encode($products);		
	}
}
?>
