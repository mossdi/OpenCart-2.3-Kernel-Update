<?php
class ControllerSettingFastMaintenance extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/setting');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            //$this->model_setting_setting->editSetting('config', $this->request->post);
			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $this->request->post['config_maintenance']);
			$this->response->redirect($this->url->link('setting/fast_maintenance','token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->language('setting/fast_maintenance');

		$this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_maintenance'] = $this->language->get('entry_maintenance');
		$data['help_maintenance'] = $this->language->get('help_maintenance');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/fast_maintenance', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('setting/fast_maintenance', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];



		if (isset($this->request->post['config_maintenance'])) {
			$data['config_maintenance'] = $this->request->post['config_maintenance'];
		} else {
			$data['config_maintenance'] = $this->config->get('config_maintenance');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/fast_maintenance.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/fast_maintenance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}