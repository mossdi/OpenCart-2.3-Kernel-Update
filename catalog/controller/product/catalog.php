<?php
class ControllerProductCatalog extends Controller {
    public function index() {
        $this->load->language('product/catalog');

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('catalog/catalog');
        $this->load->model('tool/image');

        $catalog_info = $this->model_catalog_catalog->getCatalog();

        $this->document->addLink($this->url->link('product/catalog'), 'canonical');
        $this->document->setTitle($catalog_info['meta_title']);
        $this->document->setDescription($catalog_info['meta_description']);
        $this->document->setKeywords($catalog_info['meta_keyword']);

        if ($catalog_info['meta_h1']) {
            $data['heading_title'] = $catalog_info['meta_h1'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['description'] = html_entity_decode($catalog_info['description'], ENT_QUOTES, 'UTF-8');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('product/catalog')
        );

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);

        foreach ($categories as $category) {
            if ($category['top']) {
                // Level 2
                $children_data = array();

                $children = $this->model_catalog_category->getCategories($category['category_id']);

                foreach ($children as $child) {
                    $filter_data = array(
                        'filter_category_id'  => $child['category_id'],
                        'filter_sub_category' => true
                    );

                    if (!$child['product_display']) {
                        $children_data[] = array(
                            'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                            'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                        );
                    }
                }

                if ($category['icon'] && $category['icon_width'] && $category['icon_height']) {
                    $image = $this->model_tool_image->resize($category['icon'], $category['icon_width'], $category['icon_height']);
                } else if ($category['icon']) {
                    $image = $this->model_tool_image->resize($category['icon'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                } else if ($category['icon_width'] && $category['icon_height']) {
                    $image = $this->model_tool_image->resize('placeholder.png', $category['icon_width'], $category['icon_height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                }

                // Level 1
                $data['categories'][] = array(
                    'name'     => $category['name'],
                    'image'    => $image,
                    'children' => $children_data,
                    'column'   => $category['column'] ? $category['column'] : 1,
                    'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if ($catalog_info['catalog_display'] && file_exists(DIR_TEMPLATE  . $this->config->get('config_theme') . '/template/custom/catalog/' . $catalog_info['catalog_display'])) {
            $this->response->setOutput($this->load->view('custom/catalog/' . $catalog_info['catalog_display'], $data));
        } else {
            $this->response->setOutput($this->load->view('product/catalog', $data));
        }
    }
}