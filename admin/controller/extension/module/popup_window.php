<?php
class ControllerExtensionModulePopupWindow extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('extension/module/popup_window');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('popup_window', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/module/popup_window', 'token=' . $this->session->data['token'], true));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_apply'] = $this->language->get('text_apply');
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_vision'] = $this->language->get('text_vision');
		$data['text_vision_help'] = $this->language->get('text_vision_help');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_text'] = $this->language->get('text_text');

		$data['token'] = $this->session->data['token'];

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/extension', 'type=module&token=' . $this->session->data['token'], true),
        );
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/popup_window', 'token=' . $this->session->data['token'], 'SSL')
		);

        $data['action'] = $this->url->link('extension/module/popup_window', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'type=module&token=' . $this->session->data['token'], true);
		
		if (isset($this->request->post['popup_window_setting'])) {
			$data['popup_window_setting'] = $this->request->post['popup_window_setting'];
		} elseif ($this->config->get('popup_window_setting')) {
			$data['popup_window_setting'] = $this->config->get('popup_window_setting');
		} else {
			$data['popup_window_setting'] = array(
				'modal_status' => false,
				'modal_header' => '',
				'modal_text'   => '',
				'modal_time'   => 0
			);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/popup_window.tpl', $data));
	}

	protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/popup_window')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}
