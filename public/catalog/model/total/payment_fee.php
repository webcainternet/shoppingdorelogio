<?php
class ModelTotalPaymentfee extends Model { 
	public function getTotal(&$total_data, &$total, &$taxes) { 
	
		if ($this->config->get('payment_fee_status') && (isset($this->session->data['payment_method']['code']) || isset($this->request->post['payment_code'])) ) { 
	
			$this->load->language('total/payment_fee'); 
		
			if ($this->customer->isLogged()) { 
			$customer_group_id = $this->customer->getGroupId(); 
			} else { 
			$customer_group_id = $this->config->get('config_customer_group_id'); 
			} 
			
			if (isset($this->session->data['payment_method']['code'])){ 
			$payment_method_code = $this->session->data['payment_method']['code']; 
			} else { 
			$payment_method_code = $this->request->post['payment_code']; 
			} 
			
			if (isset($this->session->data['payment_method']['title'])) { 
			$payment_method_name = $this->session->data['payment_method']['title']; 
			} elseif (isset($this->request->post['payment_title'])) { $payment_method_name = $this->request->post['payment_title']; 
			} else { 
			$payment_method_name = ''; 
			} 
			
			$subTotal = $this->cart->getSubTotal(); $paymentFees = $this->config->get('payment_fees'); 
			
			if ($paymentFees) { 
				foreach($paymentFees as $paymentFee) { 
				if ($paymentFee['payment_method'] == $payment_method_code) { 
				
					if ( is_numeric($paymentFee['customer_group_id']) && $customer_group_id != $paymentFee['customer_group_id'] ) { continue; } if (substr(trim($paymentFee['fee']),-1) == '%') { 
					$isPercent = true; 
					} else { 
					$isPercent = false; 
					} 
					
					if (substr(trim($paymentFee['fee']),0,1) == '-') { $isDiscount = true; } else { 
					$isDiscount = false; 
					} 
					
					if ($isDiscount) { 
					if (is_numeric($paymentFee['order_total']) && $subTotal < $paymentFee['order_total']) { continue; } 
					} else { 
					if (is_numeric($paymentFee['order_total']) && $subTotal > $paymentFee['order_total']) { continue; } 
					} 
					
					$totalDiff = 0; if ($isDiscount) { foreach ($this->cart->getProducts() as $product) { 
					$discount = 0; if ($isPercent) { 
					$discount = $product['total'] / 100 * substr(trim($paymentFee['fee']), 0, -1); 
					} else { 
					$discount = $paymentFee['fee'] * ($product['total'] / $subTotal); 
					} 
					
					if ($product['tax_class_id']) { 
					$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] + $discount), $product['tax_class_id']); foreach ($tax_rates as $tax_rate) { 
					if ($tax_rate['type'] == 'P') { 
					$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount']; 
					} 
					} 
					} 
					
					$totalDiff += $discount; 
					} 
					
					$myTitle = $payment_method_name . ' ' . $this->language->get('text_payment_discount'); 
					
					if ($isPercent) { $myTitle .= ' (' . $paymentFee['fee'] . ')'; } 
					} else { 
					
					if ($isPercent) { $totalDiff = $subTotal / 100 * substr(trim($paymentFee['fee']), 0, -1); 
					} else { 
					$totalDiff = $paymentFee['fee']; 
					} 
					$myTitle = $payment_method_name . ' ' . $this->language->get('text_payment_fee'); if ($isPercent) { 
					$myTitle .= ' (' . $paymentFee['fee'] . ')'; 
					} 
					
					if ($this->config->get('payment_fee_tax_class_id')) { 
					$tax_rates = $this->tax->getRates($totalDiff, $this->config->get('payment_fee_tax_class_id')); foreach ($tax_rates as $tax_rate) { 
					
					if (!isset($taxes[$tax_rate['tax_rate_id']])) { 
					$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount']; 
					} else { 
					$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount']; 
					} 
					} 
					} 
					} 
					
					$total_data[] = array( 
					'code' => 'payment_fee', 
					'title' => $myTitle, 
					'text' => $this->currency->format($totalDiff), 
					'value' => $totalDiff, 
					'sort_order' => $this->config->get('payment_fee_sort_order') 
					); 
					
					$total += $totalDiff; 
				} 
			} 
			} 
					
		} 
	} 
}