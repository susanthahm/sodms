<?php
	if(isset($_GET['id']) && $_GET['id'] > 0){
		include('db_connect.php');
		$q = mysqli_query($conn, "SELECT price FROM products WHERE id = '".$_GET['id']."'");
		$r = mysqli_fetch_array($q);
		echo $r['price'];
		mysqli_close($conn);
	}
?>