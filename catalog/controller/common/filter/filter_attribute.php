<?php
class ControllerCommonFilterFilterAttribute extends Controller {
	public function index($filter_data) {
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/parent_product');

        $this->load->language('common/filters');

        $data['text_select'] = $this->language->get('text_select');

        $filter_data['filter_attributes_id'] = $this->config->get('dm_project_attribute_filters');

        $data['filters_attribute'] = array();

        $results = $this->model_catalog_parent_product->getAttributeFilters($filter_data);

	    if ($results) {
            $data['filter_selected'] = isset($this->request->get['attribute_filter']) ? $this->request->get['attribute_filter'] : '';

            if ($this->request->get['route'] == 'product/category') {
                $route = 'product/category&path=' . $filter_data['filter_category_id'];
            } elseif ($this->request->get['route'] == 'product/manufacturer/info') {
                unset($filter_data['filter_category_id']); //delete $filter_data element
                $route = 'product/manufacturer/info&manufacturer_id=' . $filter_data['filter_manufacturer_id'];
            } elseif ($this->request->get['route'] == 'product/special') {
                $route = 'product/special';
            }

            $url = '';

            if (isset($this->request->get['min_price']) && isset($this->request->get['max_price'])) {
                $url .= '&min_price=' . $this->request->get['min_price'] . '&max_price=' . $this->request->get['max_price'];
            }
            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
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

            foreach ($results as $result) {
                if (!empty($this->config->get('dm_project_attribute_filters_explode')) && in_array($result['group_id'], $this->config->get('dm_project_attribute_filters_explode'))) {
                    $result_attributes = array();

                    foreach ($result['attributes'] as $attribute) {
                        if (stripos($attribute, '/') !== false) {
                            $delimiter = '/';
                        } else {
                            $delimiter = ',';
                        }

                        $attributes_explode = explode($delimiter, $attribute);

                        foreach ($attributes_explode as $attribute_explode) {
                            if (!in_array(trim($attribute_explode), $result_attributes)) {
                                $result_attributes[] = trim($attribute_explode);
                            }
                        }

                        asort($result_attributes);
                    }
                } else {
                    $result_attributes = $result['attributes'];
                }

                $attribute_selected = '';

                if (isset($this->request->get['attribute_filter'])) {
                    foreach ($this->request->get['attribute_filter'] as $key => $attribute_value) {
                        if ($key != $result['group_id']) {
                            $attribute_selected .= '&attribute_filter[' . $key . ']=' . mb_strtolower($attribute_value);
                        }
                    }
                }

                $attributes = array();

                foreach ($result_attributes as $attribute) {
                    $attributes[] = array(
                        'value' => $attribute,
                        'href'  => $this->url->link($route . '&attribute_filter[' . $result['group_id'] . ']=' . mb_strtolower($attribute) . $attribute_selected . $url)
                    );
                }

                if ($attributes) {
                    $data['filters_attribute'][] = array(
                        'group_name' => $result['group_name'],
                        'group_id'   => $result['group_id'],
                        'attributes' => $attributes,
                        'disable'    => $this->url->link($route . $attribute_selected . $url)
                    );
                }
            }
        }

	    return $this->load->view('common/filter/filter_attribute', $data);
	}
}
