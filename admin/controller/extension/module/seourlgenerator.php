<?php
class ControllerExtensionModuleSeoUrlGenerator extends Controller {
    private $error = array();
    private $dublicates = array();
    private $only_to_latin;
    private $simple_blog;
    
    public function index() {
        $this->language->load('extension/module/seourlgenerator');
        
        $this->load->model('extension/module/seourlgenerator');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->simple_blog = $this->model_extension_module_seourlgenerator->getShowTable(array(DB_PREFIX.'simple_blog_article',DB_PREFIX.'simple_blog_article_description'));
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;    
        }

        $url = '';

        $sort_id = '';
        
        $sort = array();
        
        $tab = 'products';
        
        if (isset($this->request->get['categories'])) {
            $sort = array('categories' => $this->request->get['categories']);
            
            $url .= '&categories=' . $this->request->get['categories'];
            
            $sort_id = '&categories=asc-seos_data.category_id';
            
            $order_id = 'asc';
            
            if ($this->request->get['categories'] == 'asc-seos_data.category_id') {
                $sort_id = '&categories=desc-seos_data.category_id';
            
                $order_id = 'desc';
            }
            
            $tab = 'categories';
        } elseif (isset($this->request->get['manufactures'])) {
            $sort = array('manufactures' => $this->request->get['manufactures']);
            
            $url .= '&manufactures=' . $this->request->get['manufactures'];
            
            $sort_id = '&manufactures=asc-seos_data.manufacturer_id';
            
            $order_id = 'asc';
            
            if ($this->request->get['manufactures'] == 'asc-seos_data.manufacturer_id') {
                $sort_id = '&manufactures=desc-seos_data.manufacturer_id';
                
                $order_id = 'desc';
            }
            $tab = 'manufactures';
        } elseif (isset($this->request->get['informations'])) { //v1.1
            $sort = array('informations' => $this->request->get['informations']);
            
            $url .= '&informations=' . $this->request->get['informations'];
            
            $sort_id = '&informations=asc-seos_data.information_id';
            
            $order_id = 'asc';
            
            if ($this->request->get['informations']=='asc-seos_data.information_id') {
                $sort_id = '&informations=desc-seos_data.information_id';
            
                $order_id = 'desc';
            }
            
            $tab = 'informations';
        } elseif ($this->simple_blog && isset($this->request->get['simpleblogarticles'])) { //v.1.2
            $sort = array('simpleblogarticles' => $this->request->get['simpleblogarticles']);
            
            $url .= '&simpleblogarticles=' . $this->request->get['simpleblogarticles'];
            
            $sort_id = '&simpleblogarticles=asc-seos_data.information_id';
            
            $order_id = 'asc';
            
            if ($this->request->get['simpleblogarticles'] == 'asc-seos_data.simple_blog_article_id') {
                $sort_id = '&simpleblogarticles=desc-seos_data.simple_blog_article_id';
            
                $order_id = 'desc';
            }
            
            $tab = 'simpleblogarticles';
        } elseif ($this->simple_blog && isset($this->request->get['simpleblogcategories'])) {
            $sort = array('simpleblogcategories' => $this->request->get['simpleblogcategories']);
            
            $url .= '&simpleblogcategories=' . $this->request->get['simpleblogcategories'];
            
            $sort_id = '&simpleblogcategories=asc-seos_data.simple_blog_category_id';
            
            $order_id = 'asc';
            
            if ($this->request->get['simpleblogcategories'] == 'asc-seos_data.simple_blog_category_id') {
                $sort_id = '&simpleblogcategories=desc-seos_data.simple_blog_category_id';
            
                $order_id = 'desc';
            }
            
            $tab = 'simpleblogcategories';
        } else {
            if (isset($this->request->get['products'])) {
                $sort = array('products' => $this->request->get['products']);
                
                $url .= '&products=' . $this->request->get['products'];
            } else {
                $sort = array('products' => 'asc-seos_data.product_id');
                
                $url .= '&products=asc-seos_data.product_id';
            }
            $sort_id = '&products=asc-seos_data.product_id';
            
            $order_id = 'asc';
            
            if (isset($this->request->get['products']) && $this->request->get['products'] == 'asc-seos_data.product_id') {
                $sort_id = '&products=desc-seos_data.product_id';
            
                $order_id = 'desc';
            }
        }
        
        $filter_name = '';
        
