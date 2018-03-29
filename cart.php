<?php
include_once("session.php");

$user = $_SESSION['login_user'];

if(isset($_REQUEST['emptyCart'])){
	$db->emptyCart($user);
}
$title = "Cart";
include("header.php");
include("adminNav.php");
?>
 <h3>Shopping Cart</h3>
 <form action="cart.php" method="post">
 <table class="table table-striped">
 <tr class="success">
 <th scope="col">Name</th>
 <th scope="col">Description</th>
 <th scope="col">Quantity</th>
 <th scope="col">Price</th>
 </tr>
 <?php

 $data = $db->showCart($user);
 $amount = 0;
 
 if(isset($data)){
 	foreach($data as $value){
 	extract($value);
echo @<<<show
 	<tr class="success">\n
 	<td>$name</td>\n
 	<td>$description</td>\n
	<td>$quantity</td>\n
 	<td>$price</td>\n
 	</tr>\n
show;
	$amount += ($quantity*$price);

 	}
 	echo "<tr><td></td><td></td><td><input type='submit' name='emptyCart' value='Empty Cart' class='btn'></td>";
 	echo "<td><h4 style=\"font-weight: bold;\">Total order:"." $".$amount."</h4></td></tr>";
 }else{
 	$error .= "Cart is Empty!!! Please add products to the cart !!";
 }
 
 echo "</table></form>";
include("footer.php");
?>