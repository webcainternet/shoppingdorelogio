<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-payment_fee" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment_fee" class="form-horizontal">
          <div class="form-group">
            
            <label class="col-sm-2 control-label" for="input-tax-class"><span data-toggle="tooltip" title="<?php echo $entry_tax_class_help; ?>"><?php echo $entry_tax_class; ?></span></label>
            <div class="col-sm-10">
              <select name="payment_fee_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $payment_fee_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="payment_fee_status" id="input-status" class="form-control">
                <?php if ($payment_fee_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_fee_sort_order" value="<?php echo $payment_fee_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

	  <table id="paymentFees" class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<td class="text-left"><?php echo $entry_payment_method; ?></td>
			<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo $entry_fee_help; ?>"><?php echo $entry_fee; ?></span></label></td>
			<td class="text-left"><label class="control-label"><span data-toggle="tooltip" title="<?php echo $entry_total_help; ?>"><?php echo $entry_total; ?></span></label></td>
			<td class="text-left"><?php echo $entry_customer_group; ?></td>
			<td></td>
		  </tr>
		</thead>
		<tbody>
		<?php $feeNo = 0; ?>
		<?php foreach ($payment_fees as $fee) { ?>
		  <tr id="feeNo<?php echo $feeNo; ?>">
			  <td class="text-left">
				<select name="payment_fees[<?php echo $feeNo; ?>][payment_method]" class="form-control">
					<?php if ($payment_methods) { ?>
					  <?php foreach($payment_methods as $payment_method) { ?>
					  <?php if ($fee['payment_method'] == $payment_method['code']) { ?>
						  <option value="<?php echo $payment_method['code']; ?>" selected="selected"><?php echo $payment_method['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['name']; ?></option>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</select>
			  </td> 
			<td class="text-left"><input type="text" name="payment_fees[<?php echo $feeNo; ?>][fee]" value="<?php echo $fee['fee']; ?>" class="form-control" /></td> 
				<td class="text-left"><input type="text" name="payment_fees[<?php echo $feeNo; ?>][order_total]" value="<?php echo ($fee['order_total'] == "")? "" : $fee['order_total']; ?>" class="form-control" /></td>
				<td class="text-left">
				  <select name="payment_fees[<?php echo $feeNo; ?>][customer_group_id]" class="form-control">
				  <option value=""><?php echo $entry_all; ?></option>
				  <?php foreach($customer_groups as $customer_group) { ?>
				  <?php if ($fee['customer_group_id'] == $customer_group['customer_group_id']) { ?>
					  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
				  <?php } else { ?>
					  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
				<?php } ?>
				</select>
			  </td>
		  <td class="text-left"><button type="button" onclick="$('#feeNo<?php echo $feeNo; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
			</tr>
		  <?php $feeNo++; } ?>
		</tbody>
		<tfoot>
		  <tr>
			<td colspan="4"></td>
			<td class="text-left"><a onclick="addFee();" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
		  </tr>
		</tfoot>
		</table>
        </form>
      </div>
    </div>
  </div>
</div>

	<script type="text/javascript"><!--
	var feeNo = <?php echo $feeNo; ?>;

	function addFee() {
		html  = '<tr id="feeNo' + feeNo + '">';
		html += '<td class="text-left"><select name="payment_fees[' + feeNo + '][payment_method]" class="form-control">';
		<?php if ($payment_methods) { ?>
		  <?php foreach($payment_methods as $payment_method) { ?>
		  html += '<option value="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['name']; ?></option>';
		  <?php } ?>
		<?php } ?>
	  html += '</select></td>';
		html += '<td class="text-left"><input type="text" name="payment_fees[' + feeNo + '][fee]" value="" class="form-control" /></td>';
		html += '<td class="text-left"><input type="text" name="payment_fees[' + feeNo + '][order_total]" value="" class="form-control" /></td>';
		html += '<td class="text-left"><select name="payment_fees[' + feeNo + '][customer_group_id]" class="form-control">';
		html += '<option value=""><?php echo $entry_all; ?></option>';
		<?php if ($customer_groups) { ?>
		  <?php foreach($customer_groups as $customer_group) { ?>
		  html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
		  <?php } ?>
		<?php } ?>
		html += '</select></td>';
	  html += '<td class="text-left"><button type="button" onclick="$(\'#feeNo' + feeNo + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

	  $('#paymentFees tbody').append(html);
		
		feeNo++;
	}
	//--></script> 
<?php echo $footer; ?> 