        if (isset($this->request->post['filter_name']) || isset($this->request->get['filter_name'])) {
            if (isset($this->request->post['filter_name']) && $this->request->post['filter_name']) {
                $filter_name = $this->request->post['filter_name'];

                $url .= '&filter_name=' . $this->request->post['filter_name'];

                $sort_id .= '&filter_name=' . $this->request->post['filter_name'];
            } elseif (isset($this->request->get['filter_name']) && $this->request->get['filter_name']) {
                $filter_name = $this->request->get['filter_name'];

                $url .= '&filter_name=' . $this->request->get['filter_name'];

                $sort_id .= '&filter_name=' . $this->request->get['filter_name'];
            }
        }

        $this->load->model('setting/setting');

        if (!$this->config->get('seourlgenerator_only_to_latin')) {
            $this->only_to_latin = 0;
        } else {
            $this->only_to_latin = $this->config->get('seourlgenerator_only_to_latin');
        }

        if (!$this->config->get('seourlgenerator_status')) {
            $data['status'] = 0;
        } else {
            $data['status'] = $this->config->get('seourlgenerator_status');
        }

        $data['canonical_products'] = 0;

        if ($this->config->get('seourlgenerator_canonical_products')) {
            $data['canonical_products'] = 1;
        }

        $data['check_main_category'] = $this->model_extension_module_seourlgenerator->checkDBColumn('product_to_category','main_category');

        $data['select_main_category'] = 0;
        if ($this->config->get('seourlgenerator_select_main_category')) {
            $data['select_main_category'] = 1;
        }

        $data['breadcrumb_list'] = 0;
        if ($this->config->get('seourlgenerator_breadcrumb_list')) {
            $data['breadcrumb_list'] = 1;
        }

        $data['product_microdata_status'] = array();
        if ($this->config->get('seourlgenerator_product_microdata_status')) {
            $data['product_microdata_status'] = $this->config->get('seourlgenerator_product_microdata_status');
        }

