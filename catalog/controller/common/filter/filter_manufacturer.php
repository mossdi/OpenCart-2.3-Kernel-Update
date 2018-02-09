<?php
class ControllerCommonFilterFilterManufacturer extends Controller {
    public function index($filter_data) {
        $this->load->model('catalog/parent_product');

        $data['filter_manufacturers'] = array();

        $products_manufacturers = $this->model_catalog_parent_product->getManufacturersProductsAnyType($filter_data);

        if ($products_manufacturers) {
            $data['filter_manufacturer'] = $filter_data['filter_manufacturer_id'];

            if ($this->request->get['route'] == 'product/category') {
                $route = 'product/category&path=' . $filter_data['filter_category_id'];
            } elseif ($this->request->get['route'] == 'product/special') {
                $route = 'product/special';
            }

            $url = '';

            if (isset($this->request->get['attribute_filter'])) {
                foreach ($this->request->get['attribute_filter'] as $key => $value) {
                    $url .= '&attribute_filter[' . $key . ']=' . $value;
                }
            }
            if (isset($this->request->get['min_price']) && isset($this->request->get['max_price'])) {
                $url .= '&min_price=' . $this->request->get['min_price'] . '&max_price=' . $this->request->get['max_price'];
            }
            if (isset($this->request->get['in_stock'])) {
                $url .= '&in_stock=true';
            }
            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }
            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }
            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            foreach ($products_manufacturers as $product_manufacturer) {
                if ($product_manufacturer['image']) {
                    $image = $this->model_tool_image->resize($product_manufacturer['image'], $this->config->get($this->config->get('config_theme') . '_filter_manufacturer_image_width'), $this->config->get($this->config->get('config_theme') . '_filter_manufacturer_image_height'));
                } else {
                    $image = false;
                }

                $data['filter_manufacturers'][] = array(
                    'id'    => $product_manufacturer['manufacturer_id'],
                    'text'  => $product_manufacturer['name'],
                    'image' => $image,
                    'href'  => $this->url->link($route . '&manufacturer_id=' . $product_manufacturer['manufacturer_id'] . $url)
                );
            }
        }

        return $this->load->view('common/filter/filter_manufacturer', $data);
    }
}
