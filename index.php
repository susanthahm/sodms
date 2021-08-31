<?php include('header.php'); ?>
<?php
	if(isset($_GET['action']) && isset($_GET['id'])){
		if($_GET['action'] == 'edit' && user_privilege('edit_orders')){
			header('location: edit_order.php?id='.$_GET['id']);
			ob_end_flush();
		}
		if($_GET['action'] == 'delete' && user_privilege('delete_orders')){
			mysqli_query($conn, "DELETE FROM orders WHERE id = '".$_GET['id']."'");
			$success = 'Order was deleted!';
		}
		if($_GET['action'] == 'completed' && user_privilege('complete_orders')){
			mysqli_query($conn, "UPDATE orders SET order_status = 'COM' WHERE id = '".$_GET['id']."'");
			$success = 'Order is completed!';
		}
		if($_GET['action'] == 'returned' && user_privilege('return_orders')){
			mysqli_query($conn, "UPDATE orders SET order_status = 'RTN' WHERE id = '".$_GET['id']."'");
			$success = 'Order is returned!';
		}
	}
	
	// filter form posted
	if(
		isset($_POST['date_from']) && !empty($_POST['date_from']) && 
		isset($_POST['date_to']) && !empty($_POST['date_to']) && 
		$_POST['date_from'] <= $_POST['date_to'] 
	){
		$sql = "SELECT * FROM orders WHERE ";
		if($_POST['product'] > 0){
			$sql .= " product_code = '".$_POST['product']."' AND ";
		}
		if(!empty($_POST['deliver_by'])){
			$sql .= " delivery_by = '".$_POST['deliver_by']."' AND ";
		}
		if(!empty($_POST['created_by'])){
			$sql .= " created_by = '".$_POST['created_by']."' AND ";
		}
		if(!empty($_POST['edited_by'])){
			$sql .= " edited_by = '".$_POST['edited_by']."' AND ";
		}
		$sql .= " DATE(order_date) BETWEEN '".$_POST['date_from']."' AND '".$_POST['date_to']."'";
	}
