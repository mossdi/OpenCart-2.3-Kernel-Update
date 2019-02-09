<?php
class ControllerCatalogLandingPages extends Controller
{
    private $error = array();

    public function index() {
        $this->load->model('catalog/landing_pages');

        $this->load->language('catalog/landing_pages');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/landing_pages', 'token=' . $this->session->data['token'], true)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['landing_pages'] = $this->model_catalog_landing_pages->getLandingPages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/landing_pages', $data));
    }
}
    