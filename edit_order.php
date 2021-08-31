<?php include('header.php'); ?>
<?php
	// check privileges
	if(user_privilege('edit_orders') && isset($_GET['id'])){
		$order_id = $_GET['id'];
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
			$sql = "UPDATE ORDERS SET 
						customer_name = '".$_POST['customer_name']."', 
						delivery_address = '".$_POST['address']."', 
						contact_no = '".$_POST['telephone']."', 
						product_id = '".$_POST['product']."', 
						qty = '".$_POST['qty']."', 
						unit_price = '".$_POST['price']."', 
						delivery_charge = '".$delivery."', 
						payment_type = '".$_POST['payment']."', 
						delivery_by = '".$_POST['delivered_by']."', 
						delivery_date = '".$_POST['delivery_date']."', 
						edited_by = '".$_SESSION['login_user']['username']."', 
						order_status = '".$_POST['order_status']."' 
					WHERE id = '$order_id'";
			mysqli_query($conn, $sql);
			$success = 'Order details are saved!';
		}
		$order = order_obj($order_id);
	}else{
		header('location: index.php');
		ob_end_flush();
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">Edit Order</h5>
	<?php include('alert.php'); ?>	
	<form method="post" class="mb-4">
		<div class="row">
			<div class="mb-1 col-md">
				<label for="delivered_by" class="form-label">Delivery by</label>
				<select size="1" id="delivered_by" name="delivered_by" class="form-select">
					<?php
						$q = mysqli_query($conn, "SELECT username FROM users WHERE id > '1' ORDER BY username");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['username'].'"';
							if($r['username'] == $order['delivery_by']){
								echo ' selected';
							}
							echo '>'.ucfirst($r['username']).'</option>';
						}
					?>
				</select>
			</div>
			<div class="mb-1 col-md">
				<label for="delivery_date" class="form-label">Delivery Date</label>
				<input type="date" id="delivery_date" name="delivery_date" value="<?php echo $order['delivery_date']; ?>" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="order_status" class="form-label">Order Status</label>
				<select size="1" id="order_status" name="order_status" class="form-select">
					<option value="NEW"<?php if($order['order_status'] == 'NEW'){ echo ' selected'; } ?>>New Order</option>
					<option value="PND"<?php if($order['order_status'] == 'PND'){ echo ' selected'; } ?>>Pending Delivery</option>
					<option value="COM"<?php if($order['order_status'] == 'COM'){ echo ' selected'; } ?>>Delivered</option>
					<option value="RTN"<?php if($order['order_status'] == 'RTN'){ echo ' selected'; } ?>>Returned</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="mb-1 col-md">
				<label for="product" class="form-label">Product</label>
				<select size="1" id="product" name="product" class="form-select">
					<?php
						$q = mysqli_query($conn, "SELECT id, name FROM products WHERE status = 'A' ORDER BY name");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['id'].'"';
							if($r['id'] == $order['product_id']){
								echo ' selected';
							}
							echo '>'.$r['name'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="mb-1 col-md">
				<label for="qty" class="form-label">Qty</label>
				<input type="number" id="qty" name="qty" value="<?php echo $order['qty']; ?>" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="price" class="form-label">Unit Price</label>
				<input type="number" id="price" name="price" value="<?php echo $order['unit_price']; ?>" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="delivery" class="form-label">Delivery Charge</label>
				<input type="number" id="delivery" name="delivery" value="<?php echo $order['delivery_charge']; ?>" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="payment" class="form-label">Payment Method</label>
				<select size="1" id="payment" name="payment" class="form-select">
					<option name="COD"<?php if($order['payment_type'] == 'COD'){ echo ' selected'; } ?>>COD</option>
					<option name="BANK"<?php if($order['payment_type'] == 'BANK'){ echo ' selected'; } ?>>Bank Transfer</option>
				</select>
			</div>
		</div>
		<div class="mb-1">
			<label for="customer_name" class="form-label">Customer Name</label>
			<input type="text" id="customer_name" name="customer_name" value="<?php echo $order['customer_name']; ?>" class="form-control">
		</div>
		<div class="mb-1">
			<label for="address" class="form-label">Address</label>
			<input type="text" id="address" name="address" value="<?php echo $order['delivery_address']; ?>" class="form-control">
		</div>
		<div class="mb-4">
			<label for="telephone" class="form-label">Contact Number</label>
			<input type="number" id="telephone" name="telephone" value="<?php echo $order['contact_no']; ?>" class="form-control">
		</div>
		<button class="btn btn-primary" type="submit">Save Order</button>
	</form>
</div>
	
<?php include('footer.php'); ?>