?>
<div class="container-fluid">
	<h5 class="mt-4">Orders</h5>
	<?php include('alert.php'); ?>
	<form method="post" class="mb-4">
		<div class="row">
			<div class="col">
				<input type="date" name="date_from" class="form-control" placeholder="Date From" aria-label="Date From">
			</div>
			<div class="col">
				<input type="date" name="date_to" class="form-control" placeholder="Date To" aria-label="Date To">
			</div>
			<div class="col">
				<select size="1" name="product" class="form-select" aria-label="Product">
					<option name="0">All Products</option>
					<?php
						$q = mysqli_query($conn, "SELECT id, name FROM products WHERE status = 'A' ORDER BY name");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="col">
				<select size="1" name="deliver_by" class="form-select" aria-label="Deliver by">
					<option value="">Delivered by (All)</option>
					<?php
						$q = mysqli_query($conn, "SELECT username FROM users WHERE id > '1' ORDER BY username");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['username'].'">'.ucfirst($r['username']).'</option>';
						}
					?>
				</select>
			</div>
			<div class="col">
				<select size="1" name="created_by" class="form-select" aria-label="Created by">
					<option value="">Created by (All)</option>
					<?php
						$q = mysqli_query($conn, "SELECT username FROM users WHERE id > '1' ORDER BY username");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['username'].'">'.ucfirst($r['username']).'</option>';
						}
					?>
				</select>
			</div>
			<div class="col">
				<select size="1" name="edited_by" class="form-select" aria-label="Edited by">
					<option value="">Edited by (All)</option>
					<?php
						$q = mysqli_query($conn, "SELECT username FROM users WHERE id > '1' ORDER BY username");
						while($r = mysqli_fetch_array($q)){
							echo '<option value="'.$r['username'].'">'.ucfirst($r['username']).'</option>';
						}
					?>
				</select>
			</div>
			<div class="col">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</form>
	
	<div class="table-responsive">
		<table class="table table-bordered table-sm table-hover" style="font-size:11px;">
			<thead>
				<tr class="table-dark">
					<th>#</th>
					<th>Order Date</th>
					<th>Name</th>
					<th>Address</th>
					<th>Contact No.</th>
					<th>Product</th>
					<th>Qty</th>
					<th>Rate LKR</th>
					<th>Delivery LKR</th>
					<th>COD</th>
					<th>Bank</th>
					<th>Delivery By</th>
					<th>Delivery Date</th>
					<th>Created By</th>
					<th>Edited By</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$n = 1;
				$total_qty = 0;
				$total_delivery = 0;
				$total_cod = 0;
				$total_bank = 0;
				if(isset($sql)){
					// show filter results
					$q = mysqli_query($conn, $sql);
					while($r = mysqli_fetch_array($q)){
						$cod = 0;
						$bank = 0;
						if($r['payment_type'] == 'COD'){
							$cod = $r['unit_price'] * $r['qty'];
						}else{
							$bank = $r['unit_price'] * $r['qty'];
						}
						$product_obj = product_obj($r['product_id']);
						$total_qty += $r['qty'];
						$total_delivery += $r['delivery_charge'];
						$total_cod += $cod;
						$total_bank += $bank;
						
						$tr_class = '';
						if($r['order_status'] == 'RTN'){
							$tr_class = ' class="table-warning"';
						}
						if($r['order_status'] == 'PND'){
							$tr_class = ' class="table-primary"';
						}
						if($r['order_status'] == 'COM'){
							$tr_class = ' class="table-success"';
						}
						echo '<tr'.$tr_class.'>
							<td>'.$n.'</td>
							<td>'.$r['order_date'].'</td>
							<td>'.$r['customer_name'].'</td>
							<td>'.$r['delivery_address'].'</td>
							<td>'.$r['contact_no'].'</td>
							<td>'.$product_obj['name'].'</td>
							<td class="text-center">'.$r['qty'].'</td>
							<td class="text-end">'.number_format($r['unit_price'],0).'</td>
							<td class="text-end">'.number_format($r['delivery_charge'],0).'</td>
							<td class="text-end">'.number_format($cod,0).'</td>
							<td class="text-end">'.number_format($bank,0).'</td>
							<td class="text-center">'.$r['delivery_by'].'</td>
							<td>'.$r['delivery_date'].'</td>
							<td>'.$r['created_by'].'</td>
							<td>'.$r['edited_by'].'</td>
							<td class="text-center" style="width:150px;">
								<a href="javascript:confirmAction(\'Edit\',\''.$r['id'].'\');" title="Edit" class="btn btn-outline-primary btn-sm" style="text-decoration:none;"><i class="bi-pencil"></i></a>
								<a href="javascript:confirmAction(\'Completed\',\''.$r['id'].'\');" title="Completed" class="btn btn-outline-success btn-sm" style="text-decoration:none;"><i class="bi-check2"></i></a>
								<a href="javascript:confirmAction(\'Returned\',\''.$r['id'].'\');" title="Returned" class="btn btn-outline-warning btn-sm" style="text-decoration:none;"><i class="bi-box-arrow-in-left"></i></a>
								<a href="javascript:confirmAction(\'Delete\',\''.$r['id'].'\');" title="Delete" class="btn btn-outline-danger btn-sm" style="text-decoration:none;"><i class="bi-trash"></i></a>
							</td>
						</tr>';
					}
				}else{
					// show new and pending orders
					// NEW ORDERS
					$q = mysqli_query($conn, "SELECT * FROM orders WHERE order_status = 'NEW' ORDER BY order_date DESC");
					while($r = mysqli_fetch_array($q)){
						$cod = 0;
						$bank = 0;
						if($r['payment_type'] == 'COD'){
							$cod = $r['unit_price'] * $r['qty'];
						}else{
							$bank = $r['unit_price'] * $r['qty'];
						}
						$product_obj = product_obj($r['product_id']);
						$total_qty += $r['qty'];
						$total_delivery += $r['delivery_charge'];
						$total_cod += $cod;
						$total_bank += $bank;
						echo '<tr>
							<td>'.$n.'</td>
							<td>'.$r['order_date'].'</td>
							<td>'.$r['customer_name'].'</td>
							<td>'.$r['delivery_address'].'</td>
							<td>'.$r['contact_no'].'</td>
							<td>'.$product_obj['name'].'</td>
							<td class="text-center">'.$r['qty'].'</td>
							<td class="text-end">'.number_format($r['unit_price'],0).'</td>
							<td class="text-end">'.number_format($r['delivery_charge'],0).'</td>
							<td class="text-end">'.number_format($cod,0).'</td>
							<td class="text-end">'.number_format($bank,0).'</td>
							<td class="text-center">'.$r['delivery_by'].'</td>
							<td>'.$r['delivery_date'].'</td>
							<td>'.$r['created_by'].'</td>
							<td>'.$r['edited_by'].'</td>
							<td class="text-center" style="width:150px;">
								<a href="javascript:confirmAction(\'Edit\',\''.$r['id'].'\');" title="Edit" class="btn btn-outline-primary btn-sm" style="text-decoration:none;"><i class="bi-pencil"></i></a>
								<a href="javascript:confirmAction(\'Completed\',\''.$r['id'].'\');" title="Completed" class="btn btn-outline-success btn-sm" style="text-decoration:none;"><i class="bi-check2"></i></a>
								<a href="javascript:confirmAction(\'Returned\',\''.$r['id'].'\');" title="Returned" class="btn btn-outline-warning btn-sm" style="text-decoration:none;"><i class="bi-box-arrow-in-left"></i></a>
								<a href="javascript:confirmAction(\'Delete\',\''.$r['id'].'\');" title="Delete" class="btn btn-outline-danger btn-sm" style="text-decoration:none;"><i class="bi-trash"></i></a>
							</td>
						</tr>';
					}
					// PENDING ORDERS
					$q = mysqli_query($conn, "SELECT * FROM orders WHERE order_status = 'PEN' ORDER BY order_date DESC");
					while($r = mysqli_fetch_array($q)){
						$cod = 0;
						$bank = 0;
						if($r['payment_type'] == 'COD'){
							$cod = $r['unit_price'] * $r['qty'];
						}else{
							$bank = $r['unit_price'] * $r['qty'];
						}
						$product_obj = product_obj($r['product_id']);
						$total_qty += $r['qty'];
						$total_delivery += $r['delivery_charge'];
						$total_cod += $cod;
						$total_bank += $bank;
						echo '<tr class="table-primary">
							<td>'.$n.'</td>
							<td>'.$r['order_date'].'</td>
							<td>'.$r['customer_name'].'</td>
							<td>'.$r['delivery_address'].'</td>
							<td>'.$r['contact_no'].'</td>
							<td>'.$product_obj['name'].'</td>
							<td class="text-center">'.$r['qty'].'</td>
							<td class="text-end">'.number_format($r['unit_price'],0).'</td>
							<td class="text-end">'.number_format($r['delivery_charge'],0).'</td>
							<td class="text-end">'.number_format($cod,0).'</td>
							<td class="text-end">'.number_format($bank,0).'</td>
							<td class="text-center">'.$r['delivery_by'].'</td>
							<td>'.$r['delivery_date'].'</td>
							<td>'.$r['created_by'].'</td>
							<td>'.$r['edited_by'].'</td>
							<td class="text-center" style="width:150px;">
								<a href="javascript:confirmAction(\'Edit\',\''.$r['id'].'\');" title="Edit" class="btn btn-outline-primary btn-sm" style="text-decoration:none;"><i class="bi-pencil"></i></a>
								<a href="javascript:confirmAction(\'Completed\',\''.$r['id'].'\');" title="Completed" class="btn btn-outline-success btn-sm" style="text-decoration:none;"><i class="bi-check2"></i></a>
								<a href="javascript:confirmAction(\'Returned\',\''.$r['id'].'\');" title="Returned" class="btn btn-outline-warning btn-sm" style="text-decoration:none;"><i class="bi-box-arrow-in-left"></i></a>
								<a href="javascript:confirmAction(\'Delete\',\''.$r['id'].'\');" title="Delete" class="btn btn-outline-danger btn-sm" style="text-decoration:none;"><i class="bi-trash"></i></a>
							</td>
						</tr>';
					}
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="6"></th>
					<th class="text-center"><?php echo number_format($total_qty,0); ?></th>
					<th class="text-end"></th>
					<th class="text-end"><?php echo number_format($total_delivery,0); ?></th>
					<th class="text-end"><?php echo number_format($total_cod,0); ?></th>
					<th class="text-end"><?php echo number_format($total_bank,0); ?></th>
					<th colspan="5"></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<script>
	function confirmAction(actionType, recordID){
		var arg = confirm("Are you sure you want to " + actionType + " this record?");
		if(arg){
			switch(actionType){
				case "Edit":
					window.location="index.php?action=edit&id="+recordID;
					break;
				case "Completed":
					window.location="index.php?action=completed&id="+recordID;
					break;
				case "Returned":
					window.location="index.php?action=returned&id="+recordID;
					break;
				case "Delete":
					window.location="index.php?action=delete&id="+recordID;
					break;
				default:
					alert("Invalid action...!");
			}
		}
	}
</script>
<?php include('footer.php'); ?>