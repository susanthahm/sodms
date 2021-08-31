<?php include('header.php'); ?>
<?php
	// check privileges
	if(user_privilege('manage_users') && isset($_GET['id'])){
		$user_id = $_GET['id'];
		if(
			isset($_POST['username']) && 
			isset($_POST['password']) && 
			!empty($_POST['username']) && 
			!empty($_POST['password']) && 
			isset($_POST['privileges']) 
		){
			// check for existing usernames
			$q = mysqli_query($conn, "SELECT * FROM users WHERE username = '".$_POST['username']."' AND id != '$user_id'");
			if(mysqli_num_rows($q) > 0){
				$error = 'This username already existing in the system!';
			}else{
				$privileges = implode(",", $_POST['privileges']);
				$password = md5($_POST['password']);
				mysqli_query($conn, "UPDATE users SET username = '".$_POST['username']."', password = '$password', privileges = '$privileges' WHERE id = '$user_id'");
				$success = 'User details has been updated!';
			}
		}
		$user_obj = user_obj($user_id);
	}else{
		header('location: index.php');
		ob_end_flush();
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">Edit User</h5>	
	<?php include('alert.php'); ?>
	<form method="post" class="mb-4">
		<div class="row">
			<div class="mb-1">
				<label for="username" class="form-label">User Name</label>
				<input type="text" id="username" name="username" value="<?php echo $user_obj['username']; ?>" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="password" class="form-label">Password</label>
				<input type="password" id="password" name="password" class="form-control">
			</div>
		</div>
		<p class="mt-4"><strong>Select access privileges for this user</strong></p>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="create_orders" name="privileges[]" value="create_orders"<?php if(strpos($user_obj['privileges'], "create_orders")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="create_orders">User can enter new orders.</label>
		</div>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="edit_orders" name="privileges[]" value="edit_orders"<?php if(strpos($user_obj['privileges'], "edit_orders")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="edit_orders">User can edit order details.</label>
		</div>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="complete_orders" name="privileges[]" value="complete_orders"<?php if(strpos($user_obj['privileges'], "complete_orders")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="complete_orders">User can mark orders as delivered.</label>
		</div>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="return_orders" name="privileges[]" value="return_orders"<?php if(strpos($user_obj['privileges'], "return_orders")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="return_orders">User can mark orders as returned.</label>
		</div>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="delete_orders" name="privileges[]" value="delete_orders"<?php if(strpos($user_obj['privileges'], "delete_orders")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="delete_orders">User can delete orders.</label>
		</div>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="manage_products" name="privileges[]" value="manage_products"<?php if(strpos($user_obj['privileges'], "manage_products")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="manage_products">User can manage (create / edit / delete) products.</label>
		</div>
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="manage_users" name="privileges[]" value="manage_users"<?php if(strpos($user_obj['privileges'], "manage_users")!== false){ echo ' checked'; } ?>>
			<label class="form-check-label" for="manage_users">User can manage (create / edit / delete) other users.</label>
		</div>
		<button class="btn btn-primary mt-3" type="submit">Save</button>
	</form>
</div>
	
<?php include('footer.php'); ?>