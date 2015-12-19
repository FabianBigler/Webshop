<?php
public class ProductController {
	public function getAll() {
		echo 'ALL products';
	}
}

$controllers = array(
	"product" => new ProductController()
);

$controller = $controllers[$_GET["controller"]];
$controller->$$_GET["action"]();

?>