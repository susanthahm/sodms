<?php include('header.php'); ?>
<?php
	// check privileges
	if(user_privilege('create_orders')){
		if(
			isset($_POST['product']) && 
			isset($_POST['qty']) && 
			!empty($_POST['qty']) && 
			isset($_POST['price']) && 
			!empty($_POST['price']) && 
			isset($_POST['customer_name']) && 
			!empty($_POST['customer_name']) && 
			isset($_POST['address']) && 
			!empty($_POST['address']) && 
			isset($_POST['telephone']) && 
			!empty($_POST['telephone'])  
		){
			$delivery = $_POST['delivery'];
			if(empty($_POST['delivery'])){
				$delivery = 0;
			}
			$sql = "INSERT INTO ORDERS (
						customer_name, 
						delivery_address, 
						contact_no, 
						product_id, 
						qty, 
						unit_price, 
						delivery_charge, 
						payment_type, 
						created_by
					) VALUES (
						'".$_POST['customer_name']."', 
						'".$_POST['address']."', 
						'".$_POST['telephone']."', 
						'".$_POST['product']."', 
						'".$_POST['qty']."', 
						'".$_POST['price']."', 
						'".$delivery."', 
						'".$_POST['payment']."', 
						'".$_SESSION['login_user']['username']."'
					)";
			mysqli_query($conn, $sql);
			$success = 'Order details are saved!';
		}
	}else{
		header('location: index.php');
		ob_end_flush();
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">New Order</h5>	
	<?php include('alert.php'); ?>
	<form method="post" class="mb-4">
		<div class="row">
			<div class="mb-1 col-md">
				<label for="product" class="form-label">Product</label>
				<select size="1" id="product" name="product" class="form-select" required>
					<option value="">Select product...</option>
					<?php
						$q = mysqli_query($conn, "SELECT id, name FROM products WHERE status = 'A' ORDER BY name");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="mb-1 col-md">
				<label for="qty" class="form-label">Qty</label>
				<input type="number" id="qty" name="qty" class="form-control" required>
			</div>
			<div class="mb-1 col-md">
				<label for="price" class="form-label">Unit Price</label>
				<input type="number" id="price" name="price" class="form-control" required>
			</div>
			<div class="mb-1 col-md">
				<label for="delivery" class="form-label">Delivery Charge</label>
				<input type="number" id="delivery" name="delivery" value="0" class="form-control" required>
			</div>
			<div class="mb-1 col-md">
				<label for="payment" class="form-label">Payment Method</label>
				<select size="1" id="payment" name="payment" class="form-select">
					<option name="COD">COD</option>
					<option name="BANK">Bank Transfer</option>
				</select>
			</div>
		</div>
		<div class="mb-1">
			<label for="customer_name" class="form-label">Customer Name</label>
			<input type="text" id="customer_name" name="customer_name" class="form-control" required>
		</div>
		<div class="mb-1">
			<label for="address" class="form-label">Address</label>
			<input type="text" id="address" name="address" class="form-control" required>
		</div>
		<div class="mb-4">
			<label for="telephone" class="form-label">Contact Number</label>
			<input type="number" id="telephone" name="telephone" class="form-control" required>
		</div>
		<button class="btn btn-primary" type="submit">Save Order</button>
	</form>
</div>
	
<?php include('footer.php'); ?>