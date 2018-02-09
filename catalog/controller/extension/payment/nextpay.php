<?php
class ControllerExtensionPaymentNextpay extends Controller {
	public function index() {
		$this->load->model('checkout/order');

		$this->load->language('extension/payment/nextpay');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_instruction'] = $this->language->get('text_instruction');

        $data['nextpay_error'] = '';

        if (!empty($this->config->get('nextpay_description_' . $this->config->get('config_language_id')))) {
            $data['nextpay_description'] = nl2br($this->config->get('nextpay_description_' . $this->config->get('config_language_id')));
        } else {
            $data['nextpay_description'] = '';
        }

        if (!empty($this->config->get('nextpay_order_comment_confirm_' . $this->config->get('config_language_id')))) {
            $data['nextpay_instruction'] = nl2br($this->config->get('nextpay_order_comment_confirm_' . $this->config->get('config_language_id')));
        } else {
            $data['nextpay_instruction'] = '';
        }

        $data['continue'] = $this->url->link('checkout/success');

		$order_id = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$currency_id = $order_info['currency_id'];

		$config_currency_id = $this->config->get('nextpay_order_currency_id');

		if ($currency_id != $config_currency_id) {
			$data['nextpay_error'] = $this->language->get('error_invalid_currency_id');
		}

		$currency_value = $order_info['currency_value'];

		if ($currency_value <= 0) {
			$data['nextpay_error'] = $this->language->get('error_invalid_currency_value');
		}

		return $this->load->view('extension/payment/nextpay', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'nextpay') {
			$this->load->language('extension/payment/nextpay');

			$this->load->model('checkout/order');

			if (!empty($this->config->get('nextpay_order_comment_confirm_' . $this->config->get('config_language_id')))) {
                $comment = $this->config->get('nextpay_order_comment_confirm_' . $this->config->get('config_language_id'));
            } else {
                $comment = '';
            }

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'), $comment, true);
			
			$this->cart->clear(); 
		}
	} 
	
	public function callback() {
		$orderId = $this->readParam("order_id");
		if ($orderId == null) {
			$this->error("Не передан ID заказа");
		}

		$sellerExtOrderId = $this->readParam("seller_ext_order_id");
		if ($sellerExtOrderId == null) {
			$this->error("Не передан параметр seller_ext_order_id");
		}

		$test = $this->readParam("test");

		$productId = $this->readParam("product_id");
		$commission = $this->readParam("commission");

		$orderHash =  $this->readParam("hash");
		if ($orderHash == null) {
			$this->error("Не передан параметр hash");
		}

		$cost_general = $this->readParam("cost_general");
		if ($cost_general == null) {
			$this->error("Не передан параметр cost_general");
		}
		if ($cost_general < 0) {
			$this->error("Неверное значение параметра cost_general");
		}

		$cost = $this->readParam("cost");
		if ($cost == null) {
			$this->error("Не передан параметр cost");
		}
		if ($cost <= 0) {
			$this->error("Неверное значение параметра cost");
		}

		$profit = $this->readParam("profit");
		if ($profit == null) {
			$this->error("Не передан параметр profit");
		}
		if ($profit < 0) {
			$this->error("Неверное значение параметра profit");
		}

		$currency = $this->readParam("currency");
		if ($currency == null) {
			$this->error("Не передан параметр currency");
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($sellerExtOrderId);
		if ($order_info == null) {
			$this->error("model_checkout_order->getOrder failed to find order by id");
		}

		if ($order_info['payment_code'] != "nextpay") {
			$this->error("invalid payment_code, expected: \"nextpay\", received from model: ".$order_info['payment_code']);
		}

		$currency_value = $order_info['currency_value'];
		if ($currency_value <= 0) {
			$this->error("invalid currency_value, expected: value >= 0, received from model: $currency_value");
		}

		$ext_order_cost_expected = $order_info['total'];
		$ext_order_cost_expected = $this->caclAmount($ext_order_cost_expected, $currency_value);

		if ($ext_order_cost_expected != $cost_general) {
			$this->error("invalid payment amount, expected: $ext_order_cost_expected, received: $cost_general");
		}

		$oc_product_id = $this->config->get('nextpay_product');
		if ($oc_product_id != $productId) {
			$this->error("invalid nextpay_product, expected: $oc_product_id, received: $productId");
		}

		$secret_key = $this->config->get('nextpay_key');

		//Проверка контрольной суммы
		$hash = "$test$productId$orderId$currency$cost_general$cost$profit$commission$secret_key";
		$hash = sha1($hash);

		if ($hash != $orderHash) {
			$this->error("Контрольные суммы не совпадают");
		}

		if ($test) {
			echo "ok";
			return;
		}

		ob_start();
		$order_status_id = $this->config->get('nextpay_order_status_id');
		$this->model_checkout_order->addOrderHistory($sellerExtOrderId, $order_status_id, "Заказ оплачен, ID заказа в системе nextpay.ru $orderId", true);
		ob_end_clean();

		echo "ok";
	}

	public function validate() {
		$sellerExtOrderId = $this->readParam("seller_ext_order_id");
		if ($sellerExtOrderId == null) {
			$this->error_invalid_order_data(1);
		}

		$cost_general = $this->readParam("cost_general");
		if ($cost_general == null) {
			$this->error_invalid_order_data(2);
		}
		if ($cost_general < 0) {
			$this->error_invalid_order_data(3);
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($sellerExtOrderId);
		if ($order_info == null) {
			$this->error_invalid_order_id();
		}

		if ($order_info['payment_code'] != "nextpay") {
			$this->error_invalid_order_data(5);
		}

		$currency_value = $order_info['currency_value'];
		if ($currency_value <= 0) {
			$this->error_invalid_order_data(6);
		}

		$ext_order_cost_expected = $order_info['total'];
		$ext_order_cost_expected = $this->caclAmount($ext_order_cost_expected, $currency_value);

		if ($ext_order_cost_expected != $cost_general) {
			$this->error_invalid_order_data(7);
		}

		echo "ok";
	}

    private function to1251($value) {
        return iconv("utf-8", "windows-1251", $value);
    }

    private function caclAmount($total, $currency_value) {
        $ret = $total * $currency_value;
        $ret = round($ret, 2);

        return $ret;
    }

	private function readParam($paramName) {
		$request = array_merge($this->request->post, $this->request->get);
		$ret = null;
		if (isset($request[$paramName])) {
			$ret = $request[$paramName];
		}

		return $ret;
	}
    
	private function error($msg) {
		$text = $this->to1251("Ошибка при обработке: $msg");
		echo $text;
		die();
	}

	private function error_invalid_order_id() {
		echo "invalid_order_id";
		die();
	}

	private function error_invalid_order_data($code = null) {
		echo "invalid_order_data";
		die();
	}
}
