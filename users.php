<?php include('header.php'); ?>
<?php
	// check privileges
	if(user_privilege('manage_users')){
		if(isset($_GET['action']) && isset($_GET['id'])){
			if($_GET['action'] == 'edit'){
				header('location: edit_user.php?id='.$_GET['id']);
				ob_end_flush();
			}
			if($_GET['action'] == 'delete' && $_GET['id'] > '1'){
				mysqli_query($conn, "DELETE FROM users WHERE id = '".$_GET['id']."'");
				$success = 'User deleted!';
			}
		}
	}else{
		header('location: index.php');
		ob_end_flush();
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">Users</h5>
	<?php include('alert.php'); ?>
	<div class="row mb-3">
		<div class="col">
			<a href="new_user.php" class="btn btn-primary btn-sm float-end">New User</a>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-sm table-hover" style="font-size:11px;">
			<thead>
				<tr class="table-primary">
					<th>#</th>
					<th>Name</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$q = mysqli_query($conn, "SELECT id, username FROM users WHERE id > '1'");
				$n = 1;
				while($r = mysqli_fetch_array($q)){
					echo '<tr>
						<td>'.$n.'</td>
						<td>'.$r['username'].'</td>
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
					window.location="users.php?action=edit&id="+recordID;
					break;
				case "Delete":
					window.location="users.php?action=delete&id="+recordID;
					break;
				default:
					alert("Invalid action...!");
			}
		}
	}
</script>
<?php include('footer.php'); ?>