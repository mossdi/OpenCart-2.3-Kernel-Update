<?php
class ControllerExtensionModuleAutoSeoTitle extends Controller {
    private $error = array();
    public function index() {
        $this->load->language('extension/module/autoseotitle');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('autoseotitle', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/autoseotitle', 'token=' . $this->session->data['token'], true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else if (isset($this->session->data['error']) ) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }
        else {
            $data['error_warning'] = '';
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
            'text' => $this->language->get('text_title'),
            'href' => $this->url->link('extension/module/autoseotitle', 'token=' . $this->session->data['token'], true),
        );

        if (isset($this->request->post['autoseotitle_product'])) {
            $data['autoseotitle_product'] = $this->request->post['autoseotitle_product'];
        } else {
            $data['autoseotitle_product'] = $this->config->get('autoseotitle_product');
        }

        if (isset($this->request->post['autoseotitle_category'])) {
            $data['autoseotitle_category'] = $this->request->post['autoseotitle_category'];
        } else {
            $data['autoseotitle_category'] = $this->config->get('autoseotitle_category');
        }

        if (isset($this->request->post['autoseotitle_manufacturer'])) {
            $data['autoseotitle_manufacturer'] = $this->request->post['autoseotitle_manufacturer'];
        } else {
            $data['autoseotitle_manufacturer'] = $this->config->get('autoseotitle_manufacturer');
        }

        if (isset($this->request->post['autoseotitle_descr_product'])) {
            $data['autoseotitle_descr_product'] = $this->request->post['autoseotitle_descr_product'];
        } else {
            $data['autoseotitle_descr_product'] = $this->config->get('autoseotitle_descr_product');
        }

        if (isset($this->request->post['autoseotitle_descr_category'])) {
            $data['autoseotitle_descr_category'] = $this->request->post['autoseotitle_descr_category'];
        } else {
            $data['autoseotitle_descr_category'] = $this->config->get('autoseotitle_descr_category');
        }

        if (isset($this->request->post['autoseotitle_descr_manufacturer'])) {
            $data['autoseotitle_descr_manufacturer'] = $this->request->post['autoseotitle_descr_manufacturer'];
        } else {
            $data['autoseotitle_descr_manufacturer'] = $this->config->get('autoseotitle_descr_manufacturer');
        }

        if (isset($this->request->post['autoseotitle_keyw_product'])) {
            $data['autoseotitle_keyw_product'] = $this->request->post['autoseotitle_keyw_product'];
        } else {
            $data['autoseotitle_keyw_product'] = $this->config->get('autoseotitle_keyw_product');
        }

        if (isset($this->request->post['autoseotitle_keyw_category'])) {
            $data['autoseotitle_keyw_category'] = $this->request->post['autoseotitle_keyw_category'];
        } else {
            $data['autoseotitle_keyw_category'] = $this->config->get('autoseotitle_keyw_category');
        }

        if (isset($this->request->post['autoseotitle_keyw_manufacturer'])) {
            $data['autoseotitle_keyw_manufacturer'] = $this->request->post['autoseotitle_keyw_manufacturer'];
        } else {
            $data['autoseotitle_keyw_manufacturer'] = $this->config->get('autoseotitle_keyw_manufacturer');
        }

        if (isset($this->request->post['autoseotitle_page'])) {
            $data['autoseotitle_page'] = $this->request->post['autoseotitle_page'];
        } else {
            $data['autoseotitle_page'] = $this->config->get('autoseotitle_page');
        }

        $data['allowed_product_patterns']      = '[name],[meta_h1],[meta_title],[store_name],[price],[model],[category_name],[manufacturer_name]';
        $data['allowed_category_patterns']     = '[name],[meta_h1],[meta_title],[store_name]';
        $data['allowed_manufacturer_patterns'] = '[name],[meta_h1],[meta_title],[store_name]';

        $data['allowed_descr_product_patterns']      = '[name],[meta_h1],[meta_title],[store_name],[price],[model],[category_name],[manufacturer_name]';
        $data['allowed_descr_category_patterns']     = '[name],[meta_h1],[meta_title],[store_name]';
        $data['allowed_descr_manufacturer_patterns'] = '[name],[meta_h1],[meta_title],[store_name]';

        $data['allowed_keyw_product_patterns']      = '[name],[meta_h1],[meta_title],[store_name],[price],[model],[category_name],[manufacturer_name]';
        $data['allowed_keyw_category_patterns']     = '[name],[meta_h1],[meta_title],[store_name]';
        $data['allowed_keyw_manufacturer_patterns'] = '[name],[meta_h1],[meta_title],[store_name]';

        if (isset($this->request->post['autoseotitle_enable'])) {
            $data['autoseotitle_enable'] = $this->request->post['autoseotitle_enable'];
        } else {
            $data['autoseotitle_enable'] = $this->config->get('autoseotitle_enable');
        }

        if (isset($this->request->post['autoseotitle_rewrite'])) {
            $data['autoseotitle_rewrite'] = $this->request->post['autoseotitle_rewrite'];
        } else {
            $data['autoseotitle_rewrite'] = $this->config->get('autoseotitle_rewrite');
        }

        if (isset($this->request->post['autoseotitle_descr_rewrite'])) {
            $data['autoseotitle_descr_rewrite'] = $this->request->post['autoseotitle_descr_rewrite'];
        } else {
            $data['autoseotitle_descr_rewrite'] = $this->config->get('autoseotitle_descr_rewrite');
        }

        if (isset($this->request->post['autoseotitle_keyw_rewrite'])) {
            $data['autoseotitle_keyw_rewrite'] = $this->request->post['autoseotitle_keyw_rewrite'];
        } else {
            $data['autoseotitle_keyw_rewrite'] = $this->config->get('autoseotitle_keyw_rewrite');
        }

        $data['action'] = $this->url->link('extension/module/autoseotitle', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'type=module&token=' . $this->session->data['token'], true);

        $data['heading_title']             = $this->language->get('heading_title');
        $data['yes']                       = $this->language->get('text_yes');
        $data['no']                        = $this->language->get('text_no');
        $data['entry_status']              = $this->language->get('entry_status');
        $data['entry_product']             = $this->language->get('entry_product');
        $data['entry_category']            = $this->language->get('entry_category');
        $data['entry_manufacturer']        = $this->language->get('entry_manufacturer');
        $data['entry_page']                = $this->language->get('entry_page');
        $data['entry_rewrite']             = $this->language->get('entry_rewrite');
        $data['entry_descr_rewrite']       = $this->language->get('entry_descr_rewrite');
        $data['entry_keyw_rewrite']        = $this->language->get('entry_keyw_rewrite');
        $data['text_allowed_patern']       = $this->language->get('text_allowed_patern');
        $data['tab_settings']              = $this->language->get('tab_settings');
        $data['tab_help']                  = $this->language->get('tab_help');
        $data['button_save']               = $this->language->get('button_save');
        $data['button_cancel']             = $this->language->get('button_cancel');
        $data['text_edit']                 = $this->language->get('text_edit');
        $data['text_support']              = $this->language->get('text_support');
        $data['text_rewrite_help']         = $this->language->get('text_rewrite_help');
        $data['text_legend_description']   = $this->language->get('text_legend_description');
        $data['text_legend_keywords']      = $this->language->get('text_legend_keywords');
        $data['text_legend_title']         = $this->language->get('text_legend_title');

        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $key=>$language) {
            $data['languages'][$key] = $language;
            $data['languages'][$key]['image'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/autoseotitle', $data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/autoseotitle')) {
            $this->error['warning'] = $this->language->get('text_error_access');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install() {
    }

    public function uninstall() {
    }
}