<?php include('header.php'); ?>
<?php
	// check privileges
	if(user_privilege('manage_products')){
		if(isset($_GET['action']) && isset($_GET['id'])){
			if($_GET['action'] == 'edit'){
				header('location: edit_product.php?id='.$_GET['id']);
				ob_end_flush();
			}
			if($_GET['action'] == 'delete'){
				mysqli_query($conn, "UPDATE products SET status = 'D' WHERE id = '".$_GET['id']."'");
				$success = 'Product was deleted!';
			}
		}
	}else{
		header('location: index.php');
		ob_end_flush();
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">Products</h5>
	<?php include('alert.php'); ?>
	<div class="row mb-3">
		<div class="col">
			<a href="new_product.php" class="btn btn-primary btn-sm float-end">New Product</a>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-sm table-hover" style="font-size:11px;">
			<thead>
				<tr class="table-primary">
					<th>#</th>
					<th>Name</th>
					<th>Unit Price</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$q = mysqli_query($conn, "SELECT * FROM products WHERE status = 'A' ORDER BY name");
				$n = 1;
				while($r = mysqli_fetch_array($q)){
					echo '<tr>
						<td>'.$n.'</td>
						<td>'.$r['name'].'</td>
						<td>'.$r['price'].'</td>
						<td class="text-center" style="width:80px;">
							<a href="javascript:confirmAction(\'Edit\',\''.$r['id'].'\');" title="Edit" class="btn btn-outline-primary btn-sm" style="text-decoration:none;"><i class="bi-pencil"></i></a>
							<a href="javascript:confirmAction(\'Delete\',\''.$r['id'].'\');" title="Delete" class="btn btn-outline-danger btn-sm" style="text-decoration:none;"><i class="bi-trash"></i></a>
						</td>
					</tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>

<script>
	function confirmAction(actionType, recordID){
		var arg = confirm("Are you sure you want to " + actionType + " this record?");
		if(arg){
			switch(actionType){
				case "Edit":
					window.location="products.php?action=edit&id="+recordID;
					break;
				case "Delete":
					window.location="products.php?action=delete&id="+recordID;
					break;
				default:
					alert("Invalid action...!");
			}
		}
	}
</script>
<?php include('footer.php'); ?>