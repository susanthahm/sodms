<?php
	if(isset($error)){
		echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
	}
	if(isset($success)){
		echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
	}
?>