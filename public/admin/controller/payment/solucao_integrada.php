<?php
class ControllerPaymentSolucaoIntegrada extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/solucao_integrada');

		$this->document->setTitle($this->language->get('heading_title_limpo'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('solucao_integrada', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
		
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title_limpo');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_loja'] = $this->language->get('text_loja');
		
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_identificador'] = $this->language->get('entry_identificador');
		$data['entry_texto_fatura'] = $this->language->get('entry_texto_fatura');
		$data['entry_antifraude'] = $this->language->get('entry_antifraude');
		$data['entry_status_pendete'] = $this->language->get('entry_status_pendete');
		$data['entry_status_pago'] = $this->language->get('entry_status_pago');
		$data['entry_status_negado'] = $this->language->get('entry_status_negado');
		$data['entry_status_cancelado'] = $this->language->get('entry_status_cancelado');
		$data['entry_status_naofinalizado'] = $this->language->get('entry_status_naofinalizado');
		$data['entry_status_autorizado'] = $this->language->get('entry_status_autorizado');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['entry_urls'] = $this->language->get('entry_urls');
		$data['entry_urls_help'] = $this->language->get('entry_urls_help');
		$data['entry_urls_retorno'] = $this->language->get('entry_urls_retorno');
		$data['entry_urls_notificacao'] = $this->language->get('entry_urls_notificacao');
		$data['entry_urls_status'] = $this->language->get('entry_urls_status');		

		$data['help_total'] = $this->language->get('help_total');
		$data['help_identificador'] = $this->language->get('help_identificador');
		$data['help_antifraude'] = $this->language->get('help_antifraude');
		$data['help_texto_fatura'] = $this->language->get('help_texto_fatura');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['solucao_integrada_total'])) {
			$data['error_total'] = $this->error['solucao_integrada_total'];
		} else {
			$data['error_total'] = '';
		}		

   		if (isset($this->error['solucao_integrada_identificador'])) {
			$data['error_identificador'] = $this->error['solucao_integrada_identificador'];
		} else {
			$data['error_identificador'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_limpo'),
			'href'      => $this->url->link('payment/solucao_integrada', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('payment/solucao_integrada', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if ($this->config->get('config_secure') == '1' && defined('HTTPS_CATALOG')){
			$data['base_loja'] = HTTPS_CATALOG;
		} else {
			$data['base_loja'] = HTTP_CATALOG;
		}		

		if (isset($this->request->post['solucao_integrada_total'])) {
			$data['solucao_integrada_total'] = $this->request->post['solucao_integrada_total'];
		} else {
			$data['solucao_integrada_total'] = $this->config->get('solucao_integrada_total');
		}		
		
		if (isset($this->request->post['solucao_integrada_identificador'])) {
			$data['solucao_integrada_identificador'] = $this->request->post['solucao_integrada_identificador'];
		} else {
			$data['solucao_integrada_identificador'] = $this->config->get('solucao_integrada_identificador');
		}
		
		if (isset($this->request->post['solucao_integrada_texto_fatura'])) {
			$data['solucao_integrada_texto_fatura'] = $this->request->post['solucao_integrada_texto_fatura'];
		} else {
			$data['solucao_integrada_texto_fatura'] = $this->config->get('solucao_integrada_texto_fatura');
		}

		if (isset($this->request->post['solucao_integrada_antifraude'])) {
			$data['solucao_integrada_antifraude'] = $this->request->post['solucao_integrada_antifraude'];
		} else {
			$data['solucao_integrada_antifraude'] = $this->config->get('solucao_integrada_antifraude');
		}		
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['solucao_integrada_status_pendente_id'])) {
			$data['solucao_integrada_status_pendente_id'] = $this->request->post['solucao_integrada_status_pendente_id'];
		} else {
			$data['solucao_integrada_status_pendente_id'] = $this->config->get('solucao_integrada_status_pendente_id'); 
		}
		
		if (isset($this->request->post['solucao_integrada_status_pago_id'])) {
			$data['solucao_integrada_status_pago_id'] = $this->request->post['solucao_integrada_status_pago_id'];
		} else {
			$data['solucao_integrada_status_pago_id'] = $this->config->get('solucao_integrada_status_pago_id'); 
		}
		
		if (isset($this->request->post['solucao_integrada_status_negado_id'])) {
			$data['solucao_integrada_status_negado_id'] = $this->request->post['solucao_integrada_status_negado_id'];
		} else {
			$data['solucao_integrada_status_negado_id'] = $this->config->get('solucao_integrada_status_negado_id');
		}

		if (isset($this->request->post['solucao_integrada_status_cancelado_id'])) {
			$data['solucao_integrada_status_cancelado_id'] = $this->request->post['solucao_integrada_status_cancelado_id'];
		} else {
			$data['solucao_integrada_status_cancelado_id'] = $this->config->get('solucao_integrada_status_cancelado_id');
		}

		if (isset($this->request->post['solucao_integrada_status_naofinalizado_id'])) {
			$data['solucao_integrada_status_naofinalizado_id'] = $this->request->post['solucao_integrada_status_naofinalizado_id'];
		} else {
			$data['solucao_integrada_status_naofinalizado_id'] = $this->config->get('solucao_integrada_status_naofinalizado_id');
		}

		if (isset($this->request->post['solucao_integrada_status_autorizado_id'])) {
			$data['solucao_integrada_status_autorizado_id'] = $this->request->post['solucao_integrada_status_autorizado_id'];
		} else {
			$data['solucao_integrada_status_autorizado_id'] = $this->config->get('solucao_integrada_status_autorizado_id');
		}		
		
		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['solucao_integrada_geo_zone_id'])) {
			$data['solucao_integrada_geo_zone_id'] = $this->request->post['solucao_integrada_geo_zone_id'];
		} else {
			$data['solucao_integrada_geo_zone_id'] = $this->config->get('solucao_integrada_geo_zone_id');
		}		

		if (isset($this->request->post['solucao_integrada_status'])) {
			$data['solucao_integrada_status'] = $this->request->post['solucao_integrada_status'];
		} else {
			$data['solucao_integrada_status'] = $this->config->get('solucao_integrada_status');
		}

		if (isset($this->request->post['solucao_integrada_sort_order'])) {
			$data['solucao_integrada_sort_order'] = $this->request->post['solucao_integrada_sort_order'];
		} else {
			$data['solucao_integrada_sort_order'] = $this->config->get('solucao_integrada_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('payment/solucao_integrada.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/solucao_integrada')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['solucao_integrada_total']) {
			$this->error['solucao_integrada_total'] = $this->language->get('error_total');
		}		

		if (!$this->request->post['solucao_integrada_identificador']) {
			$this->error['solucao_integrada_identificador'] = $this->language->get('error_identificador');
		}		

		return !$this->error;
	}	
}
?>