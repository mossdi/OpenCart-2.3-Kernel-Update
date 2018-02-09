<?php
class ModelExtensionModuleAutoSeoTitle extends Model {
    //Product card
    public function setProduct($ajax = false, $product_info = array(), $price, $category_info = array()) {
        $seo_title = (array)$this->config->get('autoseotitle_product');
        if (isset($seo_title[$this->config->get('config_language_id')]) && $seo_title[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'              => $product_info['name'],
                '[meta_title]'        => $product_info['meta_title'],
                '[meta_h1]'           => isset($product_info['meta_h1'])?$product_info['meta_h1']?$product_info['meta_h1']:$product_info['name']:'',
                '[store_name]'        => $this->config->get('config_name'),
                '[price]'             => $price,
                '[model]'             => $product_info['model'],
                '[category_name]'     => !empty($category_info['name'])?$category_info['name']:'',
                '[manufacturer_name]' => $product_info['manufacturer']
            );

            if ($this->config->get('autoseotitle_rewrite') || !$product_info['meta_title']) {
                $meta_title = str_replace(array_keys($pattern), array_values($pattern), $seo_title[$this->config->get('config_language_id')]);
                $this->document->setTitle($meta_title);
            }
        }

        $seo_description = (array)$this->config->get('autoseotitle_descr_product');
        if (isset($seo_description[$this->config->get('config_language_id')]) && $seo_description[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'              => $product_info['name'],
                '[meta_title]'        => $product_info['meta_title'],
                '[meta_h1]'           => isset($product_info['meta_h1'])?$product_info['meta_h1']?$product_info['meta_h1']:$product_info['name']:'',
                '[store_name]'        => $this->config->get('config_name'),
                '[price]'             => $price,
                '[model]'             => $product_info['model'],
                '[category_name]'     => !empty($category_info['name'])?$category_info['name']:'',
                '[manufacturer_name]' => $product_info['manufacturer']
            );

            if ($this->config->get('autoseotitle_descr_rewrite') || !$product_info['meta_description']) {
                $meta_description = str_replace(array_keys($pattern), array_values($pattern), $seo_description[$this->config->get('config_language_id')]);
                $this->document->setDescription($meta_description);
            }
        }

        $seo_keyword = (array)$this->config->get('autoseotitle_keyw_product');
        if (isset($seo_keyword[$this->config->get('config_language_id')]) && $seo_keyword[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'              => $product_info['name'],
                '[meta_title]'        => $product_info['meta_title'],
                '[meta_h1]'           => isset($product_info['meta_h1'])?$product_info['meta_h1']?$product_info['meta_h1']:$product_info['name']:'',
                '[store_name]'        => $this->config->get('config_name'),
                '[price]'             => $price,
                '[model]'             => $product_info['model'],
                '[category_name]'     => !empty($category_info['name'])?$category_info['name']:'',
                '[manufacturer_name]' => $product_info['manufacturer']
            );

            if ($this->config->get('autoseotitle_keyw_rewrite') || !$product_info['meta_keyword']) {
                $meta_keyword = str_replace(array_keys($pattern), array_values($pattern), $seo_keyword[$this->config->get('config_language_id')]);
                $this->document->setKeywords($meta_keyword);
            }
        }

        if ($ajax) {
            $meta_data = array(
                'meta_title'       => !empty($meta_title) ? $meta_title : $product_info['meta_title'],
                'meta_description' => !empty($meta_description) ? $meta_description : $product_info['meta_description'],
                'meta_keyword'     => !empty($meta_keyword) ? $meta_keyword : $product_info['meta_keyword']
            );

            return $meta_data;
        }
    }

