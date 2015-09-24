<?php
class ModelPaymentSolucaoIntegrada extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/solucao_integrada');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('solucao_integrada_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('solucao_integrada_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('solucao_integrada_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	

		$currencies = array(
			'AUD',
			'CAD',
			'EUR',
			'GBP',
			'JPY',
			'USD',
			'NZD',
			'CHF',
			'HKD',
			'SGD',
			'SEK',
			'DKK',
			'PLN',
			'NOK',
			'HUF',
			'CZK',
			'ILS',
			'MXN',
			'MYR',
			'BRL',
			'PHP',
			'TWD',
			'THB',
			'TRY'
		);
		
		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}			

		$method_data = array();

		if ($status) {  
      		$method_data = array(
        		'code'       => 'solucao_integrada',
        		'title'      => $this->language->get('text_title'),
      			'terms'      => '',
				'sort_order' => $this->config->get('solucao_integrada_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
  	
  	public function getDadosFrete($order_id){
  		$query = $this->db->query("SELECT title,value FROM " . DB_PREFIX . "order_total WHERE order_id = '".$order_id."' AND code = 'shipping'");
  		
  		return $query->row;
  	}
  	
  	public function getDadosDescontos($order_id){
  		$query = $this->db->query("SELECT ABS(value) as valor FROM " . DB_PREFIX . "order_total
  										WHERE order_id = '".$order_id."'
  										AND code != 'shipping'
  										AND code != 'sub_total'
  										AND code != 'total'
  										AND value < 0");
  		 
  		return $query->row;
  	}  	
}
?>