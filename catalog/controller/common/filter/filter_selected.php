<?php
class ControllerCommonFilterFilterSelected extends Controller {
    public function index($get_data) {
        $data['filters_selected'] = array();

        if ((isset($get_data['manufacturer_id']) && $this->request->get['route'] != 'product/manufacturer/info')  || (isset($get_data['min_price']) && isset($get_data['max_price'])) || isset($get_data['in_stock']) || (isset($get_data['sort']) && isset($get_data['order'])) || isset($get_data['limit']) || isset($get_data['attribute_filter'])) {
            if ($this->request->get['route'] == 'product/category') {
                $route = 'product/category&path=' . $get_data['path'];
            } elseif ($this->request->get['route'] == 'product/manufacturer/info') {
                $route = 'product/manufacturer/info&manufacturer_id=' . $get_data['manufacturer_id'];
            } elseif ($this->request->get['route'] == 'product/special') {
                $route = 'product/special';
            } elseif ($this->request->get['route'] == 'product/search') {
                $route = 'product/search&search=' . $this->request->get['search'];
            }

            $data['filter_reset'] = array (
                'name' => $this->language->get('text_reset'),
                'href' => $this->url->link($route)
            );

            if (isset($get_data['manufacturer_id']) && $get_data['route'] != 'product/manufacturer/info') {
                array_push($data['filters_selected'], $this->language->get('text_manufacturer') . ' ' . $this->model_catalog_manufacturer->getManufacturer($get_data['manufacturer_id'])['name']);
            }
            if (isset($get_data['min_price']) && isset($get_data['max_price'])) {
                array_push($data['filters_selected'], $this->language->get('text_from') . ' ' . $this->currency->format($get_data['min_price'], $this->session->data['currency']) . ' ' . $this->language->get('text_to') . ' ' . $this->currency->format($get_data['max_price'], $this->session->data['currency']));
            }
            if (isset($get_data['in_stock']) && $get_data['in_stock'] == true ) {
                array_push($data['filters_selected'], $this->language->get('entry_in_stock'));
            }
            if ((isset($get_data['sort']) && $get_data['sort'] == 'price') && (isset($get_data['order']) && $get_data['order'] == 'DESC')) {
                array_push($data['filters_selected'], $this->language->get('text_sort') . ' ' . $this->language->get('text_price_desc'));
            } elseif ((isset($get_data['sort']) && $get_data['sort'] == 'price') && (isset($get_data['order']) && $get_data['order'] == 'ASC')) {
                array_push($data['filters_selected'], $this->language->get('text_sort') . ' ' .  $this->language->get('text_price_asc'));
            }
            if ((isset($get_data['sort']) && $get_data['sort'] == 'name') && (isset($get_data['order']) && $get_data['order'] == 'DESC')) {
                array_push($data['filters_selected'], $this->language->get('text_sort') . ' ' . $this->language->get('text_name_desc'));
            } elseif ((isset($get_data['sort']) && $get_data['sort'] == 'name') && (isset($get_data['order']) && $get_data['order'] == 'ASC')) {
                array_push($data['filters_selected'], $this->language->get('text_sort') . ' ' .  $this->language->get('text_name_asc'));
            }
            if (isset($get_data['limit'])) {
                array_push($data['filters_selected'], $this->language->get('text_limit') . ' ' . $get_data['limit']);
            }
            if (isset($get_data['attribute_filter'])) {
                array_push($data['filters_selected'], implode(" / " , $get_data['attribute_filter']));
            }
        }

        return $this->load->view('common/filter/filter_selected', $data);
    }
}
