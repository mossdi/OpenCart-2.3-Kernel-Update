<?php
function productListView($object, $products_type = '', $products_any_type, $category_info, $url = '', $in_stock = false, $data, $filter) {
    if (empty($products_any_type)) return false;

    $object->load->model('catalog/product');
    $object->load->model('catalog/manufacturer');
    $object->load->model('tool/image');

    $data['products_any_type'] = array();

    foreach ($products_any_type as $product_any_type) {
        $product_preview      = array();
        $product_variants     = array();
        $product_manufacturer = array();

        if ($product_any_type['parent_product']) {
            //Cache
            $product_variants = $object->cache->get('parent_product.' . $product_any_type['id'] .  '.variants.' . (int)$object->config->get('config_language_id') . '.' . (int)$object->config->get('config_store_id') . '.' . $object->config->get('config_customer_group_id'), 'parent_products_preview');
            $product_preview  = $object->cache->get('parent_product.' . $product_any_type['id'] .  '.preview.' . (int)$object->config->get('config_language_id') . '.' . (int)$object->config->get('config_store_id') . '.' . $object->config->get('config_customer_group_id'), 'parent_products_preview');

            if (!$product_variants || !$product_preview) {
                $filter_data = array(
                    'filter_category_id'  => $product_any_type['id'],
                    'filter_in_stock'     => $in_stock,
                    'filter_sub_category' => false,
                    'filter_min_price'    => !empty($filter['filter_min_price']) ? $filter['filter_min_price'] : false,
                    'filter_max_price'    => !empty($filter['filter_max_price']) ? $filter['filter_max_price'] : false,
                    'filter_attribute'    => !empty($filter['filter_attribute']) ? $filter['filter_attribute'] : false,
                    'sort'                => !empty($filter['sort']) ? $filter['sort'] : false,
                    'order'               => !empty($filter['order']) ? $filter['order'] : false
                );

                if ($products_type == 'products_special') {
                    $variants = $object->model_catalog_product->getProductSpecials($filter_data);
                } else {
                    $variants = $object->model_catalog_product->getProducts($filter_data);
                }

                if ($variants) {
                    $product_variants = getProductVariants($object, $variants, $product_any_type);

                    //Cache set - productVariants
                    $object->cache->set('parent_product.' . $product_any_type['id'] .  '.variants.' . (int)$object->config->get('config_language_id') . '.' . (int)$object->config->get('config_store_id') . '.' . $object->config->get('config_customer_group_id'), $product_variants, 'parent_products_preview');

                    if (!empty($product_variants['groups'])) {
                        ksort($product_variants['groups']);

                        $first_group   = reset($product_variants['groups']);
                        $first_product = reset($first_group);
                    } else {
                        $first_product = reset($product_variants);
                    }

                    $product_preview_info = $object->model_catalog_product->getProduct($first_product['product_id']);

                    if ($product_preview_info) {
                        $product_preview = getProductPreview($object, $product_preview_info, $product_any_type);

                        //Cache set - productPreview
                        $object->cache->set('parent_product.' . $product_any_type['id'] .  '.preview.' . (int)$object->config->get('config_language_id') . '.' . (int)$object->config->get('config_store_id') . '.' . $object->config->get('config_customer_group_id'), $product_preview, 'parent_products_preview');
                    }
                }
            }
        } else {
            $product_info = $object->model_catalog_product->getProduct($product_any_type['id']);

            if ($product_info) $product_preview = getProductPreview($object, $product_info, $product_any_type);
        }

        if (!empty($product_any_type['manufacturer_id']) && empty($data['manufacturer_logo'])) {
            $product_manufacturer_info = $object->model_catalog_manufacturer->getManufacturer($product_any_type['manufacturer_id']);

            if ($product_manufacturer_info) $product_manufacturer = getProductManufacturer($object, $product_manufacturer_info, $products_type, $url);
        }

        if (!empty($product_preview)) {
            $data['products_any_type'][] = array(
                'id'               => $product_any_type['id'],
                'product_preview'  => $product_preview,
                'product_variants' => !empty($product_variants) ? $product_variants : false,
                'variants_display' => (!empty($product_variants) && count($product_variants) > 1) || !empty($product_variants['groups']) ? true : false,
                'manufacturer'     => $product_manufacturer
            );
        }
    }

    if ($category_info['products_display'] && file_exists(DIR_TEMPLATE  . $object->config->get('config_theme') . '/template/custom/category/category_products/' . $category_info['products_display'])) {
        $product_any_type_view = $object->load->view('custom/category/category_products/' . $category_info['products_display'], $data);
    } else {
        $product_any_type_view = $object->load->view('product/category_products', $data);
    }

    return $product_any_type_view;
}

