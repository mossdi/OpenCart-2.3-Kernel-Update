<?php
class ControllerCatalogLandingPage extends Controller
{
    private $error = array();

    public function index() {
        $this->load->language('catalog/landing_page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/landing_page');

        $this->getList();
    }

    public function add() {
        $this->load->language('catalog/landing_page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/landing_page');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_landing_page->addLandingPage($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/landing_page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/landing_page');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_landing_page->editLandingPage($this->request->get['landing_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/landing_page');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/landing_page');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $landing_id) {
                $this->model_catalog_landing_page->deleteLandingPage($landing_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->getList();
    }
    
    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, true)
        );

        $data['add'] = $this->url->link('catalog/landing_page/add', 'token=' . $this->session->data['token'] . $url, true);
        $data['delete'] = $this->url->link('catalog/landing_page/delete', 'token=' . $this->session->data['token'] . $url, true);

        $data['landing_pages'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $pages_total = $this->model_catalog_landing_page->getTotalLandingPages();

        $results = $this->model_catalog_landing_page->getLandingPages($filter_data);

        foreach ($results as $result) {
            $data['landing_pages'][] = array(
                'landing_id' => $result['landing_id'],
                'name'       => $result['name'],
                'edit'       => $this->url->link('catalog/landing_page/edit', 'token=' . $this->session->data['token'] . '&landing_id=' . $result['landing_id'] . $url, true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $pages_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($pages_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($pages_total - $this->config->get('config_limit_admin'))) ? $pages_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $pages_total, ceil($pages_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/landing_list', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['landing_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_remove'] = $this->language->get('text_remove');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_attribute'] = $this->language->get('entry_attribute');
        $data['entry_attribute_value'] = $this->language->get('entry_attribute_value');
        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = '';
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = '';
        }

        if (isset($this->error['attribute_value'])) {
            $data['error_attribute_value'] = $this->error['attribute_value'];
        } else {
            $data['error_attribute_value'] = '';
        }

        if (isset($this->error['category'])) {
            $data['error_category'] = $this->error['category'];
        } else {
            $data['error_category'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, true)
        );

        if (!isset($this->request->get['landing_id'])) {
            $data['action'] = $this->url->link('catalog/landing_page/add', 'token=' . $this->session->data['token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('catalog/landing_page/edit', 'token=' . $this->session->data['token'] . '&landing_id=' . $this->request->get['landing_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('catalog/landing_page', 'token=' . $this->session->data['token'] . $url, true);

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['landing_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $landing_info = $this->model_catalog_landing_page->getLandingPage($this->request->get['landing_id']);
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (!empty($this->request->post['landing_description'])) {
            $data['landing_description'] = $this->request->post['landing_description'];
        } elseif (!empty($landing_info)) {
            $data['landing_description'] = $this->model_catalog_landing_page->getLandingPageDescription($landing_info['landing_id']);
        } else {
            $data['landing_description'] = array();
        }

        $data['category'] = array();

        if (!empty($landing_info) || !empty($this->request->post['category_id'])) {
            $this->load->model('catalog/category');

            if (!empty($this->request->post['category_id'])) {
                $category_id = $this->request->post['category_id'];
            } elseif (!empty($landing_info)) {
                $category_id = $landing_info['category_id'];
            } else {
                $category_id = 0;
            }

            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['category'] = array (
                    'category_id' => $category_info['category_id'],
                    'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        if (!empty($this->request->post['attribute_id'])) {
            $data['attribute_id'] = $this->request->post['attribute_id'];
        } elseif (!empty($landing_info) && $landing_info['attribute_id']) {
            $data['attribute_id'] = $landing_info['attribute_id'];
        } else {
            $data['attribute_id'] = false;
        }

        $data['attributes'] = array();

        $filter_attributes = $this->config->get('dm_project_attribute_filters');

        if ($filter_attributes) {
            $this->load->model('catalog/attribute');

            foreach ($filter_attributes as $filter_attribute_id) {
                $attribute_info = $this->model_catalog_attribute->getAttribute($filter_attribute_id);

                $data['attributes'][] = array(
                    'attribute_id'   => $attribute_info['attribute_id'],
                    'attribute_name' => $attribute_info['name'],
                );
            }
        }

        $this->load->model('catalog/manufacturer');

        if (!empty($this->request->post['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($landing_info) && $landing_info['manufacturer_id']) {
            $data['manufacturer_id'] = $landing_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = false;
        }

        $data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/landing_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/landing_page')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['landing_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }

            if ((utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }

            if ((utf8_strlen($value['attribute_value']) < 2)) {
                $this->error['attribute_value'][$language_id] = $this->language->get('error_attribute_value');
            }
        }

        if (empty($this->request->post['category_id'])) {
            $this->error['category'] = $this->language->get('error_category');
        }

        if (empty($this->request->post['attribute_id']) && empty($this->request->post['manufacturer_id'])) {
            $this->error['warning'] = $this->language->get('error_attribute_or_manufacturer');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/landing_page')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