    //Category page
    public function setCategory($category_info = array()) {
        $seo_title = (array)$this->config->get('autoseotitle_category');
        if (isset($seo_title[$this->config->get('config_language_id')]) && $seo_title[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'        => $category_info['name'],
                '[meta_h1]'     => isset($category_info['meta_h1'])?$category_info['meta_h1']?$category_info['meta_h1']:$category_info['name']:'',
                '[meta_title]'  => $category_info['meta_title'],
                '{filter}'      => isset($filter_ocfilter)?'{filter}':'',
                '[store_name]'  => $this->config->get('config_name'),
            );

            if (isset($this->request->get['manufacturer_id'])) {
                $manufacturer_title = ' | ' . $this->language->get('text_manufacturer') . ' ' . $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id'])['name'];
            } else {
                $manufacturer_title = '';
            }
            if (isset($this->request->get['min_price']) && isset($this->request->get['max_price'])) {
                $price_gap_title = ' | ' . $this->language->get('text_from') . ' ' . $this->currency->format($this->request->get['min_price'], $this->session->data['currency']) . ' ' . $this->language->get('text_to') . ' ' . $this->currency->format($this->request->get['max_price'], $this->session->data['currency']);
            } else {
                $price_gap_title = '';
            }
            if (isset($this->request->get['in_stock']) && $this->request->get['in_stock'] == true ) {
                $in_stock_title = ' | ' . $this->language->get('entry_in_stock');
            } else {
                $in_stock_title = '';
            }
            if ((isset($this->request->get['sort']) && $this->request->get['sort'] == 'p.price') && (isset($this->request->get['order']) && $this->request->get['order'] == 'DESC')) {
                $sort_price_title = ' | ' . $this->language->get('text_sort') . ' ' . $this->language->get('text_price_desc');
            } elseif ((isset($this->request->get['sort']) && $this->request->get['sort'] == 'p.price') && (isset($this->request->get['order']) && $this->request->get['order'] == 'ASC')) {
                $sort_price_title = ' | ' . $this->language->get('text_sort') . ' ' .  $this->language->get('text_price_asc');
            } else {
                $sort_price_title = '';
            }
            if ((isset($this->request->get['sort']) && $this->request->get['sort'] == 'cd.name') && (isset($this->request->get['order']) && $this->request->get['order'] == 'DESC')) {
                $sort_name_title = ' | ' . $this->language->get('text_sort') . ' ' . $this->language->get('text_name_desc');
            } elseif ((isset($this->request->get['sort']) && $this->request->get['sort'] == 'cd.name') && (isset($this->request->get['order']) && $this->request->get['order'] == 'ASC')) {
                $sort_name_title = ' | ' . $this->language->get('text_sort') . ' ' .  $this->language->get('text_name_asc');
            } else {
                $sort_name_title = '';
            }
            if (isset($this->request->get['limit'])) {
                $limit_title = ' | ' . $this->language->get('text_limit') . ' ' . $this->request->get['limit'];
            } else {
                $limit_title = '';
            }
            if (isset($this->request->get['filter'])) {
                $filter_title = ' | ' . $this->request->get['filter'];
            } else {
                $filter_title = '';
            }

            $sort_title = array(
                'manufacturer_title' => $manufacturer_title,
                'price_gap_title'    => $price_gap_title,
                'in_stock_title'     => $in_stock_title,
                'sort_price_title'   => $sort_price_title,
                'sort_name_title'    => $sort_name_title,
                'limit_title'        => $limit_title,
                'filter_title'       => $filter_title
            );

            $page_title = (array)$this->config->get('autoseotitle_page');

            if (isset($this->request->get['page']) && ($this->request->get['page']>1) && isset($page_title[$this->config->get('config_language_id')]) && $page_title[$this->config->get('config_language_id')]) {
                if (strstr($page_title[$this->config->get('config_language_id')], '[page_num]')) {
                    $add_page = str_replace('[page_num]', $this->request->get['page'], $page_title[$this->config->get('config_language_id')]);
                } else {
                    $add_page = $page_title[$this->config->get('config_language_id')] . ' ' . $this->request->get['page'];
                }
            } else {
                $add_page = '';
            }

            if ($this->config->get('autoseotitle_rewrite') || !$category_info['meta_title']) {
                $this->document->setTitle(str_replace(array_keys($pattern), array_values($pattern), $seo_title[$this->config->get('config_language_id')]) . $add_page . implode('', array_values($sort_title)));
            }
        }

        $seo_description = (array)$this->config->get('autoseotitle_descr_category');
        if (isset($seo_description[$this->config->get('config_language_id')]) && $seo_description[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'        => $category_info['name'],
                '[meta_h1]'     => isset($category_info['meta_h1'])?$category_info['meta_h1']?$category_info['meta_h1']:$category_info['name']:'',
                '{filter}'      => isset($filter_ocfilter)?'{filter}':'',
                '[meta_title]'  => $category_info['meta_title'],
                '[store_name]'  => $this->config->get('config_name'),
            );

            $page_title = (array)$this->config->get('autoseotitle_page');
            if (isset($this->request->get['page']) && ($this->request->get['page']>1) &&
                isset($page_title[$this->config->get('config_language_id')]) && $page_title[$this->config->get('config_language_id')]
            ) {
                if (strstr($page_title[$this->config->get('config_language_id')], '[page_num]')) {
                    $add_page = str_replace('[page_num]', $this->request->get['page'], $page_title[$this->config->get('config_language_id')]);
                } else {
                    $add_page = $page_title[$this->config->get('config_language_id')] . ' ' . $this->request->get['page'];
                }
            } else {
                $add_page = '';
            }

            if ($this->config->get('autoseotitle_descr_rewrite') || !$category_info['meta_description']) {
                $this->document->setDescription(str_replace(array_keys($pattern), array_values($pattern), $seo_description[$this->config->get('config_language_id')]) . $add_page);
            }
        }

        $seo_keyword = (array)$this->config->get('autoseotitle_keyw_category');
        if (isset($seo_keyword[$this->config->get('config_language_id')]) && $seo_keyword[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'        => $category_info['name'],
                '[meta_h1]'     => isset($category_info['meta_h1'])?$category_info['meta_h1']?$category_info['meta_h1']:$category_info['name']:'',
                '{filter}'      => isset($filter_ocfilter)?'{filter}':'',
                '[meta_title]'  => $category_info['meta_title'],
                '[store_name]'  => $this->config->get('config_name'),
            );

            $page_title = (array)$this->config->get('autoseotitle_page');
            if (isset($this->request->get['page']) && ($this->request->get['page']>1) && isset($page_title[$this->config->get('config_language_id')]) && $page_title[$this->config->get('config_language_id')]) {
                if (strstr($page_title[$this->config->get('config_language_id')], '[page_num]')) {
                    $add_page = str_replace('[page_num]', $this->request->get['page'], $page_title[$this->config->get('config_language_id')]);
                } else {
                    $add_page = $page_title[$this->config->get('config_language_id')] . ' ' . $this->request->get['page'];
                }
            } else {
                $add_page = '';
            }

            if ($this->config->get('autoseotitle_keyw_rewrite') || !$category_info['meta_keyword']) {
                $this->document->setKeywords(str_replace(array_keys($pattern),array_values($pattern),$seo_keyword[$this->config->get('config_language_id')]) . $add_page);
            }
        }
    }

