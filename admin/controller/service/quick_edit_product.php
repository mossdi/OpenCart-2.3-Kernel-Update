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

        $product_id = $this->request->post['product_id'];

        parse_str(html_entity_decode($this->request->post['data']), $product_data);

        if (!empty($product_data['images'])) {
            foreach ($product_data['images'] as $key => $value) {
                if ($key == $product_data['main-photo']) {
                    $product_data['image'] = $value['image'];
                } else {
                    $product_data['product_image'][] = [
                        'image'      => $value['image'],
                        'sort_order' => 0,
                    ];
                }
            }
        }

        $json['warning'] = $this->validate($product_id, $product_data);

        if (!$json['warning']) {
            $this->load->model('service/quick_edit_product');

            $this->model_service_quick_edit_product->editProduct($product_id, $product_data);

            if ($product_data['status']) {
                $productURL = $this->url->linkToCatalog('product/product', 'product_id=' . $product_id);

                $textSuccess = "Product edited - <a href=" . $productURL . " target=\"_blank\">" . $productURL . "</a> - " .
                    "<strong>( +1 )</strong>";

                $this->session->data['success'] = $textSuccess;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate($product_id, $data = array()) {
        $result = false;

        $attributes_required = $this->config->get('dm_project_attributes_required');

        if (!empty($attributes_required)) {
            $requiredAttributeIsEmpty   = false;
            $product_attribute_post_ids = array();

            if (!empty($data['product_attribute'])) {
                foreach ($data['product_attribute'] as $product_attribute_post) {
                    $product_attribute_post_ids[] = $product_attribute_post['attribute_id'];
                }

                foreach ($attributes_required as $attribute_required_id) {
                    if (!in_array($attribute_required_id, $product_attribute_post_ids)) {
                        $requiredAttributeIsEmpty = true;
                        break;
                    }
                }
            } else {
                $requiredAttributeIsEmpty = true;
            }

            if ($requiredAttributeIsEmpty) {
                $this->load->model('catalog/attribute');

                $result = "Required Attributes:";

                foreach ($attributes_required as $attribute_required_id) {
                    $result .= " " . $this->model_catalog_attribute->getAttribute($attribute_required_id)['name'] . ";";
                }
            }
        }

        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $result = true;
        }

        foreach ($data['product_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $result = true;
            }

            if (utf8_strlen($value['meta_title']) > 255) {
                $result = true;
            }
        }

        if ((utf8_strlen($data['model']) < 1) || (utf8_strlen($data['model']) > 64)) {
            $result = true;
        }

        if (utf8_strlen($data['keyword']) > 0) {
            $this->load->model('catalog/url_alias');

            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($data['keyword']);

            if ($url_alias_info && isset($product_id) && $url_alias_info['query'] != 'product_id=' . $product_id) {
                $result = true;
            }

            if ($url_alias_info && !isset($product_id)) {
                $result = true;
            }
        }

        return $result;
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
        $data['tab_image'] = $this->language->get('tab_image');

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
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_additional_image'] = $this->language->get('entry_additional_image');

        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');

        $data['product_description'] = $this->model_catalog_product->getProductDescriptions($product_id);

        $data['model'] = $product_info['model'];
        $data['sku'] = $product_info['sku'];
        $data['keyword'] = $product_info['keyword'];
        $data['status'] = $product_info['status'];
        $data['manufacturer_id'] = $product_info['manufacturer_id'];
        $data['attribute_group_id'] = $product_info['attribute_group_id'];

        $data['product_id'] = $product_id;

        //Manufacturer
        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

        $data['manufacturer'] = $manufacturer_info ? $manufacturer_info['name'] : null;

        //Categories
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

        //Attributes
        $this->load->model('catalog/attribute_group');

        $data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

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

        // Images
        if (!empty($product_info)) {
            $data['image'] = $product_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

        $data['product_images'] = array();

        foreach ($product_images as $product_image) {
            if (is_file(DIR_IMAGE . $product_image['image'])) {
                $image = $product_image['image'];
                $thumb = $product_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['product_images'][] = array(
                'image'      => $image,
                'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $product_image['sort_order']
            );
        }
        $data['token'] = $this->session->data['token'];

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->response->setOutput($this->load->view('service/quick_edit_product_form', $data));
    }

    public function getAttributes() {
        $this->load->model('catalog/attribute');

        $results = $this->model_catalog_attribute->getAttributes([
            'filter_attribute_group_id' => (int)$this->request->post['attribute_group_id'],
        ]);

        $attributes = array();

        foreach ($results as $attribute) {
            $attributes[] = [
                'attribute_id'  => $attribute['attribute_id'],
                'name'          => $attribute['name'],
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($attributes));
    }

    public function imagesUpload() {
        $this->load->model('service/quick_edit_product');
        $this->load->language('common/filemanager');

        $json   = array();
        $images = array();

        if ($this->request->server['HTTPS']) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }

        $uploadTo = 'catalog/photo/products/';

        $productManufacturer = mb_strtolower($this->request->post['product_manufacturer']);

        if (!empty($productManufacturer) && stripos($productManufacturer, 'не выбрано') === false) {
            $uploadTo .= $productManufacturer . '/';
        } else {
            $json['error'] = 'Please, set manufacturer';
        };

        $directory = DIR_IMAGE . '/' . $uploadTo;

        if (!file_exists($directory) || file_exists($directory) && !is_dir($directory)) {
            mkdir($directory, 0777);
        }

        if (!$json) {
            // Check if multiple files are uploaded or just one
            $files = array();

            if (!empty($this->request->files['file']['name']) && is_array($this->request->files['file']['name'])) {
                $i = 0;

                foreach (array_keys($this->request->files['file']['name']) as $key) {
                    $files[] = array(
                        'name'     => $this->request->post['product_id'] . '_' . $productManufacturer . '_' . $i++ . utf8_strtolower(utf8_substr(strrchr($this->request->files['file']['name'][$key], '.'), 0)),
                        'type'     => $this->request->files['file']['type'][$key],
                        'tmp_name' => $this->request->files['file']['tmp_name'][$key],
                        'error'    => $this->request->files['file']['error'][$key],
                        'size'     => $this->request->files['file']['size'][$key]
                    );
                }
            }

            $i = 0;

            foreach ($files as $file) {
                if (is_file($file['tmp_name'])) {
                    // Sanitize the filename
                    $filename = basename(html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8'));

                    // Validate the filename length
                    if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                        $json['error'] = $this->language->get('error_filename');
                    }

                    // Allowed file extension types
                    $allowed = array(
                        'jpg',
                        'jpeg',
                        'gif',
                        'png'
                    );

                    if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                        $json['error'] = $this->language->get('error_filetype');
                    }

                    // Allowed file mime types
                    $allowed = array(
                        'image/jpeg',
                        'image/pjpeg',
                        'image/png',
                        'image/x-png',
                        'image/gif'
                    );

                    if (!in_array($file['type'], $allowed)) {
                        $json['error'] = $this->language->get('error_filetype');
                    }

                    // Return any upload error
                    if ($file['error'] != UPLOAD_ERR_OK) {
                        $json['error'] = $this->language->get('error_upload_' . $file['error']);
                    }
                } else {
                    $json['error'] = $this->language->get('error_upload');
                }

                if (!$json) {
                    move_uploaded_file($file['tmp_name'], $directory . '/' . $filename);

                    $images[$i] = [
                        'path'      =>  $server . basename(DIR_IMAGE) . '/' . $uploadTo . $filename,
                        'cut_path'  =>  $uploadTo . $filename,
                    ];
                }

                $i++;
            }
        }

        if (!$json) {
            $json['success'] = true;
            $json['images']  = $images;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
