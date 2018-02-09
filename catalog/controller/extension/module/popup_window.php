<?php
class ControllerExtensionModulePopupWindow extends Controller {
	public function index() {
		$this->load->model('catalog/product');

		$settings = $this->config->get('popup_window_setting');

		$data['modal_heading_title'] = $settings['modal_header'];
		$data['modal_message'] = html_entity_decode((str_replace("img src", "img class='img-responsive' src", $settings['modal_text'])), ENT_QUOTES, 'UTF-8');
		$data['modal_time'] = $settings['modal_time'];

		$this->response->setOutput($this->load->view('extension/module/popup_window.tpl', $data));
	}

	public function statusRequest() {
        $json = array();

        $settings = $this->config->get('popup_window_setting');

        if (!empty($settings) && $settings['modal_status']) {
            $json['status'] = true;
        } else {
            $json['status'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
