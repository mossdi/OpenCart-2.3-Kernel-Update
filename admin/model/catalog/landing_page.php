<?php
class ModelCatalogLandingPage extends Model {
    public function addLandingPage($data = array()) {
        $sql = "INSERT INTO " . DB_PREFIX . "landing_page SET category_id = " . (int)$data['category_id'];

        if (!empty($data['attribute_id'])) {
            $sql .= ", attribute_id = " . $data['attribute_id'];
        }

        if (!empty($data['manufacturer_id'])) {
            $sql .= ", manufacturer_id = " . $data['manufacturer_id'];
        }

        $this->db->query($sql);

        $landing_id = $this->db->getLastId();

        foreach ($data['landing_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_description
                              SET landing_id = '" . (int)$landing_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }
    }

    public function editLandingPage($landing_id, $data = array()) {
        $sql = "UPDATE " . DB_PREFIX . "landing_page SET category_id = " . (int)$data['category_id'];

        if (!empty($data['attribute_id'])) {
            $sql .= ", attribute_id = " . $data['attribute_id'];
        } else {
            $sql .= ", attribute_id = NULL";
        }

        if (!empty($data['manufacturer_id'])) {
            $sql .= ", manufacturer_id = " . $data['manufacturer_id'];
        } else {
            $sql .= ", manufacturer_id = NULL";
        }

        $sql .= " WHERE landing_id = " . (int)$landing_id;

        $this->db->query($sql);

        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page_description WHERE landing_id = " . (int)$landing_id);

        foreach ($data['landing_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "landing_page_description
                              SET landing_id = '" . (int)$landing_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }
    }

    public function deleteLandingPage($landing_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "landing_page WHERE landing_id = " . (int)$landing_id);
    }

    public function getLandingPage($landing_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "landing_page lp WHERE landing_id = " . (int)$landing_id;

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getLandingPageDescription($landing_id) {
        $landing_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "landing_page_description WHERE landing_id = '" . (int)$landing_id . "'");

        foreach ($query->rows as $result) {
            $landing_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'description'      => $result['description']
            );
        }

        return $landing_description_data;
    }

    public function getLandingPages($data = array()) {
        $language_id = $this->config->get('config_language_id');

        $sql = "SELECT * FROM " . DB_PREFIX . "landing_page lp
                LEFT JOIN " . DB_PREFIX . "landing_page_description lpd ON (lp.landing_id = lpd.landing_id AND lpd.language_id = " . $language_id . ")";

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

    public function getTotalLandingPages() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "landing_page");

        return $query->row['total'];
    }
}

