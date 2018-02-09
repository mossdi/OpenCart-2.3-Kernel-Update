<?php
class ControllerExtensionModuleModulesTab extends Controller {
    public function index ($setting) {
        static $module = 0;

        $this->document->addStyle('catalog/view/javascript/jquery/slick/slick.css');
        $this->document->addStyle('catalog/view/javascript/jquery/slick/slick-theme.css');
        $this->document->addScript('catalog/view/javascript/jquery/slick/slick.min.js');

        $data['popup_view_data'] = $this->config->get($this->config->get('config_theme') . '_popup_view_data');
        $data['popup_view_text'] = $this->language->load('common/popup_view');

        $this->load->language('extension/module/modules_tab');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['tab_featured'] = $this->language->get('tab_featured');
        $data['tab_special'] = $this->language->get('tab_special');
        $data['tab_latest'] = $this->language->get('tab_latest');

        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_instock'] = $this->language->get('text_instock');
        $data['text_nostock'] = $this->language->get('text_nostock');
        $data['text_pre_order'] = $this->language->get('text_pre_order');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_discount'] = $this->language->get('text_discount');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_to_manufacturer_page'] = $this->language->get('text_to_manufacturer_page');
        $data['text_special_all'] = $this->language->get('text_special_all');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_no_stock'] = $this->language->get('button_no_stock');
        $data['button_pre_order'] = $this->language->get('button_pre_order');

        $data['stock_checkout'] = $this->config->get('config_stock_checkout');
        $data['stock_display'] = $this->config->get('config_stock_display');

        $data['special'] = $this->url->link('product/special');

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/parent_product');
        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        $module_info['products_display'] = $setting['products_display'];

        //Featured
        if ($setting['featured_products'] && !empty($setting['parent_product'])) {
            $data['unique_id'] = 'featured';

            if (empty($setting['limit'])) {
                $setting['limit'] = 6;
            }

            $featured_products = array();

            $featured_categories = array_slice($setting['parent_product'], 0, (int)$setting['limit']);

            foreach ($featured_categories as $featured_category_id) {
                $featured_product_info = $this->model_catalog_category->getCategory($featured_category_id);

                if ($featured_product_info) {
                    $featured_products[] = $featured_product_info;
                }
            }

            $data['featured_products'] = $this->model_catalog_parent_product->getProductsAnyTypeView($products_type = 'products', $featured_products, $module_info, $url = '', $in_stock = true, $data, array());
        } else {
            $data['featured_products'] = false;
        }

        //Specials
        if ($setting['special_products']) {
            $data['unique_id'] = 'special';

            $special_filter = array(
                'filter_manufacturer_id' => false,
                'filter_in_stock'        => true,
                'sort'                   => '',
                'order'                  => '',
                'start'                  => 0,
                'limit'                  => !empty($setting['limit']) ? $setting['limit'] : 6
            );

            $special_products = $this->model_catalog_parent_product->getSpecialProductsAnyType($special_filter);

            $data['special_products'] = $this->model_catalog_parent_product->getProductsAnyTypeView($products_type = 'products_special', $special_products, $module_info, $url = '', $in_stock = true, $data, $special_filter);
        } else {
            $data['special_products'] = false;
        }

        //Latest
        if ($setting['latest_products']) {
            $data['unique_id'] = 'latest';

            $latest_filter = array(
                'filter_manufacturer_id' => false,
                'filter_in_stock'        => true,
                'sort'                   => '',
                'order'                  => '',
                'start'                  => 0,
                'limit'                  => !empty($setting['limit']) ? $setting['limit'] : 6
            );

            $latest_products = $this->model_catalog_parent_product->getLatestProductsAnyType($latest_filter);

            $data['latest_products'] = $this->model_catalog_parent_product->getProductsAnyTypeView($products_type = 'products', $latest_products, $module_info, $url = '', $in_stock = true, $data, $latest_filter);
        } else {
            $data['latest_products'] = false;
        }

        $data['module'] = $module++;

        return $this->load->view('extension/module/modules_tab', $data);
    }
}
