<?php include('header.php'); ?>
<?php
	// check privileges
	if(user_privilege('manage_products') && isset($_GET['id'])){
		$product_id = $_GET['id'];
		if(
			isset($_POST['product_name']) && 
			isset($_POST['price']) && 
			!empty($_POST['product_name']) && 
			!empty($_POST['price']) 
		){
			// check for existing names
			$q = mysqli_query($conn, "SELECT * FROM products WHERE name = '".$_POST['product_name']."' AND id != '$product_id'");
			if(mysqli_num_rows($q) > 0){
				$error = 'This product name already existing in the system!';
			}else{
				mysqli_query($conn, "UPDATE products SET name = '".$_POST['product_name']."', price = '".$_POST['price']."' WHERE id = '$product_id'");
				$success = 'Product details have been updated!';
			}
		}
		$product_obj = product_obj($product_id);
	}else{
		header('location: index.php');
		ob_end_flush();
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">Edit Product</h5>	
	<?php include('alert.php'); ?>
	<form method="post" class="mb-4">
		<div class="row">
			<div class="mb-1">
				<label for="product_name" class="form-label">Product Name</label>
				<input type="text" id="product_name" name="product_name" value="<?php echo $product_obj['name']; ?>" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="price" class="form-label">Unit Price</label>
				<input type="number" id="price" name="price" value="<?php echo $product_obj['price']; ?>" class="form-control">
			</div>
		</div>
		<button class="btn btn-primary mt-3" type="submit">Save</button>
	</form>
</div>
	
<?php include('footer.php'); ?>