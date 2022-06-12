<?php
public function getCategories() {
    $sqlQuery = "
		SELECT category_id, category_name
		FROM ".$this->productTable." 
		GROUP BY category_name";
    return  $this->getData($sqlQuery);
}
public function getBrand () {
    $sql = '';
    if(isset($_POST['category']) && $_POST['category']!="") {
        $category = $_POST['category'];
        $sql.=" WHERE category_id IN ('".implode("','",$category)."')";
    }
    $sqlQuery = "
		SELECT distinct brand
		FROM ".$this->productTable." 
		$sql GROUP BY brand";
    return  $this->getData($sqlQuery);
}
public function getMaterial () {
    $sql = '';
    if(isset($_POST['brand']) && $_POST['brand']!="") {
        $brand = $_POST['brand'];
        $sql.=" WHERE brand IN ('".implode("','",$brand)."')";
    }
    $sqlQuery = "
		SELECT distinct material
		FROM ".$this->productTable." 
		$sql GROUP BY material";
    return  $this->getData($sqlQuery);
}
public function getProductSize () {
    $sql = '';
    if(isset($_POST['brand']) && $_POST['brand']!="") {
        $brand = $_POST['brand'];
        $sql.=" WHERE brand IN ('".implode("','",$brand)."')";
    }
    $sqlQuery = "
		SELECT distinct size
		FROM ".$this->productTable." 
		$sql GROUP BY size";
    return  $this->getData($sqlQuery);
}

public function getProducts() {
    $productPerPage = 9;
    $totalRecord  = strtolower(trim(str_replace("/","",$_POST['totalRecord'])));
    $start = ceil($totalRecord * $productPerPage);
    $sql= "SELECT * FROM ".$this->productTable." WHERE qty != 0";
    if(isset($_POST['category']) && $_POST['category']!=""){
        $sql.=" AND category_id IN ('".implode("','",$_POST['category'])."')";
    }
    if(isset($_POST['brand']) && $_POST['brand']!=""){
        $sql.=" AND brand IN ('".implode("','",$_POST['brand'])."')";
    }
    if(isset($_POST['material']) && $_POST['material']!="") {
        $sql.=" AND material IN ('".implode("','",$_POST['material'])."')";
    }
    if(isset($_POST['size']) && $_POST['size']!="") {
        $sql.=" AND size IN (".implode(',',$_POST['size']).")";
    }
    if(isset($_POST['sorting']) && $_POST['sorting']!="") {
        $sorting = implode("','",$_POST['sorting']);
        if($sorting == 'newest' || $sorting == '') {
            $sql.=" ORDER BY id DESC";
        } else if($sorting == 'low') {
            $sql.=" ORDER BY price ASC";
        } else if($sorting == 'high') {
            $sql.=" ORDER BY price DESC";
        }
    } else {
        $sql.=" ORDER BY id DESC";
    }
    $sql.=" LIMIT $start, $productPerPage";
    $products = $this->getData($sql);
    $rowcount = $this->getNumRows($sql);
    $productHTML = '';
    if(isset($products) && count($products)) {
        foreach ($products as $key => $product) {
            $productHTML .= '<article class="col-md-4 col-sm-6">';
            $productHTML .= '<div class="thumbnail product">';
            $productHTML .= '<figure>';
            $productHTML .= '<a href="#"><img src="images/'.$product['image'].'" alt="'.$product['product_name'].'" /></a>';
            $productHTML .= '</figure>';
            $productHTML .= '<div class="caption">';
            $productHTML .= '<a href="" class="product-name">'.$product['product_name'].'</a>';
            $productHTML .= '<div class="price">$'.$product['price'].'</div>';
            $productHTML .= '<h6>Brand : '.$product['brand'].'</h6>';
            $productHTML .= '<h6>Material : '.$product['material'].'</h6>';
            $productHTML .= '<h6>Size : '.$product['size'].'</h6>';
            $productHTML .= '</div>';
            $productHTML .= '</div>';
            $productHTML .= '</article>';
        }
    }
    return 	$productHTML;
}
?>