function productVariantsView($object, $product_id, $product_variants, $category_info) {
    if (empty($product_variants)) return false;

    $object->load->model('catalog/product');
    $object->load->model('tool/image');

    $data['button_instock'] = $object->language->get('button_instock');
    $data['button_all'] = $object->language->get('button_all');

    $data['product_id'] = $product_id;

    $data['product_variants'] = array();

    foreach ($product_variants as $product_variant) {
        $special_logged_info = $object->model_catalog_product->getProductSpecialsLogged($product_variant['product_id']);

        if (!empty($special_logged_info['price']) && $product_variant['quantity'] > 0) {
            $special_percent = round(($product_variant['price'] - $special_logged_info['price']) / ($product_variant['price']/100)) . '%';
        } elseif ((float)$product_variant['special'] && $product_variant['quantity'] > 0) {
            $special_percent = round(($product_variant['price'] - $product_variant['special']) / ($product_variant['price']/100)) . '%';
        } else {
            $special_percent = false;
        }

        if ($product_variant['image']) {
            $image = $object->model_tool_image->resize($product_variant['image'], $object->config->get($object->config->get('config_theme') . '_image_additional_width'), $object->config->get($object->config->get('config_theme') . '_image_additional_height'));
        } else {
            $image = $object->model_tool_image->resize('placeholder.png', $object->config->get($object->config->get('config_theme') . '_image_additional_width'), $object->config->get($object->config->get('config_theme') . '_image_additional_height'));
        }

        $attribute_display = array();

        if ($category_info['attribute_display']) {
            $filter_attribute_display = array (
                'product_id'   => $product_variant['product_id'],
                'attribute_id' => $category_info['attribute_display']
            );

            $attribute_display = $object->model_catalog_product->getProductAttributeValue($filter_attribute_display);
        }

        $variant = array(
            'product_id'          => $product_variant['product_id'],
            'current_category_id' => $category_info['category_id'],
            'name'                => $product_variant['name'],
            'thumb'               => $image,
            'stock_qty'           => $product_variant['quantity'],
            'stock_id'            => $product_variant['quantity'] <= 0 ? 'nostock' : 'instock',
            'special_percent'     => $special_percent,
            'attribute'           => $attribute_display ? $attribute_display['text'] : false
        );

        if ($category_info['attribute_groups']) {
            $filter_attribute = array(
                'product_id'   => $product_variant['product_id'],
                'attribute_id' => $category_info['attribute_groups']
            );

            $attribute = $object->model_catalog_product->getProductAttributeValue($filter_attribute);

            $attribute_group =  $attribute['text'] ? $attribute['name'] . ' - ' . $attribute['text'] : '<span style="color:red;">value is undefined</span>';
              if ($attribute['text'] && strrchr($attribute['name'], ',')) {
                  $attribute_group .= substr(strrchr($attribute['name'], ','), 1);
              }

            $data['product_variants']['groups'][$attribute_group][] = $variant;
        } else {
            $data['product_variants'][] = $variant;
        }
    }

    if (!empty($data['product_variants']['groups'])) {
        ksort($data['product_variants']['groups']);
    }

    if ($category_info['variations_display'] && file_exists(DIR_TEMPLATE  . $object->config->get('config_theme') . '/template/custom/product/product_variants/' . $category_info['variations_display'])) {
        $product_variants_view = $object->load->view('custom/product/product_variants/' . $category_info['variations_display'], $data);
    } else {
        $product_variants_view = $object->load->view('product/product_variants', $data);
    }

    return $product_variants_view;
}

