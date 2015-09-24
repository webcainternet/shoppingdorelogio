<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-skrill" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?> - 
      <a href="https://cieloecommerce.cielo.com.br/Backoffice/" target="_blank">BackOffice</a></h1>
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
        		<h3 class="panel-title">
        			<i class="fa fa-pencil"></i> <?php echo $text_edit; ?>
        		</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-solucao-integrada-cielo" class="form-horizontal">

					<div class="form-group">
			            <label class="col-sm-2 control-label" for="solucao_integrada_total">
			            	<span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span>
			            </label>
						<div class="col-sm-10">
							<input type="text" name="solucao_integrada_total" value="<?php echo $solucao_integrada_total; ?>" 
								placeholder="<?php echo $entry_total; ?>" id="solucao_integrada_total" class="form-control" />
							<?php if ($error_total) { ?>
								<div class="text-danger"><?php echo $error_total; ?></div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_identificador">
							<span data-toggle="tooltip" title="<?php echo $help_identificador; ?>"><?php echo $entry_identificador; ?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="solucao_integrada_identificador" value="<?php echo $solucao_integrada_identificador; ?>" 
								placeholder="<?php echo $entry_identificador; ?>" id="solucao_integrada_identificador" class="form-control" />
							<?php if ($error_identificador) { ?>
								<div class="text-danger"><?php echo $error_identificador; ?></div>
							<?php } ?>              
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_texto_fatura">
							<span data-toggle="tooltip" title="<?php echo $help_texto_fatura; ?>"><?php echo $entry_texto_fatura; ?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="solucao_integrada_texto_fatura" value="<?php echo $solucao_integrada_texto_fatura; ?>" 
								placeholder="<?php echo $entry_texto_fatura; ?>" id="solucao_integrada_texto_fatura" class="form-control" />
						</div>
					</div>			
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_antifraude">
							<span data-toggle="tooltip" title="<?php echo $help_antifraude; ?>"><?php echo $entry_antifraude; ?></span>
						</label>
						<div class="col-sm-10">
							<select name="solucao_integrada_antifraude" id="solucao_integrada_antifraude" class="form-control">
								<?php if ($solucao_integrada_antifraude) { ?>
									<option value="0"><?php echo $text_no; ?></option>
									<option value="1" selected="selected"><?php echo $text_yes; ?></option>
								<?php } else { ?>
									<option value="0" selected="selected"><?php echo $text_no; ?></option>
									<option value="1"><?php echo $text_yes; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">
							<span data-toggle="tooltip" title="<?php echo $entry_urls_help; ?>"><?php echo $entry_urls; ?></span>
						</label>
						<div class="col-sm-10">
							<strong><?php echo $entry_urls_retorno; ?></strong><?php echo $base_loja ?>index.php?route=payment/solucao_integrada/retorno<br><br>
							<strong><?php echo $entry_urls_notificacao; ?></strong><?php echo $base_loja ?>index.php?route=payment/solucao_integrada/notificacao<br><br>
							<strong><?php echo $entry_urls_status; ?></strong><?php echo $base_loja ?>index.php?route=payment/solucao_integrada/status
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status_pendente_id"><?php echo $entry_status_pendete; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status_pendente_id" id="solucao_integrada_status_pendente_id" class="form-control">
				                <?php foreach ($order_statuses as $order_status) { ?>
					                <?php if ($order_status['order_status_id'] == $solucao_integrada_status_pendente_id) { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					                <?php } else { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					                <?php } ?>
				                <?php } ?>							
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status_pago_id"><?php echo $entry_status_pago; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status_pago_id" id="solucao_integrada_status_pago_id" class="form-control">
				                <?php foreach ($order_statuses as $order_status) { ?>
					                <?php if ($order_status['order_status_id'] == $solucao_integrada_status_pago_id) { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					                <?php } else { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					                <?php } ?>
				                <?php } ?>						
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status_negado_id"><?php echo $entry_status_negado; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status_negado_id" id="solucao_integrada_status_negado_id" class="form-control">
				                <?php foreach ($order_statuses as $order_status) { ?>
					                <?php if ($order_status['order_status_id'] == $solucao_integrada_status_negado_id) { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					                <?php } else { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					                <?php } ?>
				                <?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status_cancelado_id"><?php echo $entry_status_cancelado; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status_cancelado_id" id="solucao_integrada_status_cancelado_id" class="form-control">
				                <?php foreach ($order_statuses as $order_status) { ?>
					                <?php if ($order_status['order_status_id'] == $solucao_integrada_status_cancelado_id) { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					                <?php } else { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					                <?php } ?>
				                <?php } ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status_naofinalizado_id"><?php echo $entry_status_naofinalizado; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status_naofinalizado_id" id="solucao_integrada_status_naofinalizado_id" class="form-control">
				                <?php foreach ($order_statuses as $order_status) { ?>
					                <?php if ($order_status['order_status_id'] == $solucao_integrada_status_naofinalizado_id) { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					                <?php } else { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					                <?php } ?>
				                <?php } ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status_autorizado_id"><?php echo $entry_status_autorizado; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status_autorizado_id" id="solucao_integrada_status_autorizado_id" class="form-control">
				                <?php foreach ($order_statuses as $order_status) { ?>
					                <?php if ($order_status['order_status_id'] == $solucao_integrada_status_autorizado_id) { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					                <?php } else { ?>
					                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					                <?php } ?>
				                <?php } ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_geo_zone_id" id="solucao_integrada_geo_zone_id" class="form-control">
				                <option value="0"><?php echo $text_all_zones; ?></option>
				                <?php foreach ($geo_zones as $geo_zone) { ?>
				                	<?php if ($geo_zone['geo_zone_id'] == $solucao_integrada_geo_zone_id) { ?>
				                		<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
				                	<?php } else { ?>
				                		<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
				                	<?php } ?>
				                <?php } ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="solucao_integrada_status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="solucao_integrada_status" id="solucao_integrada_status" class="form-control">
				                <?php if ($solucao_integrada_status) { ?>
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
						<label class="col-sm-2 control-label" for="solucao_integrada_sort_order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="solucao_integrada_sort_order" value="<?php echo $solucao_integrada_sort_order; ?>" 
								placeholder="<?php echo $entry_sort_order; ?>" id="solucao_integrada_sort_order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>