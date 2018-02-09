<?php
class ControllerExtensionModuleManufacturers extends Controller {
	public function index() {
		$this->load->language('extension/module/manufacturers');

		$data['heading_title'] = $this->language->get('heading_title');

		$this->document->addStyle('catalog/view/javascript/jquery/slick/slick.css');
		$this->document->addStyle('catalog/view/javascript/jquery/slick/slick-theme.css');
		$this->document->addScript('catalog/view/javascript/jquery/slick/slick.min.js');

		$this->load->model('tool/image');
		$this->load->model('catalog/manufacturer');

		$data['manufacturers'] = array();

		$filter_data = array(
			'sort' => 'sort_order'
		);

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers($filter_data);

		foreach ($manufacturers as $manufacturer) {
			if ($manufacturer['image']) {
				$image = $this->model_tool_image->resize($manufacturer['image'], 330, 130);

				$data['manufacturers'][] = array(
					'name'  => $manufacturer['name'],
					'image' => $image,
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
				);
			}
		}		

        return $this->load->view('extension/module/manufacturers', $data);
	}
}
