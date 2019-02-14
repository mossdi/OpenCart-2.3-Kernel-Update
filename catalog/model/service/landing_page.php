<?php
class ModelServiceLandingPage extends Model {
    public function isLandingPage($category_id) {
        if (empty($this->request->get['attribute_filter']) && empty($this->request->get['manufacturer_id'])) {
            return false;
        }

        $landing_pages = $this->db->query("SELECT lpd.title, lpd.description, lp.attribute_id, lpd.attribute_value, lp.manufacturer_id FROM " . DB_PREFIX . "landing_page lp
         INNER JOIN " . DB_PREFIX . "landing_page_description lpd ON (lp.landing_id = lpd.landing_id AND language_id = " . (int)$this->config->get('config_language_id') . ")
         WHERE category_id = " . (int)$category_id);

        foreach ($landing_pages->rows as $landing_page) {
            if (!empty($this->request->get['attribute_filter'][$landing_page['attribute_id']]) && !empty($this->request->get['manufacturer_id']) &&
                mb_strtolower($landing_page['attribute_value']) == mb_strtolower($this->request->get['attribute_filter'][$landing_page['attribute_id']]) &&
                (int)$landing_page['manufacturer_id'] == (int)$this->request->get['manufacturer_id']) {
                $landing_page_description = $landing_page;
                break;
            } elseif (!empty($this->request->get['attribute_filter'][$landing_page['attribute_id']]) &&
                mb_strtolower($landing_page['attribute_value']) == mb_strtolower($this->request->get['attribute_filter'][$landing_page['attribute_id']]) &&
                empty($landing_page['manufacturer_id'])) {
                $landing_page_description = $landing_page;
            } elseif (!empty($this->request->get['manufacturer_id']) &&
                (int)$landing_page['manufacturer_id'] == (int)$this->request->get['manufacturer_id'] &&
                empty($landing_page['attribute_id'])) {
                $landing_page_description = $landing_page;
            }
        }

        if (!empty($landing_page_description)) {
            $canonical_url = "path=" . $category_id;

            if ($landing_page_description['manufacturer_id']) {
                $manufacturer_id = $landing_page_description['manufacturer_id'];

                $canonical_url .= "&manufacturer_id=" . $manufacturer_id;
            }

            if ($landing_page_description['attribute_id']) {
                $attribute_filter_id    = $landing_page_description['attribute_id'];
                $attribute_filter_value = $this->request->get['attribute_filter'][$attribute_filter_id];

                $canonical_url .= "&attribute_filter[" . $attribute_filter_id . "]=" . $attribute_filter_value;
            }

            return array(
                'landing_description' => $landing_page_description,
                'landing_canonical'   => $canonical_url,
            );
        } else {
            return false;
        }
    }
}
