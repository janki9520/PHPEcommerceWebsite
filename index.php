<?php
include_once("session.php");
include_once("DB.class.php");

$msg = "";
$error = "";
$errorSales = "";

if(isset($_GET['addToCart'])){
	$product_id = $_GET['addToCart'];
	$user = $_SESSION['login_user'];
	if($db->addToCart($user,$product_id)){
		$msg .= " Successful, adding Item to cart!!!";
		echo "<script type='text/javascript'>alert('$msg');window.location.href = 'index.php';</script>";
	}else{
		$error .=" Sorry, We are out of stock !!";
		echo "<script type='text/javascript'>alert('$error');
		      window.location.href = 'index.php';</script>";	
	}
}


$error .= $db->checkProductConstraint();
//change in $errorSales .= $db->checkSalesConstraint(); 
$errorSales .= $db->checkSalesContraint();

$title = "Home";
include("header.php");
include("adminNav.php");
?>

<div class="container">

<h3 class="text-center">PRODUCTS ON SALE</h3>
<p class="text-center">Check out our Sale section!!! Grab them all till the stock lasts!!</p>
 <hr>

 <table class="table table-striped">
 <tr class="success">
 <th scope="col">Name</th>
 <th scope="col">Description</th>
 <th scope="col">Quantity Left in stock</th>
 <th scope="col">Image</th>
 <th scope="col">Original Price</th>
 <th scope="col">Discounted Price</th><th></th>
 </tr>
 <?php
if(empty($errorSales)){
 $data = $db->showSales();
 if(isset($data)){
 	foreach($data as $value){
 	extract($value);
echo @<<<show
 	<tr class="success">\n
 	<td>$name</td>\n
 	<td>$description</td>\n
	<td>$quantity</td>\n
 	<td><img src="images/$image_name" height="150" width="150" alt=$image_name></td>\n
 	<td>$price</td>\n
 	<td>$sales_price</td>\n
 	<td><a href="index.php?addToCart=$product_id" class="btn btn-default btn-lg" role="button">Add To Cart</a></td>\n
 	</tr>\n
show;
 	}
 }
 }
 
 echo "</table>";

 ?>


 <!-- catlog section -->
 <h3 class="text-center">PRODUCT CATLOG</h3>
 <p class="text-center">Catalog features products sold by many sellers!!</p>
 <hr>

 <table class="table table-striped">
 <tr class="success">
 <th scope="col">Name</th>
 <th scope="col">Description</th>
 <th scope="col">Quantity in Stock</th>
 <th scope="col">Image</th>
 <th scope="col">Price</th><th></th>
 </tr>
 
 <?php
if(empty($error)){
 if (isset($_GET["page"])){ 
 		$page  = $_GET["page"];
 		$page = $page+1;
 	} 
 	else{
 		$page=1;
 	};
 
 $rec_limit = 5;
 $start_from = ($page-1) * $rec_limit; 	
 $data = $db->showCatalog($start_from, $rec_limit);
 if(isset($data)){
 	foreach($data as $value){
 	extract($value);
echo @<<<show
 	<tr class="success">\n
 	<td>$name</td>\n
 	<td>$description</td>\n
	<td>$quantity</td>\n
	<td><img src="images/$image_name" height="150" width="150" alt=$image_name></td>\n
 	<td>$price</td>\n
	<td><a href="index.php?addToCart=$product_id" class="btn btn-default btn-lg" role="button">Add To Cart</a></td>\n
 	</tr>\n
show;
 	}
 }else{
 	
 }
 
 echo "</table>";
echo '<div class="pagination">';
 
	$total_records = $db->getProductCount();
	$total_pages = ceil($total_records / $rec_limit); 
 	if($total_pages>1){
	for ($i=1; $i<=$total_pages; $i++) {
				$pageId = $i-1;
				echo "<a href='index.php?page=".$pageId."'";
				echo " class='btn btn-default btn-lg' role='button'>";
				echo "".$i."</a>";
	}; }
if( $page > 1 && $page < $total_pages ) {
$prev = $page - 2;
$next = $page;
echo "<a href=\"$_SERVER[PHP_SELF]?page=$prev\" class='btn btn-default btn-lg' role='button'>Last 5 Records</a>";
echo "<a href=\"$_SERVER[PHP_SELF]?page=$next\" class='btn btn-default btn-lg' role='button'>Next 5 Records</a>";
}else if( $page == 1  && $total_pages!=1) {
	echo "<a href = \"$_SERVER[PHP_SELF]?page=$page\" class='btn btn-default btn-lg' role='button'>Next 5 Records</a>";
}else if( $page == $total_pages and $page!=1) {
	$prev = $page - 2;
	echo "<a href=\"$_SERVER[PHP_SELF]?page=$prev\" class='btn btn-default btn-lg' role='button'>Last 5 Records</a>";
}
echo "</div>";

 }


if(!empty($errorSales))
 echo "<div class='alert alert-danger'>
	<strong>Error!</strong>$errorSales";
 ?>
</div>

</body>
</html>