        $new_seo_urls = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePermission()) {
            if (isset($this->request->get['setting'])) {
                $setting['seourlgenerator_status'] = $this->request->post['status'];

                $setting['seourlgenerator_only_to_latin'] = $this->request->post['only_to_latin'];

                $setting['seourlgenerator_canonical_products'] = $this->request->post['canonical_products'];

                if (isset($this->request->post['select_main_category'])) {
                    $setting['seourlgenerator_select_main_category'] = $this->request->post['select_main_category'];
                } else {
                    $setting['seourlgenerator_select_main_category'] = 0;
                }

                $setting['seourlgenerator_breadcrumb_list'] = $this->request->post['breadcrumb_list'];

                $setting['seourlgenerator_product_microdata_status'] = $this->request->post['product_microdata_status'];

                //$setting['seourlgenerator_product_microdata_priceCurrency'] = $this->request->post['product_microdata_priceCurrency'];

                //создаем папку для скриптов, если еще не создавалась
                if (!$this->mkDirForMicrodata()) {
                    $this->session->data['error'] = $this->language->get('text_breadcrumb_list_error');
                }

                $this->model_setting_setting->editSetting('seourlgenerator', $setting);

                $this->session->data['success'] = $this->language->get('text_success_setting');

                $this->response->redirect($this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'].$url.'&page=' . $page, 'SSL'));
            }

            if ($this->validate()) {
                if (isset($this->request->get['save'])) {
                    $this->save();

                    $this->session->data['success'] = $this->language->get('text_success');

                    $this->response->redirect($this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'].$url.'&page=' . $page, 'SSL'));
                }

                if (isset($this->request->get['seo_generate'])) {
                    $new_seo_urls = $this->seoUrlGenerate();

                    if (!$new_seo_urls) {
                        $this->error['warning'] = $this->language->get('error_seo_generation');
                    } else {
                        $this->session->data['success'] = $this->language->get('text_success_seo_generation');
                    }
                }
            }
        }

        $data['only_to_latin'] = $this->only_to_latin;

        $data['text_only_to_latin'] = $this->language->get('text_only_to_latin');

        $data['tab_welcome_extecom'] = $this->language->get('tab_welcome_extecom');

        $data['new_seo_urls'] = $new_seo_urls;

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $data['error_warning'].'<br>' . $this->session->data['error'];

            unset($this->session->data['error']);
        }

        $data_seos = array(
            'start'       => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'       => $this->config->get('config_limit_admin'),
            'sort'        => $sort,
            'filter_name' => $filter_name
        );

        $data['filter_name'] = $filter_name;

        $seos = $this->model_extension_module_seourlgenerator->getSeos($data_seos);

        if ($seos['seos']) {
            foreach ($seos['seos'] as $id_seos => $seos_row) {
                //v1.1
                if (isset($seos_row['title'])) {
                    $seos['seos'][$id_seos]['name'] = $seos_row['title'];
                }
                ///v1.1

                //v1.2
                if (isset($seos_row['article_title'])) {
                    $seos['seos'][$id_seos]['name'] = $seos_row['article_title'];
                }
                ////v1.2

                if (isset($this->request->post['selected']) && isset($this->request->post['selected'][$id_seos])) {
                    $seos['seos'][$id_seos]['selected'] = 1;
                } else {
                    $seos['seos'][$id_seos]['selected'] = 0;
                }

                if (isset($this->request->post['name']) && isset($this->request->post['name'][$id_seos])) {
                    $seos['seos'][$id_seos]['url_alias'] = $this->request->post['name'][$id_seos];
                }
            }
        }

        $data['tab'] = $tab;

        $data['seos'] = $seos['seos'];

        $pagination = new Pagination();
        $pagination->total = $seos['total_seos'];
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($seos['total_seos']) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($seos['total_seos'] - $this->config->get('config_limit_admin'))) ? $seos['total_seos'] : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $seos['total_seos'], ceil($seos['total_seos'] / $this->config->get('config_limit_admin')));

        $data['save'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . $url. '&save=1' . '&page='.(int)$page, 'SSL');
        $data['seo_generate'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . $url. '&seo_generate=1' . '&page='.(int)$page, 'SSL');
        $data['seo_filter'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . $url. '&filter=1', 'SSL');
        $data['seo_setting'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . $url. '&setting=1', 'SSL');
        $data['sort_id'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . $sort_id, 'SSL');
        $data['order_id'] = $order_id;
        $data['seo_products'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . '&products=asc-seos_data.product_id', 'SSL');
        $data['seo_categories'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . '&categories=asc-seos_data.category_id', 'SSL');
        $data['seo_manufactures'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . '&manufactures=asc-seos_data.manufacturer_id', 'SSL');
        $data['seo_informations'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . '&informations=asc-seos_data.information_id', 'SSL');
        $data['simple_blog'] = $this->simple_blog;

        if ($this->simple_blog) {
            $data['seo_simpleblogarticles'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . '&simpleblogarticles=asc-seos_data.simple_blog_article_id', 'SSL');
            $data['seo_simpleblogcategories'] = $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'] . '&simpleblogcategories=asc-seos_data.simple_blog_category_id', 'SSL');
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_seo_generate'] = $this->language->get('button_seo_generate');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_setting'] = $this->language->get('button_setting');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_keyword'] = $this->language->get('column_keyword');
        $data['column_id'] = $this->language->get('column_id');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['dublicates'] = $this->dublicates;
        $data['text_seo_generate'] = $this->language->get('text_seo_generate');
        $data['text_seo_products'] = $this->language->get('text_seo_products');
        $data['text_seo_categories'] = $this->language->get('text_seo_categories');
        $data['text_seo_manufactures'] = $this->language->get('text_seo_manufactures');
        $data['text_seo_informations'] = $this->language->get('text_seo_informations');
        $data['text_remove_seorl'] = $this->language->get('text_remove_seorl');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_canonical_products'] = $this->language->get('text_canonical_products');
        $data['text_select_main_category'] = $this->language->get('text_select_main_category');

        $data['text_breadcrumb_list'] = $this->language->get('text_breadcrumb_list');

        $data['text_product_microdata_status'] = $this->language->get('text_product_microdata_status');
        $data['text_product_microdata_image'] = $this->language->get('text_product_microdata_image');
        $data['text_product_microdata_brand'] = $this->language->get('text_product_microdata_brand');
        $data['text_product_microdata_aggregateRating'] = $this->language->get('text_product_microdata_aggregateRating');
        $data['text_product_microdata_review'] = $this->language->get('text_product_microdata_review');
        $data['text_product_microdata_offerCount'] = $this->language->get('text_product_microdata_offerCount');
        $data['text_product_microdata_brand'] = $this->language->get('text_product_microdata_brand');
        $data['text_product_microdata_settings'] = $this->language->get('text_product_microdata_settings');
        $data['text_product_microdata_priceCurrency'] = $this->language->get('text_product_microdata_priceCurrency');
        $data['text_product_microdata_availability'] = $this->language->get('text_product_microdata_availability');

        $data['text_seo_simpleblogarticles'] = $this->language->get('text_seo_simpleblogarticles');
        $data['text_seo_simpleblogcategories'] = $this->language->get('text_seo_simpleblogcategories');

        $data['back'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL');
        $data['button_back'] = $this->language->get( 'button_back' );

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/module/seourlgenerator', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/seourlgenerator.tpl', $data));
    }

    public function getConfigLanguageId() {
        $configLanguageId = $this->config->get('config_language_id');

        echo $configLanguageId;
    }

    protected function save() {
        $query_part = 'product_id';

        if (isset($this->request->get['categories'])) {
            $query_part = 'category_id';
        } elseif (isset($this->request->get['manufactures'])) {
            $query_part = 'manufacturer_id';
        } elseif (isset($this->request->get['informations'])) { //v1.1
            $query_part = 'information_id';
        } elseif (isset($this->request->get['simpleblogarticles'])) { //v1.2
            $query_part = 'simple_blog_article_id';
        } elseif (isset($this->request->get['simpleblogcategories'])) {
            $query_part = 'simple_blog_category_id';
        }

        $seos = array();

        foreach ($this->request->post['selected'] as $value) {
            $seos[$value] = $this->request->post['name'][$value];
        }

        $this->model_extension_module_seourlgenerator->save($query_part,$seos);
    }

    protected function seoUrlGenerate() {
        $query_part = 'product_id';

        if (isset($this->request->get['categories'])) {
            $query_part = 'category_id';
        } elseif (isset($this->request->get['manufactures'])) {
            $query_part = 'manufacturer_id';
        } elseif (isset($this->request->get['informations'])) { //v1.1
            $query_part = 'information_id';
        } elseif (isset($this->request->get['simpleblogarticles'])) { //v1.2
            $query_part = 'simple_blog_article_id';
        } elseif (isset($this->request->get['simpleblogcategories'])) {
            $query_part = 'simple_blog_category_id';
        }

        $seos = array();

        foreach ($this->request->post['selected'] as $value) {
            $seos[$value] = $this->request->post['name'][$value];
        }

        return $this->model_extension_module_seourlgenerator->seoUrlGenerate($query_part,$seos,$this->only_to_latin);
    }

    private function mkDirForMicrodata() {
        return TRUE;

        $check_dir = DIR_CATALOG.'view/javascript/jsonldmicrodata';

        if (!is_dir($check_dir)) {
            mkdir($check_dir);
        }

        if (!is_dir($check_dir)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function seourlgenerateajax() {
        $this->load->model('setting/setting');

        $only_to_latin = $this->config->get('seourlgenerator_only_to_latin');

        $status = $this->config->get('seourlgenerator_status');

        //0 если вызов после ввода названия, 1 если от клика на кнокпу - в этом случае статус не важен
        $autogenerator = $this->request->post['autogenerator'];

        $result = '';

        if (($status && !$autogenerator) || $autogenerator ) {
            $query_part = $this->request->post['query_part'];
            $name = $this->request->post['name'];
            $id = (int)$this->request->post['id'];
            $seos[$id] = $name;
            $this->load->model('extension/module/seourlgenerator');
            $result = $this->model_extension_module_seourlgenerator->seoUrlGenerateAjax($query_part,$seos,$only_to_latin);
        }

        echo $result;
    }

    protected function validatePermission() {
        if (!$this->user->hasPermission('modify', 'extension/module/seourlgenerator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/seourlgenerator')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $query_part = 'product_id';
        if (isset($this->request->get['categories'])) {
            $query_part = 'category_id';
        } elseif (isset($this->request->get['manufactures'])) {
            $query_part = 'manufacturer_id';
        } elseif (isset($this->request->get['informations'])) { //v1.1
            $query_part = 'information_id';
        } elseif (isset($this->request->get['simpleblogarticles'])) { //v1.2
            $query_part = 'simple_blog_article_id';
        } elseif (isset($this->request->get['simpleblogcategories'])) {
            $query_part = 'simple_blog_category_id';
        }

        $seos = array();

        if ( (!isset($this->request->post['selected']) || !$this->request->post['selected']) && !isset($this->request->get['filter'])) {
            $this->error['warning'] = $this->language->get('error_selected');
        }

        if (isset($this->request->get['save']) && isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $value) {
                $seos[$value] = $this->request->post['name'][$value];
            }

            $this->dublicates = $this->model_extension_module_seourlgenerator->getDublicates($query_part,$seos);
        }

        if ($this->dublicates) {
            $this->error['warning'] = $this->language->get('error_dublicates');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
