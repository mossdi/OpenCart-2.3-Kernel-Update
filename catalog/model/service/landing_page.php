<?php
class ModelServiceLandingPage extends Model {
    public function isLandingPage($category_id) {
        if (empty($this->request->get['attribute_filter']) && empty($this->request->get['manufacturer_id'])) {
            return false;
        }

        $landing_page = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page WHERE category_id = " . (int)$category_id);

        if (empty($landing_page->row) ||
            ($landing_page->row['attribute_id'] && empty($this->request->get['attribute_filter'][$landing_page->row['attribute_id']])) ||
            ($landing_page->row['manufacturer_id'] && empty($this->request->get['manufacturer_id']))) {
            return false;
        }

        $landing_page_description = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "landing_page_description 
            WHERE landing_id = " . (int)$landing_page->row['landing_id'] . " AND language_id = " . (int)$this->config->get('config_language_id')
        );

        $canonical_url = "path=" . $category_id;

        if ($landing_page->row['attribute_id']) {
            $attribute_filter_id    = $landing_page->row['attribute_id'];
            $attribute_filter_value = $this->request->get['attribute_filter'][$attribute_filter_id];

            $canonical_url .= "&attribute_filter[" . $attribute_filter_id . "]=" . $attribute_filter_value;
        }

        if ($landing_page->row['manufacturer_id']) {
            $manufacturer_id = $landing_page->row['manufacturer_id'];

            $canonical_url .= "&manufacturer_id=" . $manufacturer_id;
        }

        return array(
            'landing_description' => $landing_page_description->row,
            'landing_canonical'   => $canonical_url,
        );
    }
}
