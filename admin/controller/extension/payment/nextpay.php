<?php
class ControllerExtensionPaymentNextpay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/nextpay');

		$this->load->model('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nextpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)); 
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_nextpay'] = $this->language->get('text_nextpay');

        $data['tab_general'] = $this->language->get('tab_general');

        $data['entry_nextpay_key'] = $this->language->get('entry_nextpay_key');
        $data['entry_nextpay_product'] = $this->language->get('entry_nextpay_product');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_status_confirm'] = $this->language->get('entry_order_status_confirm');
        $data['entry_order_comment_confirm'] = $this->language->get('entry_order_comment_confirm');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_order_currency'] = $this->language->get('entry_order_currency');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['action'] = $this->url->link('extension/payment/nextpay', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/nextpay', 'token=' . $this->session->data['token'], true)
        );

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['nextpay_key'])) {
			$data['nextpay_key'] = $this->request->post['nextpay_key'];
		} else {
			$data['nextpay_key'] = $this->config->get('nextpay_key');
		}

		if (isset($this->request->post['nextpay_product'])) {
			$data['nextpay_product'] = $this->request->post['nextpay_product'];
		} else {
			$data['nextpay_product'] = $this->config->get('nextpay_product');
		}

        if (isset($this->request->post['nextpay_order_currency_id'])) {
            $data['nextpay_order_currency_id'] = $this->request->post['nextpay_order_currency_id'];
        } else {
            $data['nextpay_order_currency_id'] = $this->config->get('nextpay_order_currency_id');
        }

        foreach ($languages as $language) {
            if (isset($this->request->post['nextpay_description_' . $language['language_id']])) {
                $data['nextpay_description_' . $language['language_id']] = $this->request->post['nextpay_description_' . $language['language_id']];
            } else {
                $data['nextpay_description_' . $language['language_id']] = $this->config->get('nextpay_description_' . $language['language_id']);
            }

            if (isset($this->request->post['nextpay_order_comment_confirm_' . $language['language_id']])) {
                $data['nextpay_order_comment_confirm_' . $language['language_id']] = $this->request->post['nextpay_order_comment_confirm_' . $language['language_id']];
            } else {
                $data['nextpay_order_comment_confirm_' . $language['language_id']] = $this->config->get('nextpay_order_comment_confirm_' . $language['language_id']);
            }
        }

		if (isset($this->request->post['nextpay_order_status_id'])) {
			$data['nextpay_order_status_id'] = $this->request->post['nextpay_order_status_id'];
		} else {
			$data['nextpay_order_status_id'] = $this->config->get('nextpay_order_status_id');
		}

        if (isset($this->request->post['nextpay_status'])) {
            $data['nextpay_status'] = $this->request->post['nextpay_status'];
        } else {
            $data['nextpay_status'] = $this->config->get('nextpay_status');
        }

        if (isset($this->request->post['nextpay_sort_order'])) {
            $data['nextpay_sort_order'] = $this->request->post['nextpay_sort_order'];
        } else {
            $data['nextpay_sort_order'] = $this->config->get('nextpay_sort_order');
        }

		$this->load->model('localisation/currency');

		$data['order_currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->error['nextpay_key'])) {
			$data['error_nextpay_key'] = $this->error['nextpay_key'];
		} else {
			$data['error_nextpay_key'] = '';
		}

		if (isset($this->error['nextpay_product'])) {
			$data['error_nextpay_product'] = $this->error['nextpay_product'];
		} else {
			$data['error_nextpay_product'] = '';
		}

		if (isset($this->error['nextpay_order_status_id'])) {
			$data['error_nextpay_order_status_id'] = $this->error['nextpay_order_status_id'];
		} else {
			$data['error_nextpay_order_status_id'] = '';
		}

		if (isset($this->error['nextpay_error_order_currency_id'])) {
			$data['error_order_currency_id'] = $this->error['nextpay_error_order_currency_id'];
		} else {
			$data['error_order_currency_id'] = '';
		}

		$data['languages'] = $languages;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left'); 
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/nextpay', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/nextpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['nextpay_key']) {
			$this->error['warning'] = $this->language->get('error_nextpay_key');
		}

		if (!$this->request->post['nextpay_product']) {
			$this->error['warning'] = $this->language->get('error_nextpay_product');
		}

		if (!$this->request->post['nextpay_order_currency_id']) {
			$this->error['warning'] = $this->language->get('error_nextpay_order_currency_id');
		}

		if (!$this->request->post['nextpay_order_status_id']) {
			$this->error['warning'] = $this->language->get('error_nextpay_order_status_id');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
