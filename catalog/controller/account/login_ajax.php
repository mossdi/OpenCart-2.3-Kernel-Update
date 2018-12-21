<?php
class ControllerAccountLoginAjax extends Controller {
	public function login() {
		$this->load->model('account/customer');

		$this->load->language('account/login');

		$data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_loading'] = $this->language->get('text_loading');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');

		$data['forgotten'] = $this->url->link('account/forgotten', '', true);

		$this->response->setOutput($this->load->view('account/login_ajax', $data));
	}

    public function logout() {
        if ($this->customer->isLogged()) {
            $this->customer->logout();

            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['order_id']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
        }
    }

	public function validate() {
	    $json = array();
	    $json['warning'] = false;

        $this->load->model('account/customer');
        $this->load->language('account/login');

		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$json['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$json['warning'] = $this->language->get('error_approved');
		}

		if (empty($json['warning'])) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$json['warning'] = $this->language->get('error_login');

				$this->model_account_customer->addLoginAttempt($this->request->post['email']);
			} else {
				$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
			}
		}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}