    //Manufacturer info
    public function setManufacturer($manufacturer_info = array()) {
        $seo_title = (array)$this->config->get('autoseotitle_manufacturer');

        if (isset($seo_title[$this->config->get('config_language_id')]) && $seo_title[$this->config->get('config_language_id')]) {
            $manufacturer_info['meta_h1'] = !empty($manufacturer_info['meta_h1']) ? $manufacturer_info['meta_h1'] : $manufacturer_info['name'];
            $manufacturer_info['meta_title'] = !empty($manufacturer_info['meta_title']) ? $manufacturer_info['meta_title'] : false;
            $manufacturer_info['meta_description'] = !empty($manufacturer_info['meta_description']) ? $manufacturer_info['meta_description'] : false;
            $manufacturer_info['meta_keyword'] = !empty($manufacturer_info['meta_keyword']) ? $manufacturer_info['meta_keyword'] : false;

            $pattern = array(
                '[name]'       => $manufacturer_info['name'],
                '[meta_h1]'    => $manufacturer_info['meta_h1'],
                '[meta_title]' => $manufacturer_info['meta_title'],
                '[store_name]' => $this->config->get('config_name'),
            );

            if ((isset($this->request->get['sort']) && $this->request->get['sort'] == 'cd.name') && (isset($this->request->get['order']) && $this->request->get['order'] == 'DESC')) {
                $sort_name_title = ' | ' . $this->language->get('text_sort') . ' ' . $this->language->get('text_name_desc');
            } elseif ((isset($this->request->get['sort']) && $this->request->get['sort'] == 'cd.name') && (isset($this->request->get['order']) && $this->request->get['order'] == 'ASC')) {
                $sort_name_title = ' | ' . $this->language->get('text_sort') . ' ' .  $this->language->get('text_name_asc');
            } else {
                $sort_name_title = '';
            }
            if (isset($this->request->get['limit'])) {
                $limit_title = ' | ' . $this->language->get('text_limit') .  ' ' . $this->request->get['limit'];
            } else {
                $limit_title = '';
            }
            if (isset($this->request->get['filter'])) {
                $filter_title = ' | ' . $this->request->get['filter'];
            } else {
                $filter_title = '';
            }
            if (isset($this->request->get['in_stock']) && $this->request->get['in_stock'] == true ) {
                $in_stock_title = ' | ' . $this->language->get('entry_in_stock');
            } else {
                $in_stock_title = '';
            }

            $sort_title = array(
                'sort_name_title'    => $sort_name_title,
                'limit_title'        => $limit_title,
                'filter_title'       => $filter_title,
                'in_stock_title'     => $in_stock_title
            );

            $page_title = (array)$this->config->get('autoseotitle_page');
            if (isset($this->request->get['page']) && ($this->request->get['page']>1) &&
                isset($page_title[$this->config->get('config_language_id')]) && $page_title[$this->config->get('config_language_id')]
            ) {
                if (strstr($page_title[$this->config->get('config_language_id')], '[page_num]')) {
                    $add_page = str_replace('[page_num]', $this->request->get['page'], $page_title[$this->config->get('config_language_id')]);
                } else {
                    $add_page = $page_title[$this->config->get('config_language_id')] . ' ' . $this->request->get['page'];
                }
            } else {
                $add_page = '';
            }

            if ($this->config->get('autoseotitle_rewrite') || !$manufacturer_info['meta_title']) {
                $this->document->setTitle(str_replace(array_keys($pattern), array_values($pattern), $seo_title[$this->config->get('config_language_id')]) . $add_page . implode('', array_values($sort_title)));
            }
        }

        $seo_description = (array)$this->config->get('autoseotitle_descr_manufacturer');
        if (isset($seo_description[$this->config->get('config_language_id')]) && $seo_description[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'              => $manufacturer_info['name'],
                '[meta_h1]'           => $manufacturer_info['meta_h1'],
                '[meta_title]'        => $manufacturer_info['meta_title'],
                '[store_name]'        => $this->config->get('config_name'),
            );

            $page_title = (array)$this->config->get('autoseotitle_page');
            if (isset($this->request->get['page']) && ($this->request->get['page']>1) &&
                isset($page_title[$this->config->get('config_language_id')]) && $page_title[$this->config->get('config_language_id')]
            ) {
                if (strstr($page_title[$this->config->get('config_language_id')], '[page_num]')) {
                    $add_page = str_replace('[page_num]', $this->request->get['page'], $page_title[$this->config->get('config_language_id')]);
                } else {
                    $add_page = $page_title[$this->config->get('config_language_id')] . ' ' . $this->request->get['page'];
                }
            } else {
                $add_page = '';
            }

            if ($this->config->get('autoseotitle_descr_rewrite') || !$manufacturer_info['meta_description']) {
                $this->document->setDescription(str_replace(array_keys($pattern), array_values($pattern), $seo_description[$this->config->get('config_language_id')]) . $add_page);
            }
        }

        $seo_keyword = (array)$this->config->get('autoseotitle_keyw_manufacturer');
        if (isset($seo_keyword[$this->config->get('config_language_id')]) && $seo_keyword[$this->config->get('config_language_id')]) {
            $pattern = array(
                '[name]'              => $manufacturer_info['name'],
                '[meta_h1]'           => $manufacturer_info['meta_h1'],
                '[meta_title]'        => $manufacturer_info['meta_title'],
                '[store_name]'        => $this->config->get('config_name'),
            );

            $page_title = (array)$this->config->get('autoseotitle_page');
            if (isset($this->request->get['page']) && ($this->request->get['page']>1) &&
                isset($page_title[$this->config->get('config_language_id')]) && $page_title[$this->config->get('config_language_id')]
            ) {
                if (strstr($page_title[$this->config->get('config_language_id')], '[page_num]')) {
                    $add_page = str_replace('[page_num]', $this->request->get['page'], $page_title[$this->config->get('config_language_id')]);
                } else {
                    $add_page = $page_title[$this->config->get('config_language_id')] . ' ' . $this->request->get['page'];
                }
            } else {
                $add_page = '';
            }

            if ($this->config->get('autoseotitle_keyw_rewrite') || !$manufacturer_info['meta_keyword']) {
                $this->document->setKeywords(str_replace(array_keys($pattern), array_values($pattern), $seo_keyword[$this->config->get('config_language_id')]) . $add_page);
            }
        }
    }
}
