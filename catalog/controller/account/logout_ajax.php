<?php
class ControllerAccountLogoutAjax extends Controller {
	public function index() {
		$this->load->language('account/logout');

		$data['text_message'] = $this->language->get('text_message');

		$this->response->setOutput($this->load->view('account/logout_ajax', $data));
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
}
