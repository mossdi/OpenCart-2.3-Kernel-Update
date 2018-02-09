<?php
class ControllerExtensionModuleModulesTab extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/modules_tab');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('modules_tab', $this->request->post);
            } else {
                $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_by_default'] = $this->language->get('text_by_default');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_animatetabs'] = $this->language->get('entry_animatetabs');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_latest'] = $this->language->get('entry_latest');
        $data['entry_bestseller'] = $this->language->get('entry_bestseller');
        $data['entry_special'] = $this->language->get('entry_special');
        $data['entry_featured'] = $this->language->get('entry_featured');
        $data['entry_mostviewed'] = $this->language->get('entry_mostviewed');
        $data['entry_template_products'] = $this->language->get('entry_template_products');

        $data['help_product'] = $this->language->get('help_product');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
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

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/modules_tab', 'token=' . $this->session->data['token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/modules_tab', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/modules_tab', 'token=' . $this->session->data['token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/modules_tab', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['animatetabsshow'])) {
            $data['animatetabsshow'] = $this->request->post['animatetabsshow'];
        } elseif (!empty($module_info)) {
            $data['animatetabsshow'] = $module_info['animatetabsshow'];
        } else {
            $data['animatetabsshow'] = 0;
        }

        if (isset($this->request->post['products_display'])) {
            $data['products_display'] = $this->request->post['products_display'];
        } elseif (!empty($module_info)) {
            $data['products_display'] = $module_info['products_display'];
        } else {
            $data['products_display'] = 0;
        }

        if (isset($this->request->post['special_products'])) {
            $data['special_products'] = $this->request->post['special_products'];
        } elseif (!empty($module_info)) {
            $data['special_products'] = $module_info['special_products'];
        } else {
            $data['special_products'] = 0;
        }

        if (isset($this->request->post['featured_products'])) {
            $data['featured_products'] = $this->request->post['featured_products'];
        } elseif (!empty($module_info)) {
            $data['featured_products'] = $module_info['featured_products'];
        } else {
            $data['featured_products'] = 0;
        }

        if (isset($this->request->post['latest_products'])) {
            $data['latest_products'] = $this->request->post['latest_products'];
        } elseif (!empty($module_info)) {
            $data['latest_products'] = $module_info['latest_products'];
        } else {
            $data['latest_products'] = 0;
        }

        if (isset($this->request->post['bestseller_products'])) {
            $data['bestseller_products'] = $this->request->post['bestseller_products'];
        } elseif (!empty($module_info)) {
            $data['bestseller_products'] = $module_info['bestseller_products'];
        } else {
            $data['bestseller_products'] = 0;
        }

        if (isset($this->request->post['mostviewed_products'])) {
            $data['mostviewed_products'] = $this->request->post['mostviewed_products'];
        } elseif (!empty($module_info)) {
            $data['mostviewed_products'] = $module_info['mostviewed_products'];
        } else {
            $data['mostviewed_products'] = 1;
        }

        if (isset($this->request->post['limit'])) {
            $data['limit'] = $this->request->post['limit'];
        } elseif (!empty($module_info)) {
            $data['limit'] = $module_info['limit'];
        } else {
            $data['limit'] = 6;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        $products_templates = glob(DIR_CATALOG . 'view/theme/'  . $this->config->get('config_theme') . '/template/custom/category/category_products/*.tpl');

        $data['products_templates'] = array();

        if ($products_templates) {
            foreach ($products_templates as $template) {
                $data['products_templates'][] = array(
                    'name'  => basename($template)
                );
            }
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('catalog/category');

        $data['parent_products'] = array();

        if (isset($this->request->post['parent_product'])) {
            $parent_products = $this->request->post['parent_product'];
        } elseif (!empty($module_info['parent_product'])) {
            $parent_products = $module_info['parent_product'];
        } else {
            $parent_products = array();
        }

        if (!empty($parent_products)) {
            foreach ($parent_products as $parent_product_id) {
                $parent_product_info = $this->model_catalog_category->getCategory($parent_product_id);

                if ($parent_product_info && $parent_product_info['product_display']) {
                    $data['parent_products'][] = array(
                        'id'   => $parent_product_info['category_id'],
                        'name' => ($parent_product_info['path']) ? $parent_product_info['path'] . ' &gt; ' . $parent_product_info['name'] : $parent_product_info['name']
                    );
                }
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/modules_tab', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/modules_tab')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if(!isset($this->request->post['special_products'])){
            $this->request->post['special_products'] = 0;
        }

        if(!isset($this->request->post['latest_products'])){
            $this->request->post['latest_products'] = 0;
        }

        if(!isset($this->request->post['featured_products'])){
            $this->request->post['featured_products'] = 0;
        }

        if(!isset($this->request->post['bestseller_products'])){
            $this->request->post['bestseller_products'] = 0;
        }

        return !$this->error;
    }
}
