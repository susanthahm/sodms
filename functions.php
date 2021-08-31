<?php
	/* 
		Simple Order Delivery Management System 
		Design and developed by Susantha Herath
		susanthahm[at]gmail.com
		Follow me on twitter @susanthahm
		On Git https://github.com/susanthahm
		
		Copyright (c) 2021 Susantha Herath @susanthahm. Portions Copyright
		(c) JS Foundation https://js.foundation/ and 
		Bootstrap Team https://getbootstrap.com/docs/5.1/about/team/ 
		All rights reserved.

		Project website: https://github.com/susanthahm/sodms

		Redistribution and use in source and binary forms, with
		or without modification, are permitted provided that the
		following conditions are met:

		* Redistributions of source code must retain the above
		  copyright notice, this list of conditions and the
		  following disclaimer.

		* Redistributions in binary form must reproduce the above
		  copyright notice, this list of conditions and the
		  following disclaimer in the documentation and/or other
		  materials provided with the distribution.

		THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND
		CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
		INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
		MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
		DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
		CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
		SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
		NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
		LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
		HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
		CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
		OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
		SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

		This product includes the following third party libraries:
		* JQuery 3.6.0
		  See: https://code.jquery.com/jquery-3.6.0.min.js
		* Boostrap 5.1.0
		  See: https://github.com/twbs/bootstrap/releases/download/v5.1.0/bootstrap-5.1.0-dist.zip

	*/
	
	function page_name(){
		$arr = explode(".",basename($_SERVER['PHP_SELF']));
		return ucwords(str_replace("_", " ", $arr[0]));
	}
	
	function check_login_status(){
		if(PAGE_NAME == 'Login'){
			if(isset($_SESSION['login_user'])){
				header('location: index.php');
				ob_end_flush();
			}
		}else{
			if(!isset($_SESSION['login_user'])){
				header('location: login.php');
				ob_end_flush();
			}
		}
	}
	
	function user_privilege($privilege){
		if(strpos($_SESSION['login_user']['privileges'], $privilege) !== false){
			return true;
		}else{
			return false;
		}
	}
	
	function user_obj($user_id){
		global $conn;
		$q = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
		$r = mysqli_fetch_array($q);
		$arr = array("username"=>"{$r['username']}", "password"=>"{$r['password']}", "privileges"=>"{$r['privileges']}");
		return $arr;
	}
	
	function product_obj($product_id){
		global $conn;
		$q = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
		$r = mysqli_fetch_array($q);
		$arr = array("name"=>"{$r['name']}", "price"=>"{$r['price']}");
		return $arr;
	}
	
	function order_obj($order_id){
		global $conn;
		$q = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id'");
		$r = mysqli_fetch_array($q);
		$arr = array(
			"customer_name"=>"{$r['customer_name']}", 
			"delivery_address"=>"{$r['delivery_address']}",
			"contact_no"=>"{$r['contact_no']}",
			"product_id"=>"{$r['product_id']}",
			"qty"=>"{$r['qty']}",
			"unit_price"=>"{$r['unit_price']}",
			"delivery_charge"=>"{$r['delivery_charge']}",
			"payment_type"=>"{$r['payment_type']}",
			"delivery_by"=>"{$r['delivery_by']}",
			"delivery_date"=>"{$r['delivery_date']}",
			"delivery_address"=>"{$r['delivery_address']}",
			"order_status"=>"{$r['order_status']}"
		);
		return $arr;
	}
?>