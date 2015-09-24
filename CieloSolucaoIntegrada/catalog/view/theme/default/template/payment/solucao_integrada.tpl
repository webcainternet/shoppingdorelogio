<form action="<?php echo $action; ?>" method="post" id="payment">
	<input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>" />
	<input type="hidden" name="order_number" value="<?php echo $order_number; ?>" />
	<?php if($soft_descriptor){ ?>
		<input type="hidden" name="soft_descriptor" value="<?php echo $soft_descriptor; ?>" />
	<?php } ?>
	
	<input type="hidden" name="shipping_type" value="<?php echo $shipping_type; ?>" />

	<?php $i = 1; foreach ($products as $product) { ?>
		<input type="hidden" name="cart_<?php echo $i;?>_name" value="<?php echo $product['name'];?>" />
		<input type="hidden" name="cart_<?php echo $i;?>_unitprice" value="<?php echo $product['unitprice'];?>" />
		<input type="hidden" name="cart_<?php echo $i;?>_quantity" value="<?php echo $product['quantity'];?>" />
		<input type="hidden" name="cart_<?php echo $i;?>_type" value="<?php echo $cart_type; ?>" />
		<input type="hidden" name="cart_<?php echo $i;?>_weight" value="<?php echo $product['weight']; ?>" />
	<?php $i++; } ?>
	
	<?php if($has_desconto){ ?>
		<input type="hidden" name="Discount_Type" value="<?php echo $Discount_Type; ?>" />
		<input type="hidden" name="Discount_Value" value="<?php echo $Discount_Value; ?>" />
	<?php } ?>
	
	<?php if($has_shipping){ ?>
		<?php if($enviar_valor_frete){ ?>
			<input type="hidden" name="shipping_1_name" value="<?php echo $shipping_1_name; ?>" />
			<input type="hidden" name="shipping_1_price" value="<?php echo $shipping_1_price; ?>" />
		<?php } ?>
		
		<input type="hidden" name="Shipping_Zipcode" value="<?php echo $Shipping_Zipcode; ?>" />
		<input type="hidden" name="Shipping_Address_Name" value="<?php echo $Shipping_Address_Name; ?>" />
		<input type="hidden" name="Shipping_Address_Number" value="<?php echo $Shipping_Address_Number; ?>" />
		<input type="hidden" name="Shipping_Address_Complement" value="<?php echo $Shipping_Address_Complement; ?>" />
		<input type="hidden" name="Shipping_Address_District" value="<?php echo $Shipping_Address_District; ?>" />
		<input type="hidden" name="Shipping_Address_City" value="<?php echo $Shipping_Address_City; ?>" />
		<input type="hidden" name="Shipping_Address_State" value="<?php echo $Shipping_Address_State; ?>" />
	<?php } ?>

	<input type="hidden" name="Customer_Name" value="<?php echo $Customer_Name; ?>" />
	<input type="hidden" name="Customer_Email" value="<?php echo $Customer_Email; ?>" />
	<?php if($Customer_Identity){ ?>
		<input type="hidden" name="Customer_Identity" value="<?php echo $Customer_Identity; ?>" />
	<?php } ?>
	<input type="hidden" name="Customer_Phone" value="<?php echo $Customer_Phone; ?>" />
</form>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function(e){
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/solucao_integrada/confirm',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		success: function() {
			$('#payment').submit();
		}
	});
});
//--></script>