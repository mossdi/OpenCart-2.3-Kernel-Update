<?php
class ModelCatalogCatalog extends Model {
    public function getCatalog() {
        $query = $this->db->query("SELECT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product/catalog') AS keyword FROM " . DB_PREFIX . "catalog");

        return $query->row;
    }

    public function getCatalogDescription() {
        $catalog_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "catalog_description");

        foreach ($query->rows as $result) {
            $catalog_description_data[$result['language_id']] = array(
                'meta_h1'          => $result['meta_h1'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'description'      => $result['description']
            );
        }

        return $catalog_description_data;
    }

    public function editCatalog($data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "catalog");
        $this->db->query("INSERT INTO " . DB_PREFIX . "catalog SET catalog_display = '" . $data['catalog_display'] . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "catalog_description");
        foreach ($data['catalog_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "catalog_description SET language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "', meta_h1 = '" . $this->db->escape($value['meta_h1']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product/catalog'");
        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product/catalog', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
    }
}