<!--Shop Now Page-->

<?php
session_start();
require_once("dbcontroll.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) 
{
	switch($_GET["action"]) 
	{
		case "add":
			if(!empty($_POST["quantity"])) {
				$productByCode = $db_handle->runQuery("SELECT * FROM product WHERE code='" . $_GET["code"] . "'");
				$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
				
				if(!empty($_SESSION["cart_item"])) {
					if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
						foreach($_SESSION["cart_item"] as $k => $v) {
								if($productByCode[0]["code"] == $k) {
									if(empty($_SESSION["cart_item"][$k]["quantity"])) {
										$_SESSION["cart_item"][$k]["quantity"] = 0;
									}
									$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
								}
						}
					} else {
						$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
					}
				} else {
					$_SESSION["cart_item"] = $itemArray;
				}
			}
			break;
		case "remove":
			if(!empty($_SESSION["cart_item"])) {
				foreach($_SESSION["cart_item"] as $k => $v) {
						if($_GET["code"] == $k)
							unset($_SESSION["cart_item"][$k]);				
						if(empty($_SESSION["cart_item"]))
							unset($_SESSION["cart_item"]);
				}
			}
			break;
		case "empty":
			unset($_SESSION["cart_item"]);
			break;	
	}
}
?>
<HTML>
<head>
	<title>Furniture WebStore</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> <!--for navigation bar-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" ></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>         <!--for drop down-->
	<link rel="stylesheet" type="text/css" href="page1style.css">
	<link href="cartstyle.css" type="text/css" rel="stylesheet">
</head>

<body >
	<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">                                    <!--Navigation Bar-->
		<a class="navbar-brand" href="http://designextracts.com/index.html">
		<img src="images\logo.png" height="40" width="180" alt="logo"><br></a>

		<a class="navbar-brand" href="#"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="home.html">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="contact.html">Contact Us</a>
				</li>
			
				<li class="nav-item active">
					<a class="nav-link" href="index.php">Shop Now <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="order.html" > Your Orders</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">User
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						 <a class="dropdown-item" href="s.html">Sign Up</a>
						 <a class="dropdown-item" href="log.php">Sign In</a>
						 <a class="dropdown-item" href="logout.php">Sign Out</a>
					</div>
				</li>
			</ul>
			<form class="form-inline my-2 my-lg-0">
				<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
	</nav>
	

	<div id="shopping-cart">                                                                                     <!--Shopping Cart-->
		<div class="txt-heading">Shopping Cart</div>
		<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
		
		<?php
		if(isset($_SESSION["cart_item"])){
			$total_quantity = 0;
			$total_price = 0;
		?>	
		
		<table class="tbl-cart" cellpadding="10" cellspacing="1">
			<tbody>
				<tr>
					<th style="text-align:left;">Name</th>
					<th style="text-align:left;">Code</th>
					<th style="text-align:right;" width="5%">Quantity</th>
					<th style="text-align:right;" width="10%">Unit Price</th>
					<th style="text-align:right;" width="10%">Price</th>
					<th style="text-align:center;" width="5%">Remove</th>
				</tr>	
				<?php		
					foreach ($_SESSION["cart_item"] as $item){
						$item_price = $item["quantity"] * $item["price"];
				?>
								<tr>
									<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
									<td><?php echo $item["code"]; ?></td>
									<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
									<td  style="text-align:right;"><?php echo " ".$item["price"]; ?></td>
									<td  style="text-align:right;"><?php echo " ". number_format($item_price,2); ?></td>
									<td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="images/icon-delete.png" alt="Remove Item" /></a></td>
								</tr>
						<?php
								$total_quantity = $total_quantity + $item["quantity"];
								$total_price = $total_price + ($item["price"] * $item["quantity"]);
						}
						?>

				<tr>
					<td colspan="2" align="right">Total:</td>
					<td align="right"><?php echo $total_quantity; ?></td>
					<td align="right" colspan="2"><strong><?php echo "INR ".number_format($total_price, 2); ?></strong></td>
					<td></td>
				</tr>
			</tbody>
		</table>		
		<?php
		} else {
		?>
		<div class="no-records">Your Cart is Empty</div>
		<?php 
		}
		?>
	</div>

	<div id="product-grid" class="mb-6 pl-5">                                                               <!--Product Grid-->
		<div class="txt-heading">Products</div>
		<?php
		$product_array = $db_handle->runQuery("SELECT * FROM product ORDER BY id ASC");
		if (!empty($product_array)) { 
			foreach($product_array as $key=>$value){
		?>
			<div class="product-item">
				<form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
					<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
					<div class="product-tile-footer">
						<div class="product-title">Name :-<?php echo $product_array[$key]["name"]; ?></div>
						<div class="product-price">INR <?php echo " ".$product_array[$key]["price"]; ?></div><br/>
						<div class="cart-action">Quantity:-<input type="number" size="1" class="product-quantity" name="quantity" value="1"/><input type="submit" value="+Cart" class="btnAddAction" /></div>
					</div>
				</form>
			</div>
		<?php
			}
		}
		?>
	</div>
</body>
</html>