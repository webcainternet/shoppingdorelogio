<?php if ($teste) { ?>
<div class="alert alert-warning"><?php echo $text_teste; ?></div>
<?php } ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <form id="payment-form" class="form-horizontal well" role="form" action="<?php echo $action; ?>">
                <fieldset>
                    <legend><?php echo $text_barra; ?></legend>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="creditcard_cctype" class="col-sm-3 control-label">Tipo</label>
                                    <div class="col-sm-9">
                                    <select name="creditcard_cctype" id="creditcard_cctype" class="form-control">
                                        <option value="">Selecione...</option>
                                    <?php foreach($data['cartoes'] as $bandeira => $cartao) { ?>
                                        <option value="<?php echo $bandeira; ?>" data-parcelas="<?php echo $cartao['parcelas']; ?>"><?php echo $cartao['nome']; ?></option>
                                    <?php } ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="creditcard_name" class="col-sm-3 control-label">Nome</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="creditcard_name" name="creditcard_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="creditcard_ccno" class="col-sm-3 control-label">Nº do Cartão</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="creditcard_ccno" name="creditcard_ccno">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="creditcard_cccvd" class="col-sm-3 control-label">Cód. de Segurança</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="creditcard_cccvd" name="creditcard_cccvd">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Vencimento</label>
                                    <div class="col-sm-4">
                                        <select name="creditcard_ccexpm" class="form-control">
                                            <option value="">Mês</option>
                                            <?php for($m=1; $m<=12; $m++) {
                                                $option = str_pad($m, 2, '0', STR_PAD_LEFT);
                                            ?>
                                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="creditcard_ccexpy" class="form-control">
                                            <option value="">Ano</option>
                                            <?php for($i=0; $i <= 20; $i++) {
                                                $option = date('Y') + $i;
                                            ?>
                                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="parcelas"</div>
                            </div>
                        </div>
                    </div>

                    <div class="buttons">
                        <div class="pull-right">
                            <input type="submit" value="<?php echo $text_pagamento; ?>" id="button-confirm" class="btn btn-success" data-loading-text="<?php echo $text_loading?>" />
                        </div>
                    </div>
                </fieldset>
            </form>
            <p style="text-align:center"><?php echo $text_info; ?></p>
        </div>
    </div>
</div>

<script type="text/javascript">

jQuery(function () {
    jQuery('#creditcard_cctype').change(function() {
        $this = jQuery(this);

        if($this.val() == '') { return; }

        var bandeira = $this.find('option:selected').val();
        var parcelas = $this.find('option:selected').attr('data-parcelas');

        jQuery.ajax({
            url: 'index.php?route=payment/cielo/parcelamento&bandeira='+bandeira+'&parcelas='+parcelas,
            type: 'GET',
            cache: false,
            dataType: 'html',
            beforeSend: function() {
                jQuery('#button-confirm').button('loading');
            },
            complete: function() {
                jQuery('#button-confirm').button('reset');
            },
            success: function(data) {
                jQuery('#parcelas').html(data);
            }
        });
    });
});

jQuery('#payment-form').submit(function(event) {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;

    var validade = jQuery('select[name="creditcard_ccexpy"]').val() + '' + jQuery('select[name="creditcard_ccexpm"]').val();

    jQuery.ajax({
        url: jQuery(this).prop('action'),
        type: 'POST',
        cache: false,
        data: jQuery(this).serialize() + '&validade=' + validade,
        dataType: 'json',
        beforeSend: function() {
            jQuery('#button-confirm').button('loading');
            jQuery('#payment-form .alert-danger').remove();
        },
        complete: function() {
            jQuery('#button-confirm').button('reset');
        },
        success: function(data) {
            if(!!data.error) {
                var $form = jQuery('#payment-form');
                $form.find('.help-inline').not('.fixed-help').remove();
                $form.find('.form-group').removeClass('error').removeClass('has-error');

                jQuery.each(data.error, function (i, m) {
                    var $input = jQuery('input[name="' + i + '"],select[name="' + i + '"]');
                    var $container = $input.parents('.form-group');

                    $input.parent().append('<span class="help-inline">' + m + '</span>');
                    $container.addClass('has-error').addClass('error');

                    if($input.length == 0) {
                        jQuery('#payment-form').prepend('<div class="alert alert-danger"><p>'+ m +'</p></div>');
                    }
                });

            } else if(!!data.redirect) {

                if(typeof data.redirect == 'object') {

                    window.location.href = data.redirect[0];
                } else {
                    window.location.href = data.redirect;
                }
            }
        }
    });
});
</script>