<?php
class ControllerExtensionModuleLastModified extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/last_modified');
		$this->config->load('last_modified');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('last_modified', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');


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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/last_modified', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/last_modified', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['last_modified_enable'])) {
			$data['last_modified_enable'] = $this->request->post['last_modified_enable'];
		} else {
			$data['last_modified_enable'] = $this->config->get('last_modified_enable');
		}
		$data['entry_status'] = $this->language->get('entry_status');

		if (isset($this->request->post['last_modified_category'])) {
			$data['last_modified_category'] = $this->request->post['last_modified_category'];
		} else {
			$data['last_modified_category'] = $this->config->get('last_modified_category');
		}

		if (isset($this->request->post['last_modified_category_module'])) {
			$data['last_modified_category_module'] = $this->request->post['last_modified_category_module'];
		} else {
			$data['last_modified_category_module'] = $this->config->get('last_modified_category_module');
		}
		if (isset($this->request->post['last_modified_category_product'])) {
			$data['last_modified_category_product'] = $this->request->post['last_modified_category_product'];
		} else {
			$data['last_modified_category_product'] = $this->config->get('last_modified_category_product');
		}
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_changes_modules'] = $this->language->get('entry_changes_modules');
		$data['entry_changes_product'] = $this->language->get('entry_changes_product');
		$data['text_also_manufacturer'] = $this->language->get('text_also_manufacturer');

		if (isset($this->request->post['last_modified_product'])) {
			$data['last_modified_product'] = $this->request->post['last_modified_product'];
		} else {
			$data['last_modified_product'] = $this->config->get('last_modified_product');
		}
		if (isset($this->request->post['last_modified_product_module'])) {
			$data['last_modified_product_module'] = $this->request->post['last_modified_product_module'];
		} else {
			$data['last_modified_product_module'] = $this->config->get('last_modified_product_module');
		}
		$data['entry_product'] = $this->language->get('entry_product');

		if (isset($this->request->post['last_modified_information'])) {
			$data['last_modified_information'] = $this->request->post['last_modified_information'];
		} else {
			$data['last_modified_information'] = $this->config->get('last_modified_information');
		}

		if (isset($this->request->post['last_modified_information_module'])) {
			$data['last_modified_information_module'] = $this->request->post['last_modified_information_module'];
		} else {
			$data['last_modified_information_module'] = $this->config->get('last_modified_information_module');
		}
		$data['entry_information'] = $this->language->get('entry_information');

		if (isset($this->request->post['last_modified_home'])) {
			$data['last_modified_home'] = $this->request->post['last_modified_home'];
		} else {
			$data['last_modified_home'] = $this->config->get('last_modified_home');
		}
		$data['entry_home'] = $this->language->get('entry_home');

		if (isset($this->request->post['last_modified_caching'])) {
			$data['last_modified_caching'] = $this->request->post['last_modified_caching'];
		} else {
			$data['last_modified_caching'] = $this->config->get('last_modified_caching');
		}
		$data['entry_caching'] = $this->language->get('entry_caching');

		if (isset($this->request->post['last_modified_expires'])) {
			$data['last_modified_expires'] = $this->request->post['last_modified_expires'];
		} else {
			$data['last_modified_expires'] = $this->config->get('last_modified_expires');
		}
		$data['entry_expires'] = $this->language->get('entry_expires');

		if (isset($this->request->post['last_modified_change_module'])) {
			$data['last_modified_change_module'] = $this->request->post['last_modified_change_module'];
		} else {
			$data['last_modified_change_module'] = $this->config->get('last_modified_change_module');
		}
		$data['entry_changes_modules'] = $this->language->get('entry_changes_modules');
		$data['entry_changes_modules_help'] = $this->language->get('entry_changes_modules_help');
		
		$data['tab_help'] = $this->language->get('tab_help');
		$data['tab_settings'] = $this->language->get('tab_settings');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/last_modified', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/last_modified')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "last_modified` (
			  `last_modified_id` int(11) NOT NULL AUTO_INCREMENT,
			  `layoute_route` varchar(255) collate utf8_bin NOT NULL,
			  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`last_modified_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
		");

        $res = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "information` WHERE Field = 'date_modified'");
        if (!$res->num_rows) {
            $sql = "ALTER TABLE `" . DB_PREFIX . "information` ADD `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
            $this->db->query($sql);
            $this->db->query("UPDATE `" . DB_PREFIX . "information` SET `date_modified` = NOW()");
        }

        $res = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "manufacturer` WHERE Field = 'date_modified'");
        if (!$res->num_rows) {
            $sql = "ALTER TABLE `" . DB_PREFIX . "manufacturer` ADD `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
            $this->db->query($sql);
            $this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET  `date_modified` = NOW()");
        }

        $this->load->model('extension/event');
        $events = $this->getEvents();
        foreach ($events as $code=>$value) {
            $this->model_extension_event->deleteEvent($code);
            $this->model_extension_event->addEvent($code, $value['trigger'], $value['action'], 1);
        }
    }

    public function uninstall() {
        $this->db->query("DROP TABLE " . DB_PREFIX . "last_modified");
        $this->load->model('extension/event');
        $events = $this->getEvents();
        foreach ($events as $code=>$value) {
            $this->model_extension_event->deleteEvent($code);
        }
    }

    private function getEvents(){

        $events = array(
            'lm_EditLayoutBefore' => array(
                'trigger' => 'admin/model/design/layout/editLayout/before',
                'action'  => 'event/last_modified/changeLayoutsDate',
            ),
            'lm_EditSettingBefore' => array(
                'trigger' => 'admin/model/setting/setting/editSetting/before',
                'action'  => 'event/last_modified/changeEditSetting',
            ),
            'lm_EditModuleBefore' => array(
                'trigger' => 'admin/model/extension/module/editModule/before',
                'action'  => 'event/last_modified/changeModuleDate',
            ),
            'lm_AddProductAfter' => array(
                'trigger' => 'admin/model/catalog/product/addProduct/after',
                'action'  => 'event/last_modified/changeProductDate',
            ),
            'lm_EditProductBefore' => array(
                'trigger' => 'admin/model/catalog/product/editProduct/before',
                'action'  => 'event/last_modified/changeProductDate',
            ),
            'lm_EditProductAfter' => array(
                'trigger' => 'admin/model/catalog/product/editProduct/after',
                'action'  => 'event/last_modified/changeProductDate',
            ),
            'lm_DeleteProductBefore' => array(
                'trigger' => 'admin/model/catalog/product/deleteProduct/before',
                'action'  => 'event/last_modified/changeProductDate',
            ),
            'lm_EditCategoryBefore' => array(
                'trigger' => 'admin/model/catalog/category/editCategory/before',
                'action'  => 'event/last_modified/changeCategoryDate',
            ),
            'lm_EditCategoryAfter' => array(
                'trigger' => 'admin/model/catalog/category/editCategory/after',
                'action'  => 'event/last_modified/changeCategoryDate',
            )
        );
        return $events;
    }
}