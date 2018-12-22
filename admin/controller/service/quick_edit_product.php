<?php
class ControllerServiceQuickEditProduct extends Controller {
    public function index() {
        $this->load->language('catalog/product');

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('setting/store');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/attribute');
        $this->load->model('localisation/language');

        $this->getForm();
    }

    public function save() {
        $product_data = array();

        parse_str(html_entity_decode($this->request->post['data']), $product_data);

        $json['warning'] = $this->validate($product_data);

        if (!$json['warning']) {
            $this->load->model('service/quick_edit_product');



            $this->model_service_quick_edit_product->editProduct($this->request->post['product_id'], $product_data);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate($data = array()) {
        return false;
    }

    private function getForm() {
        $product_id = $this->request->get['product_id'];

        $product_info = $this->model_catalog_product->getProduct($product_id);

        $data['text_form'] = $this->language->get('text_add');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_links'] = $this->language->get('tab_links');
        $data['tab_attribute'] = $this->language->get('tab_attribute');

        $data['help_keyword'] = $this->language->get('help_keyword');
        $data['help_manufacturer'] = $this->language->get('help_manufacturer');
        $data['help_sku'] = $this->language->get('help_sku');
        $data['help_tag'] = $this->language->get('help_tag');
        $data['help_category'] = $this->language->get('help_category');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_sku'] = $this->language->get('entry_sku');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $data['entry_main_category'] = $this->language->get('entry_main_category');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_attribute'] = $this->language->get('entry_attribute');
        $data['entry_text'] = $this->language->get('entry_text');

        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');

        $data['product_description'] = $this->model_catalog_product->getProductDescriptions($product_id);

        $data['model'] = $product_info['model'];
        $data['sku'] = $product_info['sku'];
        $data['keyword'] = $product_info['keyword'];
        $data['status'] = $product_info['status'];
        $data['manufacturer_id'] = $product_info['manufacturer_id'];

        $data['product_id'] = $product_id;

        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

        $data['manufacturer'] = $manufacturer_info ? $manufacturer_info['name'] : null;

        $data['main_category'] = array();

        $main_category_info = $this->model_catalog_category->getCategory($this->model_catalog_product->getProductMainCategoryId($product_id));

        if ($main_category_info) {
            $data['main_category'] = array (
                'category_id' => $main_category_info['category_id'],
                'name'        => ($main_category_info['path']) ? $main_category_info['path'] . ' &gt; ' . $main_category_info['name'] : $main_category_info['name']
            );
        }

        $data['product_categories'] = array();

        $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['product_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        $data['product_attributes'] = array();

        $product_attributes = $this->model_catalog_product->getProductAttributes($product_id);

        foreach ($product_attributes as $product_attribute) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

            if ($attribute_info) {
                $data['product_attributes'][] = array(
                    'attribute_id'                  => $product_attribute['attribute_id'],
                    'name'                          => $attribute_info['name'],
                    'product_attribute_description' => $product_attribute['product_attribute_description']
                );
            }
        }

        $data['token'] = $this->session->data['token'];

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->response->setOutput($this->load->view('service/product_quick_edit_form', $data));
    }
}
