<?php
class ControllerExtensionThemeDMProject extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/theme/dm_project');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('catalog/option');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('dm_project', $this->request->post, $this->request->get['store_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=theme', true));
        }

        $data['token'] = $this->session->data['token'];

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_product'] = $this->language->get('tab_product');
        $data['tab_fast_view'] = $this->language->get('tab_fast_view');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_map'] = $this->language->get('tab_map');
        $data['tab_filter'] = $this->language->get('tab_filter');

        $data['tab_payment_details'] = $this->language->get('tab_payment_details');

        $data['entry_directory'] = $this->language->get('entry_directory');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_product_limit'] = $this->language->get('entry_product_limit');
        $data['entry_product_description_length'] = $this->language->get('entry_product_description_length');
        $data['entry_image_category'] = $this->language->get('entry_image_category');
        $data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
        $data['entry_manufacturer_image_thumb'] = $this->language->get('entry_manufacturer_image_thumb');
        $data['entry_image_popup'] = $this->language->get('entry_image_popup');
        $data['entry_image_product'] = $this->language->get('entry_image_product');
        $data['entry_manufacturer_image_product'] = $this->language->get('entry_manufacturer_image_product');
        $data['entry_filter_manufacturer_image'] = $this->language->get('entry_filter_manufacturer_image');
        $data['entry_image_additional'] = $this->language->get('entry_image_additional');
        $data['entry_image_related'] = $this->language->get('entry_image_related');
        $data['entry_image_compare'] = $this->language->get('entry_image_compare');
        $data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
        $data['entry_image_cart'] = $this->language->get('entry_image_cart');
        $data['entry_image_location'] = $this->language->get('entry_image_location');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_heading'] = $this->language->get('entry_heading');
        $data['entry_no_stock_info'] = $this->language->get('entry_no_stock_info');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_description_tab'] = $this->language->get('entry_description_tab');
        $data['entry_specification_tab'] = $this->language->get('entry_specification_tab');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_description_max'] = $this->language->get('entry_description_max');
        $data['entry_wishlist'] = $this->language->get('entry_wishlist');
        $data['entry_compare'] = $this->language->get('entry_compare');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');
        $data['entry_additional_width'] = $this->language->get('entry_additional_width');
        $data['entry_additional_height'] = $this->language->get('entry_additional_height');
        $data['entry_map_api_key'] = $this->language->get('entry_map_api_key');
        $data['entry_map_zoom'] = $this->language->get('entry_map_zoom');
        $data['entry_map_marker'] = $this->language->get('entry_map_marker');
        $data['entry_map_longitude'] = $this->language->get('entry_map_longitude');
        $data['entry_map_latitude'] = $this->language->get('entry_map_latitude');
        $data['entry_attribute_filters'] = $this->language->get('entry_attribute_filters');
        $data['entry_attribute_filters_explode'] = $this->language->get('entry_attribute_filters_explode');
        $data['entry_attribute_required'] = $this->language->get('entry_attribute_required');

        $data['entry_add_attachment'] = $this->language->get('entry_add_attachment');
        $data['entry_payment_receiver_name'] = $this->language->get('entry_payment_receiver_name');
        $data['entry_payment_receiver_inn'] = $this->language->get('entry_payment_receiver_inn');
        $data['entry_payment_receiver_account'] = $this->language->get('entry_payment_receiver_account');
        $data['entry_payment_receiver_bank_name'] = $this->language->get('entry_payment_receiver_bank_name');
        $data['entry_payment_receiver_bank_bic'] = $this->language->get('entry_payment_receiver_bank_bic');
        $data['entry_payment_receiver_bank_cor_acct'] = $this->language->get('entry_payment_receiver_bank_cor_acct');
        $data['entry_payment_receiver_kpp'] = $this->language->get('entry_payment_receiver_kpp');
        $data['entry_payment_receiver_address'] = $this->language->get('entry_payment_receiver_address');

        $data['help_product_limit'] = $this->language->get('help_product_limit');
        $data['help_product_description_length'] = $this->language->get('help_product_description_length');
        $data['help_directory'] = $this->language->get('help_directory');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['product_limit'])) {
            $data['error_product_limit'] = $this->error['product_limit'];
        } else {
            $data['error_product_limit'] = '';
        }

        if (isset($this->error['product_description_length'])) {
            $data['error_product_description_length'] = $this->error['product_description_length'];
        } else {
            $data['error_product_description_length'] = '';
        }

        if (isset($this->error['image_category'])) {
            $data['error_image_category'] = $this->error['image_category'];
        } else {
            $data['error_image_category'] = '';
        }

        if (isset($this->error['image_thumb'])) {
            $data['error_image_thumb'] = $this->error['image_thumb'];
        } else {
            $data['error_image_thumb'] = '';
        }

        if (isset($this->error['manufacturer_image_thumb'])) {
            $data['error_manufacturer_image_thumb'] = $this->error['manufacturer_image_thumb'];
        } else {
            $data['error_manufacturer_image_thumb'] = '';
        }

        if (isset($this->error['image_popup'])) {
            $data['error_image_popup'] = $this->error['image_popup'];
        } else {
            $data['error_image_popup'] = '';
        }

        if (isset($this->error['image_product'])) {
            $data['error_image_product'] = $this->error['image_product'];
        } else {
            $data['error_image_product'] = '';
        }

        if (isset($this->error['manufacturer_image_product'])) {
            $data['error_manufacturer_image_product'] = $this->error['manufacturer_image_product'];
        } else {
            $data['error_manufacturer_image_product'] = '';
        }

        if (isset($this->error['filter_manufacturer_image'])) {
            $data['error_filter_manufacturer_image'] = $this->error['filter_manufacturer_image'];
        } else {
            $data['error_filter_manufacturer_image'] = '';
        }

        if (isset($this->error['image_additional'])) {
            $data['error_image_additional'] = $this->error['image_additional'];
        } else {
            $data['error_image_additional'] = '';
        }

        if (isset($this->error['image_related'])) {
            $data['error_image_related'] = $this->error['image_related'];
        } else {
            $data['error_image_related'] = '';
        }

        if (isset($this->error['image_compare'])) {
            $data['error_image_compare'] = $this->error['image_compare'];
        } else {
            $data['error_image_compare'] = '';
        }

        if (isset($this->error['image_wishlist'])) {
            $data['error_image_wishlist'] = $this->error['image_wishlist'];
        } else {
            $data['error_image_wishlist'] = '';
        }

        if (isset($this->error['image_cart'])) {
            $data['error_image_cart'] = $this->error['image_cart'];
        } else {
            $data['error_image_cart'] = '';
        }

        if (isset($this->error['image_location'])) {
            $data['error_image_location'] = $this->error['image_location'];
        } else {
            $data['error_image_location'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=theme', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/theme/dm_project', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true)
        );

        $data['action'] = $this->url->link('extension/theme/dm_project', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=theme', true);

        if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $setting_info = $this->model_setting_setting->getSetting('dm_project', $this->request->get['store_id']);
        }

        //Main
        if (isset($this->request->post['dm_project_directory'])) {
            $data['dm_project_directory'] = $this->request->post['dm_project_directory'];
        } elseif (isset($setting_info['dm_project_directory'])) {
            $data['dm_project_directory'] = $setting_info['dm_project_directory'];
        } else {
            $data['dm_project_directory'] = 'dm_project';
        }

        if (isset($this->request->post['dm_project_status'])) {
            $data['dm_project_status'] = $this->request->post['dm_project_status'];
        } elseif (isset($setting_info['dm_project_status'])) {
            $data['dm_project_status'] = $this->config->get('dm_project_status');
        } else {
            $data['dm_project_status'] = '';
        }

        $data['directories'] = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $data['directories'][] = basename($directory);
        }

        //Products
        if (isset($this->request->post['dm_project_product_limit'])) {
            $data['dm_project_product_limit'] = $this->request->post['dm_project_product_limit'];
        } elseif (isset($setting_info['dm_project_product_limit'])) {
            $data['dm_project_product_limit'] = $setting_info['dm_project_product_limit'];
        } else {
            $data['dm_project_product_limit'] = 15;
        }

        if (isset($this->request->post['dm_project_product_description_length'])) {
            $data['dm_project_product_description_length'] = $this->request->post['dm_project_product_description_length'];
        } elseif (isset($setting_info['dm_project_product_description_length'])) {
            $data['dm_project_product_description_length'] = $this->config->get('dm_project_product_description_length');
        } else {
            $data['dm_project_product_description_length'] = 100;
        }

        //Images
        if (isset($this->request->post['dm_project_image_category_width'])) {
            $data['dm_project_image_category_width'] = $this->request->post['dm_project_image_category_width'];
        } elseif (isset($setting_info['dm_project_image_category_width'])) {
            $data['dm_project_image_category_width'] = $this->config->get('dm_project_image_category_width');
        } else {
            $data['dm_project_image_category_width'] = 80;
        }

        if (isset($this->request->post['dm_project_image_category_height'])) {
            $data['dm_project_image_category_height'] = $this->request->post['dm_project_image_category_height'];
        } elseif (isset($setting_info['dm_project_image_category_height'])) {
            $data['dm_project_image_category_height'] = $this->config->get('dm_project_image_category_height');
        } else {
            $data['dm_project_image_category_height'] = 80;
        }

        if (isset($this->request->post['dm_project_image_thumb_width'])) {
            $data['dm_project_image_thumb_width'] = $this->request->post['dm_project_image_thumb_width'];
        } elseif (isset($setting_info['dm_project_image_thumb_width'])) {
            $data['dm_project_image_thumb_width'] = $this->config->get('dm_project_image_thumb_width');
        } else {
            $data['dm_project_image_thumb_width'] = 228;
        }

        if (isset($this->request->post['dm_project_image_thumb_height'])) {
            $data['dm_project_image_thumb_height'] = $this->request->post['dm_project_image_thumb_height'];
        } elseif (isset($setting_info['dm_project_image_thumb_height'])) {
            $data['dm_project_image_thumb_height'] = $this->config->get('dm_project_image_thumb_height');
        } else {
            $data['dm_project_image_thumb_height'] = 228;
        }

        if (isset($this->request->post['dm_project_manufacturer_image_thumb_width'])) {
            $data['dm_project_manufacturer_image_thumb_width'] = $this->request->post['dm_project_manufacturer_image_thumb_width'];
        } elseif (isset($setting_info['dm_project_manufacturer_image_thumb_width'])) {
            $data['dm_project_manufacturer_image_thumb_width'] = $this->config->get('dm_project_manufacturer_image_thumb_width');
        } else {
            $data['dm_project_manufacturer_image_thumb_width'] = 100;
        }

        if (isset($this->request->post['dm_project_manufacturer_image_thumb_height'])) {
            $data['dm_project_manufacturer_image_thumb_height'] = $this->request->post['dm_project_manufacturer_image_thumb_height'];
        } elseif (isset($setting_info['dm_project_manufacturer_image_thumb_height'])) {
            $data['dm_project_manufacturer_image_thumb_height'] = $this->config->get('dm_project_manufacturer_image_thumb_height');
        } else {
            $data['dm_project_manufacturer_image_thumb_height'] = 50;
        }

        if (isset($this->request->post['dm_project_image_popup_width'])) {
            $data['dm_project_image_popup_width'] = $this->request->post['dm_project_image_popup_width'];
        } elseif (isset($setting_info['dm_project_image_popup_width'])) {
            $data['dm_project_image_popup_width'] = $this->config->get('dm_project_image_popup_width');
        } else {
            $data['dm_project_image_popup_width'] = 500;
        }

        if (isset($this->request->post['dm_project_image_popup_height'])) {
            $data['dm_project_image_popup_height'] = $this->request->post['dm_project_image_popup_height'];
        } elseif (isset($setting_info['dm_project_image_popup_height'])) {
            $data['dm_project_image_popup_height'] = $this->config->get('dm_project_image_popup_height');
        } else {
            $data['dm_project_image_popup_height'] = 500;
        }

        if (isset($this->request->post['dm_project_image_product_width'])) {
            $data['dm_project_image_product_width'] = $this->request->post['dm_project_image_product_width'];
        } elseif (isset($setting_info['dm_project_image_product_width'])) {
            $data['dm_project_image_product_width'] = $this->config->get('dm_project_image_product_width');
        } else {
            $data['dm_project_image_product_width'] = 228;
        }

        if (isset($this->request->post['dm_project_image_product_height'])) {
            $data['dm_project_image_product_height'] = $this->request->post['dm_project_image_product_height'];
        } elseif (isset($setting_info['dm_project_image_product_height'])) {
            $data['dm_project_image_product_height'] = $this->config->get('dm_project_image_product_height');
        } else {
            $data['dm_project_image_product_height'] = 228;
        }

        if (isset($this->request->post['dm_project_manufacturer_image_product_width'])) {
            $data['dm_project_manufacturer_image_product_width'] = $this->request->post['dm_project_manufacturer_image_product_width'];
        } elseif (isset($setting_info['dm_project_manufacturer_image_product_width'])) {
            $data['dm_project_manufacturer_image_product_width'] = $this->config->get('dm_project_manufacturer_image_product_width');
        } else {
            $data['dm_project_manufacturer_image_product_width'] = 100;
        }

        if (isset($this->request->post['dm_project_manufacturer_image_product_height'])) {
            $data['dm_project_manufacturer_image_product_height'] = $this->request->post['dm_project_manufacturer_image_product_height'];
        } elseif (isset($setting_info['dm_project_manufacturer_image_product_height'])) {
            $data['dm_project_manufacturer_image_product_height'] = $this->config->get('dm_project_manufacturer_image_product_height');
        } else {
            $data['dm_project_manufacturer_image_product_height'] = 50;
        }

        if (isset($this->request->post['dm_project_filter_manufacturer_image_width'])) {
            $data['dm_project_filter_manufacturer_image_width'] = $this->request->post['dm_project_filter_manufacturer_image_width'];
        } elseif (isset($setting_info['dm_project_filter_manufacturer_image_width'])) {
            $data['dm_project_filter_manufacturer_image_width'] = $this->config->get('dm_project_filter_manufacturer_image_width');
        } else {
            $data['dm_project_filter_manufacturer_image_width'] = 100;
        }

        if (isset($this->request->post['dm_project_filter_manufacturer_image_height'])) {
            $data['dm_project_filter_manufacturer_image_height'] = $this->request->post['dm_project_filter_manufacturer_image_height'];
        } elseif (isset($setting_info['dm_project_filter_manufacturer_image_height'])) {
            $data['dm_project_filter_manufacturer_image_height'] = $this->config->get('dm_project_filter_manufacturer_image_height');
        } else {
            $data['dm_project_filter_manufacturer_image_height'] = 50;
        }

        if (isset($this->request->post['dm_project_image_additional_width'])) {
            $data['dm_project_image_additional_width'] = $this->request->post['dm_project_image_additional_width'];
        } elseif (isset($setting_info['dm_project_image_additional_width'])) {
            $data['dm_project_image_additional_width'] = $this->config->get('dm_project_image_additional_width');
        } else {
            $data['dm_project_image_additional_width'] = 74;
        }

        if (isset($this->request->post['dm_project_image_additional_height'])) {
            $data['dm_project_image_additional_height'] = $this->request->post['dm_project_image_additional_height'];
        } elseif (isset($setting_info['dm_project_image_additional_height'])) {
            $data['dm_project_image_additional_height'] = $this->config->get('dm_project_image_additional_height');
        } else {
            $data['dm_project_image_additional_height'] = 74;
        }

        if (isset($this->request->post['dm_project_image_related_width'])) {
            $data['dm_project_image_related_width'] = $this->request->post['dm_project_image_related_width'];
        } elseif (isset($setting_info['dm_project_image_related_width'])) {
            $data['dm_project_image_related_width'] = $this->config->get('dm_project_image_related_width');
        } else {
            $data['dm_project_image_related_width'] = 80;
        }

        if (isset($this->request->post['dm_project_image_related_height'])) {
            $data['dm_project_image_related_height'] = $this->request->post['dm_project_image_related_height'];
        } elseif (isset($setting_info['dm_project_image_related_height'])) {
            $data['dm_project_image_related_height'] = $this->config->get('dm_project_image_related_height');
        } else {
            $data['dm_project_image_related_height'] = 80;
        }

        if (isset($this->request->post['dm_project_image_compare_width'])) {
            $data['dm_project_image_compare_width'] = $this->request->post['dm_project_image_compare_width'];
        } elseif (isset($setting_info['dm_project_image_compare_width'])) {
            $data['dm_project_image_compare_width'] = $this->config->get('dm_project_image_compare_width');
        } else {
            $data['dm_project_image_compare_width'] = 90;
        }

        if (isset($this->request->post['dm_project_image_compare_height'])) {
            $data['dm_project_image_compare_height'] = $this->request->post['dm_project_image_compare_height'];
        } elseif (isset($setting_info['dm_project_image_compare_height'])) {
            $data['dm_project_image_compare_height'] = $this->config->get('dm_project_image_compare_height');
        } else {
            $data['dm_project_image_compare_height'] = 90;
        }

        if (isset($this->request->post['dm_project_image_wishlist_width'])) {
            $data['dm_project_image_wishlist_width'] = $this->request->post['dm_project_image_wishlist_width'];
        } elseif (isset($setting_info['dm_project_image_wishlist_width'])) {
            $data['dm_project_image_wishlist_width'] = $this->config->get('dm_project_image_wishlist_width');
        } else {
            $data['dm_project_image_wishlist_width'] = 47;
        }

        if (isset($this->request->post['dm_project_image_wishlist_height'])) {
            $data['dm_project_image_wishlist_height'] = $this->request->post['dm_project_image_wishlist_height'];
        } elseif (isset($setting_info['dm_project_image_wishlist_height'])) {
            $data['dm_project_image_wishlist_height'] = $this->config->get('dm_project_image_wishlist_height');
        } else {
            $data['dm_project_image_wishlist_height'] = 47;
        }

        if (isset($this->request->post['dm_project_image_cart_width'])) {
            $data['dm_project_image_cart_width'] = $this->request->post['dm_project_image_cart_width'];
        } elseif (isset($setting_info['dm_project_image_cart_width'])) {
            $data['dm_project_image_cart_width'] = $this->config->get('dm_project_image_cart_width');
        } else {
            $data['dm_project_image_cart_width'] = 47;
        }

        if (isset($this->request->post['dm_project_image_cart_height'])) {
            $data['dm_project_image_cart_height'] = $this->request->post['dm_project_image_cart_height'];
        } elseif (isset($setting_info['dm_project_image_cart_height'])) {
            $data['dm_project_image_cart_height'] = $this->config->get('dm_project_image_cart_height');
        } else {
            $data['dm_project_image_cart_height'] = 47;
        }

        if (isset($this->request->post['dm_project_image_location_width'])) {
            $data['dm_project_image_location_width'] = $this->request->post['dm_project_image_location_width'];
        } elseif (isset($setting_info['dm_project_image_location_width'])) {
            $data['dm_project_image_location_width'] = $this->config->get('dm_project_image_location_width');
        } else {
            $data['dm_project_image_location_width'] = 268;
        }

        if (isset($this->request->post['dm_project_image_location_height'])) {
            $data['dm_project_image_location_height'] = $this->request->post['dm_project_image_location_height'];
        } elseif (isset($setting_info['dm_project_image_location_height'])) {
            $data['dm_project_image_location_height'] = $this->config->get('dm_project_image_location_height');
        } else {
            $data['dm_project_image_location_height'] = 50;
        }

        //Popup fast-view
        if (isset($this->request->post['dm_project_popup_view_data'])) {
            $data['dm_project_popup_view_data'] = $this->request->post['dm_project_popup_view_data'];
        } elseif (isset($setting_info['dm_project_popup_view_data'])) {
            $data['dm_project_popup_view_data'] = $this->config->get('dm_project_popup_view_data');
        } else {
            $data['dm_project_popup_view_data'] = array();
        }

        //Map
        if (isset($this->request->post['dm_project_map_data'])) {
            $data['dm_project_map_data'] = $this->request->post['dm_project_map_data'];
        } elseif (isset($setting_info['dm_project_map_data'])) {
            $data['dm_project_map_data'] = $this->config->get('dm_project_map_data');
        } else {
            $data['dm_project_map_data'] = array();
        }

        //Payment details
        if (isset($this->request->post['dm_project_payment_details'])) {
            $data['dm_project_payment_details'] = $this->request->post['dm_project_payment_details'];
        } elseif (!empty($this->config->get('dm_project_payment_details'))) {
            $data['dm_project_payment_details'] = $this->config->get('dm_project_payment_details');
        } else {
            $data['dm_project_payment_details'] = array();
        }

        //Set filter
        $attribute_filters = $this->config->get('dm_project_attribute_filters');

        $this->load->model('catalog/attribute');

        $data['attribute_filters'] = array();

        if ($attribute_filters) {
            foreach ($attribute_filters as $attribute_id) {
                $attribute_info = $this->model_catalog_attribute->getAttribute($attribute_id);

                if ($attribute_info) {
                    $data['attribute_filters'][] = array(
                        'attribute_id' => $attribute_info['attribute_id'],
                        'name'         => $attribute_info['name']
                    );
                }
            }
        }

        $data['attribute_filters_explode'] = array();

        $attribute_filters_explode = $this->config->get('dm_project_attribute_filters_explode');

        if ($attribute_filters_explode) {
            foreach ($attribute_filters_explode as $attribute_id) {
                $attribute_info = $this->model_catalog_attribute->getAttribute($attribute_id);

                if ($attribute_info) {
                    $data['attribute_filters_explode'][] = array(
                        'attribute_id' => $attribute_info['attribute_id'],
                        'name'         => $attribute_info['name']
                    );
                }
            }
        }

        $data['attributes_required'] = array();

        $attributes_required = $this->config->get('dm_project_attributes_required');

        if ($attributes_required) {
            foreach ($attributes_required as $attribute_id) {
                $attribute_info = $this->model_catalog_attribute->getAttribute($attribute_id);

                if ($attribute_info) {
                    $data['attributes_required'][] = array(
                        'attribute_id' => $attribute_info['attribute_id'],
                        'name'         => $attribute_info['name']
                    );
                }
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/theme/dm_project', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/theme/dm_project')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['dm_project_product_limit']) {
            $this->error['product_limit'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['dm_project_product_description_length']) {
            $this->error['product_description_length'] = $this->language->get('error_limit');
        }

        if (!$this->request->post['dm_project_image_category_width'] || !$this->request->post['dm_project_image_category_height']) {
            $this->error['image_category'] = $this->language->get('error_image_category');
        }

        if (!$this->request->post['dm_project_image_thumb_width'] || !$this->request->post['dm_project_image_thumb_height']) {
            $this->error['image_thumb'] = $this->language->get('error_image_thumb');
        }

        if (!$this->request->post['dm_project_manufacturer_image_thumb_width'] || !$this->request->post['dm_project_manufacturer_image_thumb_height']) {
            $this->error['manufacturer_image_thumb'] = $this->language->get('error_manufacturer_image_thumb');
        }

        if (!$this->request->post['dm_project_image_popup_width'] || !$this->request->post['dm_project_image_popup_height']) {
            $this->error['image_popup'] = $this->language->get('error_image_popup');
        }

        if (!$this->request->post['dm_project_image_product_width'] || !$this->request->post['dm_project_image_product_height']) {
            $this->error['image_product'] = $this->language->get('error_image_product');
        }

        if (!$this->request->post['dm_project_manufacturer_image_product_width'] || !$this->request->post['dm_project_manufacturer_image_product_height']) {
            $this->error['manufacturer_image_product'] = $this->language->get('error_manufacturer_image_product');
        }

        if (!$this->request->post['dm_project_filter_manufacturer_image_width'] || !$this->request->post['dm_project_filter_manufacturer_image_height']) {
            $this->error['filter_manufacturer_image'] = $this->language->get('error_filter_manufacturer_image');
        }

        if (!$this->request->post['dm_project_image_additional_width'] || !$this->request->post['dm_project_image_additional_height']) {
            $this->error['image_additional'] = $this->language->get('error_image_additional');
        }

        if (!$this->request->post['dm_project_image_related_width'] || !$this->request->post['dm_project_image_related_height']) {
            $this->error['image_related'] = $this->language->get('error_image_related');
        }

        if (!$this->request->post['dm_project_image_compare_width'] || !$this->request->post['dm_project_image_compare_height']) {
            $this->error['image_compare'] = $this->language->get('error_image_compare');
        }

        if (!$this->request->post['dm_project_image_wishlist_width'] || !$this->request->post['dm_project_image_wishlist_height']) {
            $this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
        }

        if (!$this->request->post['dm_project_image_cart_width'] || !$this->request->post['dm_project_image_cart_height']) {
            $this->error['image_cart'] = $this->language->get('error_image_cart');
        }

        if (!$this->request->post['dm_project_image_location_width'] || !$this->request->post['dm_project_image_location_height']) {
            $this->error['image_location'] = $this->language->get('error_image_location');
        }

        if ($this->request->post['dm_project_map_data']['status']) {
            if (!$this->request->post['dm_project_map_data']['longitude'] || !$this->request->post['dm_project_map_data']['latitude'] || !$this->request->post['dm_project_map_data']['zoom']) {
                $this->error['warning'] = $this->language->get('error_fill_map');
            }
        }

        if ($this->request->post['dm_project_payment_details']['add_attachment']) {
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_name']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_inn']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_account']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_bank_name']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_bank_bic']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_bank_cor_acct']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
            if (!$this->request->post['dm_project_payment_details']['payment_receiver_address']) {
                $this->error['warning'] = $this->language->get('error_payment_details');
            }
        }

        return !$this->error;
    }

    /** ==== Install / Uninstall ================================================================================== */

    public function install() {
        //Currency
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "currency` CHANGE `symbol_right` `symbol_right` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

        //Category
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `search_related` TINYINT (1) UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `search_regex` VARCHAR (50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `variations_height` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `image`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `variations_width` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `image`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `icon_height` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `image`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `icon_width` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `image`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `thumb_height` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `thumb_width` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `attribute_groups` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `attribute_display` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `variations_display` VARCHAR (50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `products_display` VARCHAR (50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `product_display` VARCHAR (50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `category_display` VARCHAR (50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `icon` VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_general_ci AFTER `image`");

        //Category description
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "category_description` ADD `add_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `description`");

        //Product
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `thumb_height` SMALLINT UNSIGNED DEFAULT '0' NOT NULL AFTER `height`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `thumb_width` SMALLINT UNSIGNED DEFAULT '0' NOT NULL AFTER `height`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `attribute_groups` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `product_id`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `attribute_display` SMALLINT (5) UNSIGNED DEFAULT '0' NOT NULL AFTER `product_id`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `variation` TINYINT (1) UNSIGNED DEFAULT '0' NOT NULL AFTER `product_id`");

        //Manufacturers
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "manufacturer` ADD `thumb_height` SMALLINT UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "manufacturer` ADD `thumb_width` SMALLINT UNSIGNED DEFAULT '0' NOT NULL AFTER `sort_order`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "manufacturer` ADD `manufacturer_display` VARCHAR (50) DEFAULT '0' NOT NULL AFTER `sort_order`");

        //Information
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "information` ADD `top` INT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `information_id`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "information` ADD `product` INT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `bottom`");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "information` ADD `bottom_help` INT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `bottom`");

        //Product special price
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD `logged` TINYINT NOT NULL DEFAULT '0' AFTER `customer_group_id`");

        //Product to category
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_to_category` ADD `main_category` TINYINT(1) NOT NULL DEFAULT '0' AFTER `category_id`");

        //banner_image
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `description` TEXT  CHARACTER SET utf8 COLLATE utf8_general_ci AFTER `title`");

        //Catalog
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "catalog` (
                            `catalog_display` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "catalog_description` (
                            `language_id` INT (11) NOT NULL,
                            `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_h1` VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_title` VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_description` VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_keyword` VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            PRIMARY KEY (`language_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        //Manufacturer description
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "manufacturer_description` (
                            `manufacturer_id` int(11) NOT NULL,
                            `language_id` int(11) NOT NULL,
                            `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_h1` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            `meta_keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                            PRIMARY KEY (`manufacturer_id`,`language_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description (manufacturer_id, meta_h1)
                          SELECT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer 
                          WHERE manufacturer_id NOT IN (SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description)");

        //Fast maintenance
        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'setting/fast_maintenance');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'setting/fast_maintenance');

        //Fast-view setting
        $this->load->model('setting/setting');

        $setting = array(
            'dm_project_popup_view_data' => array(
                'status'                  => '1',
                'heading'                 => '0',
                'no_stock'                => '0',
                'quantity'                => '1',
                'specification'        	  => '1',
                'review'                  => '1',
                'wishlist'                => '1',
                'compare'                 => '1',
                'description'             => '1',
                'description_max'         => '200',
                'image'                   => '1',
                'image_width'             => '500',
                'image_height'            => '500',
                'additional_image'        => '1',
                'image_additional_width'  => '150',
                'image_additional_height' => '150',
            ),
            'dm_project_map_data' => array(
                'status' => '0'
            )
        );

        $this->model_setting_setting->editSetting('dm_project', $setting);
    }

    /*
    public function uninstall() {
        //DROP tables and columns
    }
    */
}
