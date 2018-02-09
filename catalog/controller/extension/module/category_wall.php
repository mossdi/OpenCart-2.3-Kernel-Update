<?php
class ControllerExtensionModuleCategoryWall extends Controller {
    public function index() {
        $this->load->language('extension/module/category_wall');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_categories_all'] = $this->language->get('text_categories_all');

        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        if (isset($parts[0])) {
            $data['category_id'] = $parts[0];
        } else {
            $data['category_id'] = 0;
        }

        if (isset($parts[1])) {
            $data['child_id'] = $parts[1];
        } else {
            $data['child_id'] = 0;
        }

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);

        $this->load->model('tool/image');

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

                    $children_data[] = array(
                        'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
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
                    'name'        => $category['name'],
                    'category_id' => $category['category_id'],
                    'description' => $category['description'],
                    'image'       => $image,
                    'children'    => $children_data,
                    'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }

        return $this->load->view('extension/module/category_wall', $data);
    }
}
