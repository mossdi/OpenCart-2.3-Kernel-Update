<?php
class ModelCatalogCatalog extends Model {
    public function getCatalog() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "catalog JOIN " . DB_PREFIX . "catalog_description cd WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }
}