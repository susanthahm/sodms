<?php
	session_start();
	ob_start();
	date_default_timezone_set('Asia/Colombo');
	include('db_connect.php');
	include('functions.php');
	
	define('PAGE_NAME', page_name());
	
	// check login status
	check_login_status();
	
	
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="css/custom.css" rel="stylesheet" crossorigin="anonymous">
<link href="icons/bootstrap-icons.css" rel="stylesheet" crossorigin="anonymous">
<title><?php echo PAGE_NAME; ?></title>
</head>
<body>
<?php if(PAGE_NAME != 'Login'){ // remove nav from login page ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
	<div class="container-fluid">
		<a class="navbar-brand" href="#">d<sub>2</sub></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="index.php">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="new_order.php">New Order</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="products.php">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" aria-current="page" href="users.php">Users</a>
				</li>
			</ul>
			<span class="navbar-text">
				<a style="text-decoration:none" href="logout.php">Logout<a/>
			</span>
		</div>
	</div>
</nav>
<?php } // end of if statement for checking login.php ?>