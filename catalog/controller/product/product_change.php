<?php
class ControllerProductProductChange extends Model {
    public function changeProductCard() {
        $this->load->model('catalog/product');

        $json = array();

        $product_info = $this->model_catalog_product->getProduct((int)$this->request->post['product_id']);

        if ($product_info) {
            $this->load->language('product/product');

            $this->load->model('tool/image');
            $this->load->model('catalog/manufacturer');
            $this->load->model('catalog/parent_product');

            $images = array();
            $info = array();
            $related = array();
            $nav_tabs = array();

            $product_id = (int)$this->request->post['product_id'];

            //Info data
            $info['text_model'] = $this->language->get('text_model');
            $info['text_manufacturer'] = $this->language->get('text_manufacturer');
            $info['text_reward'] = $this->language->get('text_reward');
            $info['text_option'] = $this->language->get('text_option');
            $info['text_discount'] = $this->language->get('text_discount');
            $info['text_tax'] = $this->language->get('text_tax');
            $info['text_points'] = $this->language->get('text_points');
            $info['text_select'] = $this->language->get('text_select');
            $info['text_payment_recurring'] = $this->language->get('text_payment_recurring');
            $info['text_select'] = $this->language->get('text_select');
            $info['text_option'] = $this->language->get('text_option');
            $info['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
            $info['text_loading'] = $this->language->get('text_loading');
            $info['text_tags'] = $this->language->get('text_tags');

            $info['recurrings'] = $this->model_catalog_product->getProfiles($product_id);
            $info['model'] = $product_info['model'];
            $info['manufacturer'] = $product_info['manufacturer'];
            $info['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
            $info['reward'] = $product_info['reward'];

            $info['button_wishlist'] = $this->language->get('button_wishlist');
            $info['button_compare'] = $this->language->get('button_compare');
            $info['button_cart'] = $this->language->get('button_cart');
            $info['button_pre_order'] = $this->language->get('button_pre_order');
            $info['button_no_stock'] = $this->language->get('button_no_stock');

            $info['product_id'] = $product_id;

            $info['stock'] = '<strong>' . $this->language->get('text_stock') . '</strong> ';
            if ((int)$product_info['quantity'] <= 0) {
                $info['stock'] .= $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $info['stock'] .= $product_info['quantity'];
            } else {
                $info['stock'] .= $this->language->get('text_instock');
            }

            $info['stock_qty'] = $product_info['quantity'];
            $info['stock_checkout'] = $this->config->get('config_stock_checkout');

            $info['minimum'] = $product_info['minimum'];

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $info['price'] = false;
            }

            if ((float)$product_info['special'] && $product_info['quantity'] > 0) {
                $info['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $info['special'] = false;
            }

            $special_logged = $this->model_catalog_product->getProductSpecialsLogged($product_id);

            if ($info['price'] && !empty($special_logged['price']) && $product_info['quantity'] > 0) {
                $info['special_logged'] = sprintf($this->language->get('text_special_logged'), $this->currency->format($this->tax->calculate($info['price'] - $special_logged['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']));
            } else {
                $info['special_logged'] = false;
            }

            if ($this->config->get('config_tax')) {
                $info['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            } else {
                $info['tax'] = false;
            }

            $info['points'] = $product_info['points'];

            $info['discounts'] = array();

            $discounts = $this->model_catalog_product->getProductDiscounts($product_id);

            foreach ($discounts as $discount) {
                $info['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                );
            }

            $info['options'] = array();

            foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
                $product_option_value_data = array();

                foreach ($option['product_option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        $product_option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price' => $price,
                            'price_prefix' => $option_value['price_prefix']
                        );
                    }
                }

                $info['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required']
                );
            }

            $info['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_id);

            $info['tags'] = array();

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $info['tags'][] = array(
                        'tag' => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                    );
                }
            }
            //End Info data

            //Images data
            if ($product_info['image']) {
                $images['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
            } else {
                $images['popup'] = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
            }

            if ($product_info['image'] && $product_info['thumb_width'] && $product_info['thumb_height']) {
                $images['thumb'] = $this->model_tool_image->resize($product_info['image'], $product_info['thumb_width'], $product_info['thumb_height']);
                $images['small_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
            } elseif ($product_info['image']) {
                $images['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                $images['small_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
            } elseif ($product_info['thumb_width'] && $product_info['thumb_height']) {
                $images['thumb'] = $this->model_tool_image->resize('placeholder.png', $product_info['thumb_width'], $product_info['thumb_height']);
                $images['small_thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
            } else {
                $images['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
                $images['small_thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
            }

            $images['images'] = array();

            $results = $this->model_catalog_product->getProductImages($product_id);

            if ($results) {
                if ($product_info['image'] && $product_info['thumb_width'] && $product_info['thumb_height']) {
                    $big_width = $product_info['thumb_width'];
                    $big_height = $product_info['thumb_height'];
                } else {
                    $big_width = $this->config->get($this->config->get('config_theme') . '_image_thumb_width');
                    $big_height = $this->config->get($this->config->get('config_theme') . '_image_thumb_height');
                }

                foreach ($results as $result) {
                    $images['images'][] = array(
                        'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
                        'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height')),
                        'big_thumb' => $this->model_tool_image->resize($result['image'], $big_width, $big_height)
                    );
                }
            }

            $images['heading_title'] = $product_info['name'];
            //End Images data

            //Related
            $related['popup_view_data'] = $this->config->get($this->config->get('config_theme') . '_popup_view_data');
            $related['popup_view_text'] = $this->language->load('common/popup_view');

            $related['text_related'] = $this->language->get('text_related');

            $related['button_cart'] = $this->language->get('button_cart');
            $related['button_no_stock'] = $this->language->get('button_no_stock');
            $related['button_pre_order'] = $this->language->get('button_pre_order');

            $related['stock_checkout'] = $this->config->get('config_stock_checkout');
            $related['stock_display'] = $this->config->get('config_stock_display');

            $related['products'] = array();

            $results = $this->model_catalog_product->getProductRelated($product_id);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special'] && $result['quantity'] > 0) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $stock = $this->language->get('text_stock') . ' ';
                if ($result['quantity'] <= 0) {
                    $stock .= $result['stock_status'];
                } elseif ($this->config->get('config_stock_display')) {
                    $stock .= $result['quantity'];
                } else {
                    $stock .= $this->language->get('text_instock');
                }

                $product_manufacturer = array();

                if ($result['manufacturer_id']) {
                    $product_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);

                    if ($product_manufacturer_info['image']) {
                        $logo = $this->model_tool_image->resize($product_manufacturer_info['image'], 100, 39);
                    } else {
                        $logo = false;
                    }

                    $product_manufacturer = array(
                        'name' => $product_manufacturer_info['name'],
                        'logo' => $logo,
                        'id' => $product_manufacturer_info['manufacturer_id'],
                        'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_manufacturer_info['manufacturer_id'])
                    );
                }

                $related['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'stock' => $stock,
                    'stock_qty' => $result['quantity'],
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $rating,
                    'manufacturer' => $product_manufacturer,
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }
            //Related

            //Nav Tabs
            $nav_tabs['text_write'] = $this->language->get('text_write');
            $nav_tabs['text_note'] = $this->language->get('text_note');
            $nav_tabs['text_loading'] = $this->language->get('text_loading');
            $nav_tabs['text_login'] = sprintf($this->language->get('text_login_ajax'), 'getPopupLogin();', $this->url->link('account/register', '', true));

            $nav_tabs['entry_name'] = $this->language->get('entry_name');
            $nav_tabs['entry_review'] = $this->language->get('entry_review');
            $nav_tabs['entry_rating'] = $this->language->get('entry_rating');
            $nav_tabs['entry_good'] = $this->language->get('entry_good');
            $nav_tabs['entry_bad'] = $this->language->get('entry_bad');

            $nav_tabs['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

            $nav_tabs['button_continue'] = $this->language->get('button_continue');

            $nav_tabs['review_status'] = $this->config->get('config_review_status');

            $this->load->model('catalog/information');

            $nav_tabs['nav_informations'] = array();

            foreach ($this->model_catalog_information->getInformations() as $result) {
                if ($result['product']) {
                    $nav_tabs['nav_informations'][] = array(
                        'id' => $result['information_id'],
                        'title' => $result['title'],
                        'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
                    );
                }
            }

            if ($this->customer->isLogged()) {
                $nav_tabs['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $nav_tabs['customer_name'] = '';
            }

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $nav_tabs['review_guest'] = true;
            } else {
                $nav_tabs['review_guest'] = false;
            }

            if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
                $nav_tabs['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
            } else {
                $nav_tabs['captcha'] = '';
            }

            $nav_tabs['product_id'] = $product_id;

            $nav_tabs['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $nav_tabs['tab_description'] = $this->language->get('tab_description');
            //Nav Tabs

            //JSON
            if ($this->config->get('autoseotitle_enable')) {
                $this->load->model('extension/module/autoseotitle');
                $json['meta_data'] = $this->model_extension_module_autoseotitle->setProduct($ajax = true, $product_info, ($this->customer->isLogged() || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false, isset($category_info) ? $category_info : array());
            } else {
                $json['meta_data'] = array(
                    'meta_title' => $product_info['meta_title'],
                    'meta_description' => $product_info['meta_description'],
                    'meta_keyword' => $product_info['meta_keyword']
                );
            }

            $category_id_info = $this->model_catalog_parent_product->getParentCategoryId($product_id);

            if ((int)$this->request->post['current_category_id'] != (int)$category_id_info['category_id']) {
                $this->load->model('catalog/category');

                $category_info = $this->model_catalog_category->getCategory((int)$category_id_info['category_id']);

                $json['category_name'] = $category_info['name'];
                $json['category_id'] = $category_info['category_id'];
                $json['category_href'] = $this->url->link('product/category&path=' . $category_id_info['category_id']);
            } else {
                $json['category_name'] = false;
            }

            $json['heading-title'] = $product_info['name'];
            $json['link'] = $this->url->link('product/product&product_id=' . $product_id);

            $json['images'] = $this->load->view('z_blocks/json/card_product_images', $images);
            $json['info'] = $this->load->view('z_blocks/json/card_product_info', $info);
            $json['related'] = $this->load->view('z_blocks/json/card_product_related', $related);
            $json['nav_tabs'] = $this->load->view('z_blocks/json/card_product_nav_tabs', $nav_tabs);
            //END JSON
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function changeProduct() {
        $this->load->model('catalog/product');

        $json = array();

        $product_info = $this->model_catalog_product->getProduct((int)$this->request->post['product_id']);

        if ($product_info) {
            $this->load->language('product/product');
            $this->load->language('product/category');

            $this->load->model('catalog/category');
            $this->load->model('tool/image');

            $info = array();

            $product_id = (int)$this->request->post['product_id'];
            $category_id = (int)$this->request->post['category_id'];

            //Info
            $info['text_tax'] = $this->language->get('text_tax');
            $info['text_points'] = $this->language->get('text_points');
            $info['text_discount'] = $this->language->get('text_discount');
            $info['text_loading'] = $this->language->get('text_loading');

            $info['button_cart'] = $this->language->get('button_cart');
            $info['button_pre_order'] = $this->language->get('button_pre_order');
            $info['button_no_stock'] = $this->language->get('button_no_stock');

            $info['stock_checkout'] = $this->config->get('config_stock_checkout');
            $info['stock_display'] = $this->config->get('config_stock_display');

            $info['product_id'] = $product_id;
            $info['category_id'] = $category_id;

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $info['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $info['price'] = false;
            }

            if ((float)$product_info['special'] && $product_info['quantity'] > 0) {
                $info['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $info['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $info['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            } else {
                $info['tax'] = false;
            }

            $info['points'] = $product_info['points'];

            $discounts = $this->model_catalog_product->getProductDiscounts($product_id);
            $info['discounts'] = array();

            foreach ($discounts as $discount) {
                $info['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                );
            }

            $info['stock'] = $this->language->get('text_stock') . ' ';
            if ((int)$product_info['quantity'] <= 0) {
                $info['stock'] .= $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $info['stock'] .= $product_info['quantity'];
            }

            $info['stock_qty'] = $product_info['quantity'];
            $info['minimum'] = $product_info['minimum'];
            //End Info

            //JSON
            $json['product_name'] = $product_info['name'];
            $json['product_link'] = $this->url->link('product/product&product_id=' . $product_id);

            if (isset($this->request->post['category_parent_id'])) {
                $category_parent_info = $this->model_catalog_category->getCategory((int)$this->request->post['category_parent_id']);
            }

            if ($product_info['image'] && !empty($category_parent_info) && $category_parent_info['thumb_width'] && $category_parent_info['thumb_height']) {
                $json['thumb'] = $this->model_tool_image->resize($product_info['image'], $category_parent_info['thumb_width'], $category_parent_info['thumb_height']);
            } else if ($product_info['image']) {
                $json['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
            } else if (!empty($category_parent_info) && $category_parent_info['thumb_width'] && $category_parent_info['thumb_height']) {
                $json['thumb'] = $this->model_tool_image->resize('placeholder.png', $category_parent_info['thumb_width'], $category_parent_info['thumb_height']);
            } else {
                $json['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
            }

            $special_logged_info = $this->model_catalog_product->getProductSpecialsLogged($product_id);

            if (!empty($special_logged_info['price']) && $product_info['quantity'] > 0) {
                $json['percent'] = '<span class="special-percent">' . sprintf($this->language->get('text_special_logged'), $this->currency->format($this->tax->calculate($product_info['price'] - $special_logged_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])) . '</span>';
            } elseif ((float)$product_info['special'] && $product_info['quantity'] > 0) {
                $json['percent'] = '<span class="special-percent">' . round(($product_info['price'] - $product_info['special']) / ($product_info['price'] / 100)) . '%</span>';
            } else {
                $json['percent'] = '';
            }

            $json['info'] = $this->load->view('z_blocks/json/category_product_info', $info);
            //End JSON
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
