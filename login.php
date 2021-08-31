<?php include('header.php'); ?>
<?php
	if(
		isset($_POST['username']) && 
		isset($_POST['password']) && 
		!empty($_POST['username']) && 
		!empty($_POST['password'])
	){
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		
		$q = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
		$r = mysqli_fetch_array($q);
		if($password == $r['password']){
			$_SESSION['login_user'] = array("id"=>"{$r['id']}", "username"=>"{$r['username']}", "privileges"=>"{$r['privileges']}");
			header('location: index.php');
			ob_end_flush();
		}else{
			$error = 'invalid username or password!';
		}
	}
?>
<div class="container">
	<h5 class="mt-4">Login</h5>	
	<div class="col-md-4 p-3" style="border: 1px solid #ccc;">
		<?php include('alert.php'); ?>
		<form method="post" class="mb-4">
			<div class="mb-1">
				<label for="username" class="form-label">Username</label>
				<input type="text" id="username" name="username" class="form-control">
			</div>
			<div class="mb-1 col-md">
				<label for="password" class="form-label">Password</label>
				<input type="password" id="password" name="password" class="form-control">
			</div>
			<button class="btn btn-primary" type="submit">Login</button>
		</form>
	</div>
</div>
<?php include('footer.php'); ?>