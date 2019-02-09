<?php
class ModelCatalogLandingPage extends Model {
    public function getLandingPages($data = array()) {
        $language_id = $this->config->get('config_admin_language');

        $sql = "SELECT * FROM " . DB_PREFIX . "landing_page lp
                LEFT JOIN " . DB_PREFIX . "landing_page_description lpd ON (lp.landing_id = lpd.landing_id AND lpd.language_id = " . $language_id . ")
                ";

        $sort_data = array('name');

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
}
