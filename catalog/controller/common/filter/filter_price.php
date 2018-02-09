<?php
class ControllerCommonFilterFilterPrice extends Controller {
	public function index($filter_data) {
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/parent_product');

        $this->load->language('common/filters');

		$data['text_from'] = $this->language->get('text_from');
        $data['text_to'] = $this->language->get('text_to');

        $data['price_gap'] = array();

        if ($this->request->get['route'] == 'product/category') {
            $route = 'product/category&path=' . $filter_data['filter_category_id'];
        } elseif ($this->request->get['route'] == 'product/manufacturer/info') {
            unset($filter_data['filter_category_id']); //delete $filter_data element
            $route = 'product/manufacturer/info&manufacturer_id=' . $filter_data['filter_manufacturer_id'];
        } elseif ($this->request->get['route'] == 'product/special') {
            $route = 'product/special';
        }

        $result_price_gap = $this->model_catalog_parent_product->getMinMaxPriceProductsAnyType($filter_data);

        if ($result_price_gap) {
            $data['price_gap'] = array(
                'min_price' => (int)$result_price_gap['min_price'],
                'max_price' => (int)$result_price_gap['max_price']
            );

            $data['min_price'] = $filter_data['filter_min_price'];
            $data['max_price'] = $filter_data['filter_max_price'];

            $url = '';

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }
            if (isset($this->request->get['attribute_filter'])) {
                foreach ($this->request->get['attribute_filter'] as $key => $value) {
                    $url .= '&attribute_filter[' . $key . ']=' . $value;
                }
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

            $data['button_filter_price'] = array(
                'text'  => $this->language->get('button_filter_price'),
                'route' => $route . $url
            );
        }

		return $this->load->view('common/filter/filter_price', $data);
	}
}