//====================================================================================================================

function getProductPreview($object, $product_info, $product_any_type) {
    if ($product_info['image'] && (!empty($category_info['thumb_width']) && !empty($category_info['thumb_height'])) && ($category_info['thumb_width'] && $category_info['thumb_height'])) {
        $category_image = $object->model_tool_image->resize($product_info['image'], $category_info['thumb_width'], $category_info['thumb_height']);
    } else if ($product_info['image']) {
        $category_image = $object->model_tool_image->resize($product_info['image'], $object->config->get($object->config->get('config_theme') . '_image_product_width'), $object->config->get($object->config->get('config_theme') . '_image_product_height'));
    } else if ((!empty($category_info['thumb_width']) && !empty($category_info['thumb_height'])) && ($category_info['thumb_width'] && $category_info['thumb_height'])) {
        $category_image = $object->model_tool_image->resize('placeholder.png', $category_info['thumb_width'], $category_info['thumb_height']);
    } else {
        $category_image = $object->model_tool_image->resize('placeholder.png', $object->config->get($object->config->get('config_theme') . '_image_product_width'), $object->config->get($object->config->get('config_theme') . '_image_product_height'));
    }

    if ($object->customer->isLogged() || !$object->config->get('config_customer_price')) {
        $price = $object->currency->format($object->tax->calculate($product_info['price'], $product_info['tax_class_id'], $object->config->get('config_tax')), $object->session->data['currency']);
    } else {
        $price = false;
    }

    $special_logged_info = $object->model_catalog_product->getProductSpecialsLogged($product_info['product_id']);

    if (!empty($special_logged_info['price']) && $product_info['quantity'] > 0) {
        $special_logged_text = sprintf($object->language->get('text_special_logged'), $object->currency->format($object->tax->calculate($product_info['price'] - $special_logged_info['price'], $product_info['tax_class_id'], $object->config->get('config_tax')), $object->session->data['currency']));
    } else {
        $special_logged_text = false;
    }

    if ((float)$product_info['special'] && $product_info['quantity'] > 0) {
        $special = $object->currency->format($object->tax->calculate($product_info['special'], $product_info['tax_class_id'], $object->config->get('config_tax')), $object->session->data['currency']);
        $special_percent = round(($product_info['price'] - $product_info['special']) / ($product_info['price'] / 100)) . '%';
    } else {
        $special = false;
        $special_percent = false;
    }

    if ($object->config->get('config_tax')) {
        $tax = $object->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $object->session->data['currency']);
    } else {
        $tax = false;
    }

    $points = $product_info['points'];

    $stock = $object->language->get('text_stock') . ' ';
    if ($product_info['quantity'] <= 0) {
        $stock .= $product_info['stock_status'];
    } elseif ($object->config->get('config_stock_display')) {
        $stock .= $product_info['quantity'];
    }

    $product_discounts = $object->model_catalog_product->getProductDiscounts($product_info['product_id']);

    $discounts = array();

    foreach ($product_discounts as $discount) {
        $discounts[] = array(
            'quantity' => $discount['quantity'],
            'price' => $object->currency->format($object->tax->calculate($discount['price'], $product_info['tax_class_id'], $object->config->get('config_tax')), $object->session->data['currency'])
        );
    }

    $attribute_display = array();

    if ($product_any_type['attribute_display']) {
        $filter_attribute_display = array (
            'product_id'   => $product_any_type['id'],
            'attribute_id' => $product_any_type['attribute_display']
        );

        $attribute_display = $object->model_catalog_product->getProductAttributeValue($filter_attribute_display);
    }

    return array(
        'name'            => $product_info['name'],
        'product_id'      => $product_info['product_id'],
        'stock_qty'       => $product_info['quantity'],
        'stock'           => $stock,
        'price'           => $price,
        'special'         => $special,
        'special_percent' => $special_logged_text ? $special_logged_text : $special_percent,
        'image'           => $category_image,
        'attribute'       => $attribute_display ? $attribute_display['text'] : false,
        'tax'             => $tax,
        'points'          => $points,
        'discount'        => $discounts,
        'href'            => $object->url->link('product/product', 'product_id=' . $product_info['product_id']),
        'minimum'         => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1
    );
}

