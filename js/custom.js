// get product price
$(document).ready(function(){
	$("#product").on("change", function(){
		var product_id = $(this).val();
		$.ajax({
			url: "get_product_price.php",
			data: { id: product_id },
			success: function(result){
				$("#price").val(result);
			}
		});
	});
});