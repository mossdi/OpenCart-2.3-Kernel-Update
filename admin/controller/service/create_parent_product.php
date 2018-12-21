<?php
class ControllerServiceCreateParentProduct extends Controller {
    public function index() {
        $this->load->language('catalog/category');

        $this->load->model('catalog/product');
        $this->load->model('setting/store');
        $this->load->model('catalog/manufacturer');
        $this->load->model('localisation/language');

        $this->getForm();
    }

    public function create() {
        $category_data = array();

        parse_str(html_entity_decode($this->request->post['data']), $category_data);

        $json['warning'] = $this->validate($category_data);

        if (!$json['warning']) {
            $this->load->model('catalog/category');

            $category_data['column']     = 1;
            $category_data['sort_order'] = 0;
            $category_data['status']     = 1;

            $this->model_catalog_category->addCategory($category_data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate($data = array()) {
        $result = false;

        if (empty($data['product_related'])) {
            $result = true;
        }

        foreach ($data['category_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
                $result = true;
            }

            if (utf8_strlen($value['meta_title']) > 255) { //mod
                $result = true;
            }
        }

        return $result;
    }

    private function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = $this->language->get('text_add');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_by_default'] = $this->language->get('text_by_default');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_default'] = $this->language->get('text_default');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_custom_template'] = $this->language->get('tab_custom_template');
        $data['tab_related'] = $this->language->get('tab_related');

        $data['button_login'] = $this->language->get('button_login');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_parent'] = $this->language->get('entry_parent');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_template'] = $this->language->get('entry_template');
        $data['entry_add_description'] = $this->language->get('entry_add_description');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_related'] = $this->language->get('entry_related');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_keyword'] = $this->language->get('entry_keyword');

        $data['help_keyword'] = $this->language->get('help_keyword');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['token'] = $this->session->data['token'];

        $product_templates = glob(DIR_CATALOG . 'view/theme/' . $this->config->get('config_theme') . '/template/custom/product/*.tpl');

        $data['product_templates'] = array();

        if ($product_templates) {
            foreach ($product_templates as $template) {
                $data['product_templates'][] = array(
                    'name'  => basename($template)
                );
            }
        }

        $data['category_name_example'] = null;

        $data['products_related'] = array();

        foreach ($this->request->get['selected'] as $product_id) {
            $product = $this->model_catalog_product->getProduct($product_id);

            if (!empty($product)) {
                if (!$data['category_name_example']) {
                    $data['category_name_example'] = $product['name'];
                }

                $data['products_related'][] = $product;
            }
        }

        $data['stores'] = $this->model_setting_store->getStores();

        $data['category_store'] = array(0);

        $this->response->setOutput($this->load->view('catalog/product_parent_form', $data));
    }
}
