<?php
class ModelCatalogLandingPages extends Model {
    public function getLandingPages() {
        $query = $this->db->query("
            SELECT * FROM " . DB_PREFIX . "landing_page lp
            LEFT JOIN " . DB_PREFIX . "landing_page_description lpd ON (lp.landing_id = lpd.landing_id)
        ");

        return $query->rows;
    }
}
