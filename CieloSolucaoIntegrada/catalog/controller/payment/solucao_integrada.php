<?php
class ControllerPaymentSolucaoIntegrada extends Controller {
	public function index() {
		$this->load->language('payment/solucao_integrada');
		$this->load->model('payment/solucao_integrada');
		
		$data['action'] = 'https://cieloecommerce.cielo.com.br/Transactional/Order/Index';
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_cpfcnpj'] = $this->language->get('text_cpfcnpj');
		$data['erro_cpfcnpj'] = $this->language->get('erro_cpfcnpj');
		
		$id_pedido = $this->session->data['order_id'];
		$data['order_number'] = $id_pedido;
		$data['merchant_id'] = $this->config->get('solucao_integrada_identificador');
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($id_pedido);
		
		if($this->config->get('solucao_integrada_texto_fatura')){
			$data['soft_descriptor'] = substr(preg_replace("/[^A-Za-z0-9]/", '',$this->config->get('solucao_integrada_texto_fatura')),0,13);
		}else{
			$data['soft_descriptor'] = '';
		}
		
		// Produtos - Início //		
		$data['products'] = array();
		
		foreach ($this->cart->getProducts() as $product){
			$data['products'][] = array(
					'name'     	=> $product['name'],
					'unitprice' => preg_replace("/[^0-9]/", '',number_format($product['price'],2)),
					'quantity' 	=> $product['quantity'],
					'weight'	=> ($product['weight'] > 0) ? $this->weight->convert($product['weight'],$product['weight_class_id'],2) : '100'
			);
		}
		// Produtos - Fim //
		
		//Descontos - Início //
		$descontos = $this->model_payment_solucao_integrada->getDadosDescontos($id_pedido);
		if(isset($descontos['valor']) && $descontos['valor'] > 0){
			$data['has_desconto'] = true;
			$data['Discount_Type'] = 1;
			$data['Discount_Value'] = preg_replace("/[^0-9]/", '',number_format($descontos['valor'],2));
		}else{
			$data['has_desconto'] = false;
		}
		//Descontos - Fim //
		
		//Frete - Início //
		if($this->cart->hasShipping()){
			$data['cart_type'] = '1';
			$data['has_shipping'] = true;
				
			$dados_frete = $this->model_payment_solucao_integrada->getDadosFrete($id_pedido);
				
			if(isset($dados_frete['value']) && $dados_frete['value'] > 0){
				$data['shipping_type'] = '2';
		
				$data['enviar_valor_frete'] = true;
		
				$data['shipping_1_name'] = substr($dados_frete['title'],0,128);
				$data['shipping_1_price'] = preg_replace("/[^0-9]/", '',number_format($dados_frete['value'],2));
			}else{
				$data['shipping_type'] = '3';
				$data['enviar_valor_frete'] = false;
			}
		
			$this->load->model('localisation/zone');
			$zone = $this->model_localisation_zone->getZone($order_info['shipping_zone_id']);
				
			$data['Shipping_Zipcode'] = preg_replace ("/[^0-9]/", '', $order_info['shipping_postcode']);
			$data['Shipping_Address_Name'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
			if(isset($order_info['shipping_numero'])){
				$data['Shipping_Address_Number'] = preg_replace ("/[^0-9]/", '', $order_info['shipping_numero']);
			}else{
				$data['Shipping_Address_Number'] = '';
			}
			if(isset($order_info['shipping_complemento'])){
				$data['Shipping_Address_Complement'] = html_entity_decode($order_info['shipping_complemento'], ENT_QUOTES, 'UTF-8');
			}else{
				$data['Shipping_Address_Complement'] = '';
			}
			$data['Shipping_Address_District'] = html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
			$data['Shipping_Address_City'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
			$data['Shipping_Address_State'] = html_entity_decode($zone['code'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['has_shipping'] = false;
			$data['shipping_type'] = '5';
			$data['cart_type'] = '2';
		}
		//Frete - Fim //

		//Dados do Cliente - Início //
		$data['Customer_Name'] = html_entity_decode($order_info['payment_firstname'].' '.$order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['Customer_Email'] = html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8');
		if(isset($order_info['cpf']) && !empty($order_info['cpf'])){
			$data['Customer_Identity'] = $order_info['cpf'];
		}elseif(isset($order_info['cnpj']) && !empty($order_info['cnpj'])){
			$data['Customer_Identity'] = $order_info['cnpj'];
		}elseif(isset($order_info['payment_tax_id']) && !empty($order_info['payment_tax_id'])){
			$data['Customer_Identity'] = $order_info['payment_tax_id'];
		}else{
			$data['Customer_Identity'] = false;
		}
		$data['Customer_Phone'] = preg_replace ("/[^0-9]/",'',$order_info['telephone']);
		//Dados do Cliente - Fim //

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/solucao_integrada.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/solucao_integrada.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/solucao_integrada.tpl', $data);
		}
	}
	
	public function retorno() {
		$id_pedido = 0;
	
		if (isset($this->session->data['order_id'])) {
			$id_pedido = $this->session->data['order_id'];
				
			$this->cart->clear();
	
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
		}
	
		$this->load->language('payment/solucao_integrada');
		$this->load->model('checkout/order');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
				'href'      => $this->url->link('account/account'),
				'text'      => $this->language->get('text_account'),
				'separator' => $this->language->get('text_separator')
		);
	
		$dados_pedido = $this->model_checkout_order->getOrder($id_pedido);
		$data['heading_title'] = $this->language->get('status_pedido').$dados_pedido['order_status'];
		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('information/contact'));
		$data['continue'] = $this->url->link('common/home');
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/solucao_integrada_retorno.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/solucao_integrada_retorno.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/solucao_integrada_retorno.tpl', $data));
		}
	}	
	
	public function confirm() {
		$this->load->model('checkout/order');
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}

	function notificacao(){
		if(empty($_POST)){
			die('<br /><center><h3>Acesso direto negado!</h3></center>'); exit();
		}
		
		extract($_POST);
		
		$notificar_cliente = true;
		$comentario = '';

		$comentario .= 'ID Pedido Cielo: '.$checkout_cielo_order_number.'<br />';
		$comentario .= 'TID da Transação: '.$tid.'<br />';
		$comentario .= 'Status da Transação na Cielo: ';
		switch ($payment_status){
			case 1:
				$novo_status = $this->config->get('solucao_integrada_status_pendente_id');
				$comentario .= 'Pendente<br />';
				break;
			case 2:
				$novo_status = $this->config->get('solucao_integrada_status_pago_id');
				$comentario .= 'Paga<br />';
				break;
			case 3:
				$novo_status = $this->config->get('solucao_integrada_status_negado_id');
				$comentario .= 'Negada<br />';
				break;
			case 5:
				$novo_status = $this->config->get('solucao_integrada_status_cancelado_id');
				$comentario .= 'Cancelada<br />';
				break;
			case 6:
				$novo_status = $this->config->get('solucao_integrada_status_naofinalizado_id');
				$comentario .= 'Não Finalizada<br />';
				break;
			case 7:
				$novo_status = $this->config->get('solucao_integrada_status_autorizado_id');
				$comentario .= 'Autorizada<br />';
				break;
		}
		
		if(isset($payment_method_type)){
			$comentario .= 'Meio de Pagamento: ';
			switch ($payment_method_type){
				case 1: $comentario .= 'Cartão de Crédito<br />';
					if(isset($payment_method_brand)){
						$comentario .= 'Bandeira do Cartão: ';
						switch ($payment_method_brand){
							case 1: $comentario .= 'Visa<br />'; break;
							case 2: $comentario .= 'Mastercard<br />'; break;
							case 3: $comentario .= 'American Express<br />'; break;
							case 4: $comentario .= 'Diners<br />'; break;
							case 5: $comentario .= 'Elo<br />'; break;
						}						
					}
					if(isset($payment_maskedcreditcard)){
						$comentario .= 'Cartão Mascarado: '.$payment_maskedcreditcard.'<br />';
					}
					if(isset($payment_installments)){
						$comentario .= 'Número de Parcelas: '.$payment_installments.'<br />';
					}
					break;
				case 2: $comentario .= 'Boleto Bancário<br />';
					if(isset($payment_method_bank)){
						$comentario .= 'Banco Emissor: ';
						switch ($payment_method_bank){
							case 1: $comentario .= 'Banco do Brasil<br />'; break;
							case 2: $comentario .= 'Bradesco<br />'; break;
						}
					}
					if(isset($payment_boletoexpirationdate)){
						$comentario .= 'Data de Vencimento: '.$payment_boletoexpirationdate.'<br />';
					}					
					break;
				case 3: $comentario .= 'Débito Online<br />';
					if(isset($payment_method_bank)){
						$comentario .= 'Banco Emissor: ';
						switch ($payment_method_bank){
							case 1: $comentario .= 'Banco do Brasil<br />'; break;
							case 2: $comentario .= 'Bradesco<br />'; break;
						}
					}				
					break;
				case 4: $comentario .= 'Cartão de Débito<br />';
					break;
			}			
		}
		
		if(isset($payment_antifrauderesult)){
			$comentario .= 'Status do Antifraude: ';
			switch ($payment_antifrauderesult){
				case 1: $comentario .= 'Baixo Risco<br />'; break;
				case 2: $comentario .= 'Alto Risco<br />'; break;
				case 3: $comentario .= 'Não Finalizado<br />'; break;
				case 4: $comentario .= 'Risco Moderado<br />'; break;
			}
		}		
		
		$comentario .= '<br />A operadora poderá ser consultada para mais informações.';
		
		$this->load->model('checkout/order');
		$this->model_checkout_order->update($order_number,$novo_status,$comentario,$notificar_cliente);
		
		echo '<status>OK</status>';
		exit();
	}
	
	function status(){
		if(empty($_POST)){
			die('<br /><center><h3>Acesso direto negado!</h3></center>'); exit();
		}		
		
		extract($_POST);
		
		$notificar_cliente = true;
		$comentario = '';
		
		$comentario .= 'ID Pedido Cielo: '.$checkout_cielo_order_number.'<br />';
		$comentario .= 'Status da Transação na Cielo: ';
		
		switch ($payment_status){
			case 1:
				$novo_status = $this->config->get('solucao_integrada_status_pendente_id');
				$comentario .= 'Pendente<br />';
				break;
			case 2:
				$novo_status = $this->config->get('solucao_integrada_status_pago_id');
				$comentario .= 'Paga<br />';
				break;
			case 3:
				$novo_status = $this->config->get('solucao_integrada_status_negado_id');
				$comentario .= 'Negada<br />';
				break;
			case 5:
				$novo_status = $this->config->get('solucao_integrada_status_cancelado_id');
				$comentario .= 'Cancelada<br />';
				break;
			case 6:
				$novo_status = $this->config->get('solucao_integrada_status_naofinalizado_id');
				$comentario .= 'Não Finalizada<br />';
				break;
			case 7:
				$novo_status = $this->config->get('solucao_integrada_status_autorizado_id');
				$comentario .= 'Autorizada<br />';
				break;				
		}
		
		$comentario .= '<br />A operadora poderá ser consultada para mais informações.';
		
		$this->load->model('checkout/order');
		$this->model_checkout_order->update($order_number,$novo_status,$comentario,$notificar_cliente);

		echo '<status>OK</status>';
		exit();
	}
}
?>