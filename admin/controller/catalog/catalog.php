<?php
class ControllerCatalogCatalog extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('catalog/catalog');

        $this->load->model('catalog/catalog');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getForm();
    }

    public function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = $this->language->get('text_edit');
        $data['text_default'] = $this->language->get('text_default');

        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_h1'] = $this->language->get('entry_meta_h1');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_catalog_display'] = $this->language->get('entry_catalog_display');
        $data['entry_keyword'] = $this->language->get('entry_keyword');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/catalog', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('catalog/catalog/edit', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $catalog_info = $this->model_catalog_catalog->getCatalog();

        if (isset($this->request->post['keyword'])) {
            $data['keyword'] = $this->request->post['keyword'];
        } elseif (!empty($catalog_info)) {
            $data['keyword'] = $catalog_info['keyword'];
        } else {
            $data['keyword'] = '';
        }

        if (isset($this->request->post['catalog_display'])) {
            $data['catalog_display'] = $this->request->post['catalog_display'];
        } elseif (!empty($catalog_info)) {
            $data['catalog_display'] = $catalog_info['catalog_display'];
        } else {
            $data['catalog_display'] = false;
        }

        if (isset($this->request->post['catalog_description'])) {
            $data['catalog_description'] = $this->request->post['catalog_description'];
        } else {
            $data['catalog_description'] = $this->model_catalog_catalog->getCatalogDescription();
        }

        $catalog_templates = glob(DIR_CATALOG . 'view/theme/'  . $this->config->get('config_theme') . '/template/custom/catalog/*.tpl');

        $data['catalog_templates'] = array();

        if ($catalog_templates) {
            foreach ($catalog_templates as $template) {
                $data['catalog_templates'][] = array(
                    'name'  => substr(strrchr($template, '/'), 1)
                );
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/catalog', $data));
    }

    public function edit() {
        $this->load->language('catalog/catalog');

        $this->load->model('catalog/catalog');

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_catalog->editCatalog($this->request->post);

            $this->response->redirect($this->url->link('catalog/catalog', 'token=' . $this->session->data['token'], true));
        }

        $this->getForm();
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/catalog')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['catalog_description'] as $language_id => $value) {
            if (utf8_strlen($value['meta_title']) > 255) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }
}