function getProductVariants($object, $variants, $product_any_type) {
    $product_variants = array();

    foreach ($variants as $variant) {
        if ($variant['image']) {
            $variant_img = $object->model_tool_image->resize($variant['image'], $object->config->get($object->config->get('config_theme') . '_image_additional_width'), $object->config->get($object->config->get('config_theme') . '_image_additional_height'));
        } else {
            $variant_img = $object->model_tool_image->resize('placeholder.png', $object->config->get($object->config->get('config_theme') . '_image_additional_width'), $object->config->get($object->config->get('config_theme') . '_image_additional_height'));
        }

        $special_logged_info = $object->model_catalog_product->getProductSpecialsLogged($variant['product_id']);

        if (!empty($special_logged_info['price']) && $variant['quantity'] > 0) {
            $special_percent = round(($variant['price'] - $special_logged_info['price']) / ($variant['price'] / 100)) . '%';
        } elseif ((float)$variant['special'] && $variant['quantity'] > 0) {
            $special_percent = round(($variant['price'] - $variant['special']) / ($variant['price'] / 100)) . '%';
        } else {
            $special_percent = false;
        }

        $attribute_display = array();

        if ($product_any_type['attribute_display']) {
            $filter_attribute_display = array(
                'product_id'   => $variant['product_id'],
                'attribute_id' => $product_any_type['attribute_display']
            );

            $attribute_display = $object->model_catalog_product->getProductAttributeValue($filter_attribute_display);
        }

        $product_variant = array(
            'name'            => $variant['name'],
            'product_id'      => $variant['product_id'],
            'special_percent' => $special_percent,
            'image'           => $variant_img,
            'attribute'       => $attribute_display ? $attribute_display['text'] : false
        );

        if ($product_any_type['attribute_groups']) {
            $filter_attribute_groups = array(
                'product_id'   => $variant['product_id'],
                'attribute_id' => $product_any_type['attribute_groups']
            );

            $attribute = $object->model_catalog_product->getProductAttributeValue($filter_attribute_groups);

            $attribute_group = $attribute['text'] ? mb_strtolower($attribute['text']) : 'empty';

            $product_variants['groups'][$attribute_group][] = $product_variant;
        } else {
            $product_variants[] = $product_variant;
        }
    }

    return $product_variants;
}

function getProductManufacturer($object, $product_manufacturer_info, $products_type, $url) {
    if ($product_manufacturer_info['image']) {
        $image = $object->model_tool_image->resize($product_manufacturer_info['image'], $object->config->get($object->config->get('config_theme') . '_manufacturer_image_product_width'), $object->config->get($object->config->get('config_theme') . '_manufacturer_image_product_height'));
    } else {
        $image = false;
    }

    if ($products_type == 'products_special')
        $filter_href = $object->url->link('product/special', 'manufacturer_id=' . $product_manufacturer_info['manufacturer_id']) . $url;
    elseif ($products_type == 'products_category') {
        $filter_href = $object->url->link('product/category', 'path=' . $object->request->get['path'] . $url . '&manufacturer_id=' . $product_manufacturer_info['manufacturer_id']);
    } else {
        $filter_href = false;
    }

    return array(
        'name'        => $product_manufacturer_info['name'],
        'image'       => $image,
        'id'          => $product_manufacturer_info['manufacturer_id'],
        'href'        => $object->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_manufacturer_info['manufacturer_id']),
        'filter_href' => $filter_href
    );
}
