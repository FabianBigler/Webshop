<?php

require_once "helper.php";
require_once "model.php";

class RepositoryBase {
    protected function query($sql, $action) {
        $con = getDbConnection();
        $stmt = $con->prepare($sql) or die($con->error);
        
        $result;
        $ex;
        try {
            $result = $action($stmt, $con);
            
            if ($stmt->error !== null && $stmt->error !== "") {
                throw new Exception($stmt->error);
            }
        }
        catch (Exception $e) {
            $ex = $e;
        }
        
        $stmt->close();
        $con->close();
        
        // try,finally
        if (isset($ex)) {
            throw $ex;
        }
    
        return $result;
    }
    
    protected function fetchScalar($stmt) {
        $stmt->bind_result($value);
        $stmt->fetch();
        
        return $value;
    }
}

class ProductRepository extends RepositoryBase {        
    public function getAll($language) {
        $sql = "SELECT product.id, name, price, imgSmallPath, description, `short-description` FROM `product` 
                INNER JOIN `productText` ON (product.id=`productText`.`product-id` AND `language-code`=?)";
        
        return $this->query($sql, function($stmt) use($language) {
            $stmt->bind_param('s', $language);
            $stmt->execute();
            
            $stmt->bind_result($row_id, $row_name, $row_price, $row_img, $row_description, $row_shortDescription);
            $result = array();
            while ($stmt->fetch()) {
                $result[] = $this->buildProductByRow($row_id, $row_name, $row_price, $row_img, $row_description, $row_shortDescription);
            }
            
            return $result;
        });
    }
    
    public function getById($productId, $language) {
        $sql = "SELECT product.id, name, price, imgSmallPath, description, `short-description` FROM `product` 
                INNER JOIN `productText` ON (product.id=`productText`.`product-id` AND `product`.`id`=? AND `language-code`=?)";
        
        return $this->query($sql, function($stmt) use($productId, $language) {
            $stmt->bind_param('is', $productId, $language);
            $stmt->execute();
            
            $stmt->bind_result($row_id, $row_name, $row_price, $row_img, $row_description, $row_shortDescription);
            $result = null;
            if ($stmt->fetch()) {
                $result = $this->buildProductByRow($row_id, $row_name, $row_price, $row_img, $row_description, $row_shortDescription);
            }
            
            return $result;
        });
    }
    
    public function getIngredients($productId, $language) {
        $sql = "SELECT ingredient.id, ingredient.name FROM `productIngredient` 
                INNER JOIN ingredient ON (ingredient.id=`productIngredient`.`ingredient-id` 
                    AND ingredient.`language-code`=? 
                    AND `productIngredient`.`product-id`=?) 
                ORDER BY `productIngredient`.`position`";
        
        return $this->query($sql, function($stmt) use($productId, $language) {
            $stmt->bind_param('si', $language, $productId);
            $stmt->execute();
            
            $stmt->bind_result($row_id, $row_name);
            $result = array();
            while ($stmt->fetch()) {
                $ingredient = new Ingredient();
                $ingredient->id = intval($row_id);
                $ingredient->name = utf8_encode($row_name);
                
                $result[] = $ingredient;
            }
            
            return $result;
        });
    }
    
    private function buildProductByRow($row_id, $row_name, $row_price, $row_img, $row_description, $row_shortDescription) {
        $product = new Product();
        $product->id = intval($row_id);
        $product->name = utf8_encode($row_name);
        $product->price = floatval($row_price);
        $product->imgSmallPath = utf8_encode($row_img);
        $product->description = utf8_encode($row_description);
        $product->shortDescription = utf8_encode($row_shortDescription);
        
        return $product;
    }
}

class BasketRepository extends RepositoryBase {        
    public function insertHeader($basket) {
        $sql = "INSERT INTO `basketHeader`(`userId`, `deliveryStreet`, `deliveryPostCode`, `deliveryCity`, `invoiceStreet`, `invoicePostCode`, `invoiceCity`) 
                VALUES (?,?,?,?,?,?,?)";
        
        return $this->query($sql, function($stmt, $con) use($basket) {
            $stmt->bind_param('issssss', $basket->userId, $basket->deliveryStreet, $basket->deliveryPostCode, $basket->deliveryCity, $basket->invoiceStreet, $basket->invoicePostCode, $basket->invoiceCity);
            $stmt->execute();
            
            return $con->insert_id;
        });
    }
    
    public function insertLine($headerId, $productId, $price, $amount) {
        $sql = "INSERT INTO `basketLine`(`headerId`, `productId`, `productPrice`, `amount`) 
                VALUES (?,?,?,?)";
                
        return $this->query($sql, function($stmt, $con) use($headerId, $productId, $price, $amount) {
            $stmt->bind_param('iidd', $headerId, $productId, $price, $amount);
            $stmt->execute();
            
            return $con->insert_id;
        });
    }
}

class UserRepository extends RepositoryBase {
    public function insert($user) {
        $sql = "INSERT INTO `user` (`email`, `role`, `password`, `salt`, `givenname`, `surname`,  `street`, `postCode`, `city`) 
                VALUES (?,?,?,?,?,?,?,?,?)";
        
        return $this->query($sql, function($stmt, $con) use($user) {
            $stmt->bind_param('sisssssss', $user->email, $user->role, $user->password, $user->salt, $user->givenname, $user->surname, $user->street, $user->postCode, $user->city);
            $stmt->execute();
            
            return $con->insert_id;
        });
    }
    
    public function getByEmail($email) {
        $sql = "SELECT `id`, `email`, `role`, `password`, `salt`, `givenname`, `surname`, `street`, `postCode`, `city` FROM `user` 
                WHERE `email` = ?";
            
        return $this->query($sql, function($stmt) use($email) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            
            $stmt->bind_result($row_id, $row_email, $row_role, $row_pw, $row_salt, $row_givenname, $row_surname, $row_street, $row_postCode, $row_city);
            $result = null;
            if ($stmt->fetch()) {
                $result = new User();
                $result->id = $row_id;
                $result->email = $row_email;
                $result->givenname = $row_givenname;
                $result->surname = $row_surname;
                $result->street = $row_street;
                $result->postCode = $row_postCode;
                $result->city = $row_city;
                $result->role = $row_role;
                $result->password = $row_pw;
                $result->salt = $row_salt;
            }
        
            return $result;
        });    
    }
    
    public function existsByEmail($email) {
        $sql = "SELECT COUNT(*) FROM `user` 
                WHERE `email` = ?";
                
        return $this->query($sql, function($stmt) use($email) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            
            return $this->fetchScalar($stmt) > 0;
        });
    }
}

class LanguageRepository extends RepositoryBase {
    public function getAll() {
        $sql = "SELECT `code`, `name` FROM `language`";
        
        return $this->query($sql, function($stmt) {
            $stmt->execute();
            
            $stmt->bind_result($row_code, $row_name);
            $result = array();
            while ($stmt->fetch()) {
                $lang = new Language();
                $lang->code = utf8_encode($row_code);
                $lang->name = utf8_encode($row_name);
                
                $result[] = $lang;
            }
            
            return $result;
        });
    }
}

?>