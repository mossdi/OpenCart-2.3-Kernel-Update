<?php
class ModelCatalogParentProduct extends Model {
    public function getProductsAnyType($data) {
        $sql = "SELECT p.product_id AS id, pd.name AS name, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND p.quantity > '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) as special,";

        if ($data['sort'] == 'price') {
            $sql .= " p.price AS price,";
        }

        $sql .= " p.manufacturer_id AS manufacturer_id, p.attribute_groups AS attribute_groups, p.attribute_display AS attribute_display, p.sort_order AS sort_order, p.variation AS parent_product FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
            }
        }

        $sql .= " WHERE cp.path_id = '" . (int)$data['filter_category_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " AND
            (CASE
                WHEN
                    (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                THEN
                    ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                ELSE
                    p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
            END)";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
            }
        }

        $sql .= " GROUP BY p.product_id";

        $sql .= " UNION ALL ";

        $sql .= "SELECT c.category_id AS id, cd.name AS name,";

        if ($data['sort'] == 'price' && $data['order'] == 'ASC') {
            $sql .= " (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND p.quantity > '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,";
            $sql .= " MIN(p.price) AS price,";
        } elseif ($data['sort'] == 'price' && $data['order'] == 'DESC') {
            $sql .= " (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND p.quantity > '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price DESC LIMIT 1) AS special,";
            $sql .= " MAX(p.price) AS price,";
        } else {
            $sql .= " (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND p.quantity > '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,";
        }

        $sql .= " MAX(p.manufacturer_id) AS manufacturer_id, c.attribute_groups AS attribute_groups, c.attribute_display AS attribute_display, c.sort_order AS sort_order, c.product_display AS parent_product FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
            }
        }

        $sql .= " WHERE cp.path_id = '" . (int)$data['filter_category_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " AND
            (CASE
                WHEN
                    (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                THEN
                    ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                ELSE
                    p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
            END)";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
            }
        }

        $sql .= " GROUP BY c.category_id";

        $sort_data = array(
            'name',
            'price'
        );

        if (isset($data['sort']) && isset($data['order']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'cd.name') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ") " . $data['order'] . "";
            } elseif ($data['sort'] == 'price') {
                $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special ELSE price END) " . $data['order'] . "";
            } else {
                $sql .= " ORDER BY " . $data['sort'] . " " . $data['order'] . "";
            }
        } else {
            $sql .= " ORDER BY sort_order, LCASE(name)";
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

    public function getTotalProductsAnyType($data) {
        $sql = "SELECT DISTINCT p.product_id AS id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
            }
        }

        $sql .= " WHERE cp.path_id = '" . (int)$data['filter_category_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " AND
            (CASE
                WHEN
                    (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                THEN
                    ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                ELSE
                    p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
            END)";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
            }
        }

        $sql .= " UNION ALL ";

        $sql .= "SELECT DISTINCT c.category_id AS id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
            }
        }

        $sql .= " WHERE cp.path_id = '" . (int)$data['filter_category_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " AND
            (CASE
                WHEN
                    (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                THEN
                    ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                ELSE
                    p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
            END)";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
            }
        }

        $query = $this->db->query($sql);

        return count($query->rows);
    }

    public function getSpecialProductsAnyType($data) {
        $sql = "SELECT p.product_id AS id, pd.name AS name,";

        if ($data['sort'] == 'price') {
            $sql .= " ps.price AS price,";
        }

        $sql .= " p.manufacturer_id AS manufacturer_id, p.attribute_groups AS attribute_groups, p.attribute_display AS attribute_display, p.sort_order AS sort_order, p.variation AS parent_product FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if (!empty($data['filter_min_price']) && !empty($data['filter_max_price'])) {
            $sql .= " AND ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        $sql .= " GROUP BY p.product_id";

        $sql .= " UNION ALL ";

        $sql .= "SELECT c.category_id AS id, cd.name AS name,";

        if ($data['sort'] == 'price' && $data['order'] == 'ASC') {
            $sql .= " MIN(ps.price) AS price,";
        } elseif ($data['sort'] == 'price' && $data['order'] == 'DESC') {
            $sql .= " MAX(ps.price) AS price,";
        }

        $sql .= " MAX(p.manufacturer_id) AS manufacturer_id, c.attribute_groups AS attribute_groups, c.attribute_display AS attribute_display, c.sort_order AS sort_order, c.product_display AS parent_product FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if (!empty($data['filter_min_price']) && !empty($data['filter_max_price'])) {
            $sql .= " AND ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        $sql .= " GROUP BY c.category_id";

        $sort_data = array(
            'name',
            'price'
        );

        if (isset($data['sort']) && isset($data['order']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'name') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ") " . $data['order'] . "";
            } else {
                $sql .= " ORDER BY " . $data['sort'] . " " . $data['order'] . "";
            }
        } else {
            $sql .= " ORDER BY sort_order, LCASE(name)";
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

    public function getTotalSpecialProductsAnyType($data) {
        $sql = "SELECT DISTINCT p.product_id AS id FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " AND ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        $sql .= " UNION ALL ";

        $sql .= "SELECT DISTINCT c.category_id AS id FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_min_price'] && $data['filter_max_price']) {
            $sql .= " AND ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        $query = $this->db->query($sql);

        return count($query->rows);
    }

    public function getLatestProductsAnyType($data) {
        $product_data = $this->cache->get('products_any_type.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (!empty($data['limit']) ? (int)$data['limit'] : ''));

        if (!$product_data) {
            $sql = "SELECT p.product_id AS id, pd.name AS name, p.manufacturer_id AS manufacturer_id, p.attribute_groups AS attribute_groups, p.attribute_display AS attribute_display, p.sort_order AS sort_order, p.variation AS parent_product, p.date_added AS date_added FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($data['filter_in_stock']) {
                $sql .= " AND p.quantity > '0'";
            }

            $sql .= " UNION ALL ";

            $sql .= "SELECT c.category_id AS id, cd.name AS name, MAX(p.manufacturer_id) AS manufacturer_id, c.attribute_groups AS attribute_groups, c.attribute_display AS attribute_display, c.sort_order AS sort_order, c.product_display AS parent_product, MAX(p.date_added) AS date_added FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($data['filter_in_stock']) {
                $sql .= " AND p.quantity > '0'";
            }

            $sql .= " GROUP BY c.category_id";

            $sql .= " ORDER BY date_added DESC";

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

            $product_data = $query->rows;

            $this->cache->set('products_any_type.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (!empty($data['limit']) ? (int)$data['limit'] : ''), $product_data);
        }

        return $product_data;
    }

    public function getParentInfoManufacturerProductsAnyType($data) {
        $categories_data = $this->cache->get('manufacturer_info_categories.' . (int)$data['filter_manufacturer_id'] . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));

        if (!$categories_data || 1 > 0) {

            $sql = "SELECT DISTINCT cd.category_id AS id, cd.name AS name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
            }

            $sql .= " WHERE p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " AND
                (CASE
                    WHEN
                        (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                    THEN
                        ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                    ELSE
                        p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
                END)";
            }

            $sql .= " UNION ";

            $sql .= "SELECT DISTINCT cd.category_id AS id, cd.name AS name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.parent_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
            }

            $sql .= " WHERE p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " AND
                (CASE
                    WHEN
                        (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                    THEN
                        ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                    ELSE
                        p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
                END)";
            }

            $sql .= " ORDER BY LCASE(name) ASC";

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 5;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            $categories_data = $query->rows;

            $this->cache->set('manufacturer_info_categories.' . (int)$data['filter_manufacturer_id'] . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $categories_data);
        }

        return $categories_data;
    }

    public function getTotalParentInfoManufacturerProductsAnyType($data) {
        $categories_data = $this->cache->get('total_manufacturer_info_categories.' . (int)$data['filter_manufacturer_id'] . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));

        if (!$categories_data || 1 > 0) {

            $sql = "SELECT DISTINCT cd.category_id AS id, cd.name AS name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
            }

            $sql .= " WHERE p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '0' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " AND
                (CASE
                    WHEN
                        (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                    THEN
                        ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                    ELSE
                        p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
                END)";
            }

            $sql .= " UNION ";

            $sql .= "SELECT DISTINCT cd.category_id AS id, cd.name AS name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.parent_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
            }

            $sql .= " WHERE p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.variation = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.product_display <> '0' AND c.status = '1' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " AND
                (CASE
                    WHEN
                        (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                    THEN
                        ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                    ELSE
                        p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
                END)";
            }

            $query = $this->db->query($sql);

            $categories_data = count($query->rows);

            $this->cache->set('total_manufacturer_info_categories.' . (int)$data['filter_manufacturer_id'] . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $categories_data);
        }

        return $categories_data;
    }

    public function getManufacturersProductsAnyType($data) {
        $sql = "SELECT m.image, m.manufacturer_id, m.name";
        if (isset($data['filter_special']) && $data['filter_special']) {
            $sql .= " FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)";

            if (!empty($data['filter_attribute'])) {
                foreach ($data['filter_attribute'] as $key => $value) {
                    $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
                }
            }

            $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.manufacturer_id <> '0' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " AND ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'";
            }

            if ($data['filter_in_stock']) {
                $sql .= " AND p.quantity > '0'";
            }

            if (!empty($data['filter_attribute'])) {
                foreach ($data['filter_attribute'] as $key => $value) {
                    $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
                }
            }
        } else {
            $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
            }

            if (!empty($data['filter_attribute'])) {
                foreach ($data['filter_attribute'] as $key => $value) {
                    $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
                }
            }

            $sql .= " WHERE cp.path_id = '" . (int)$data['filter_category_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.manufacturer_id <> '0'";

            if ($data['filter_min_price'] && $data['filter_max_price']) {
                $sql .= " AND
                (CASE
                    WHEN
                        (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                    THEN
                        ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                    ELSE
                        p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
                END)";
            }

            if ($data['filter_in_stock']) {
                $sql .= " AND p.quantity > '0'";
            }

            if (!empty($data['filter_attribute'])) {
                foreach ($data['filter_attribute'] as $key => $value) {
                    $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
                }
            }
        }

        $sql .= "  GROUP BY m.manufacturer_id ORDER BY LCASE(m.name)";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getMinMaxPriceProductsAnyType($data) {
        if (isset($data['filter_special']) && $data['filter_special']) {
            $sql = "SELECT MIN(ps.price) AS min_price, MAX(ps.price) AS max_price";
        } else {
            $sql = "SELECT MIN(p.price) AS min_price, MAX(p.price) AS max_price";
        }

        if (isset($data['filter_category_id'])) {
            $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
        } elseif (isset($data['filter_special']) && $data['filter_special']) {
            $sql .= " FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa_" . (int)$key . " ON (p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "')";
            }
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if (isset($data['filter_category_id'])) {
            $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
        }

        if ($data['filter_manufacturer_id']) {
            $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($data['filter_in_stock']) {
            $sql .= " AND p.quantity > '0'";
        }

        if (!empty($data['filter_attribute'])) {
            foreach ($data['filter_attribute'] as $key => $value) {
                $sql .= " AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($value) . "%'";
            }
        }

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getAttributeFilters($data) {
        $filters_attribute = array();

        if (!empty($data['filter_attributes_id'])) {
            foreach ($data['filter_attributes_id'] as $attribute_id) {
                $sql = "SELECT DISTINCT pa.text";

                if (isset($data['filter_category_id'])) {
                    $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
                } else {
                    $sql .= " FROM " . DB_PREFIX . "product p";
                }

                $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id)";

                if ($data['filter_min_price'] && $data['filter_max_price']) {
                    $sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
                }

                $sql .= " WHERE pa.attribute_id = '" . (int)$attribute_id . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

                if (isset($data['filter_category_id'])) {
                    $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
                }

                if ($data['filter_manufacturer_id']) {
                    $sql .= " AND p.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
                }

                if ($data['filter_min_price'] && $data['filter_max_price']) {
                    $sql .= " AND
                    (CASE
                        WHEN
                            (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (ps.logged = '0' OR ps.logged = '" . ($this->customer->isLogged() ? 1 : 0) . "') AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) IS NOT NULL AND p.quantity > '0'
                        THEN
                            ps.price >= '" . (float)$data['filter_min_price'] . "' AND ps.price <= '" . (float)$data['filter_max_price'] . "'
                        ELSE
                            p.price >= '" . (float)$data['filter_min_price'] . "' AND p.price <= '" . (float)$data['filter_max_price'] . "' 
                    END)";
                }

                if ($data['filter_in_stock']) {
                    $sql .= " AND p.quantity > '0'";
                }

                if (!empty($data['filter_attribute'])) {
                    foreach ($data['filter_attribute'] as $key => $attribute_value) {
                        if ($key != $attribute_id) {
                            $sql .= " AND (SELECT text FROM " . DB_PREFIX . "product_attribute pa_" . (int)$key . " WHERE p.product_id = pa_" . (int)$key . ".product_id AND pa_" . (int)$key . ".attribute_id = '" . (int)$key . "' AND pa_" . (int)$key . ".text LIKE '%" . $this->db->escape($attribute_value) . "%') IS NOT NULL";
                        }
                    }
                }

                $sql .= " ORDER BY LCASE(pa.text) ASC";

                $query = $this->db->query($sql);

                $attributes = array();

                if (!empty($query->rows)) {
                    foreach ($query->rows as $row) {
                        $attributes[] = $row['text'];
                    }
                }

                if ($attributes) {
                    $filter_group = $this->db->query("SELECT name FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . $attribute_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

                    $filters_attribute[] = array(
                        'group_name' => $filter_group->row['name'],
                        'group_id'   => $attribute_id,
                        'attributes' => $attributes
                    );
                }
            }

            ksort($filters_attribute);
        }

        return $filters_attribute;
    }

    public function getParentCategoryId($product_id) {
        $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category ='1'");

        if (empty($query->row)) {
            $query = $this->db->query("SELECT cp.category_id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = '" . (int)$product_id . "') LEFT JOIN " . DB_PREFIX . "category c ON (c.category_id = p2c.category_id AND c.product_display <> '0') WHERE cp.category_id = c.category_id ORDER BY cp.level DESC LIMIT 1");
        }

        return $query->row;
    }

    public function getCategoriesByName($name, $category_id) {
        $query = $this->db->query("SELECT DISTINCT category_id FROM " . DB_PREFIX . "category_description WHERE name LIKE '%" . $name . "%' AND category_id != '" . (int)$category_id . "'");

        return $query->rows;
    }

    //*********** Get view products ***********//

    public function getProductsAnyTypeView($products_type = '', $products_any_type, $category_info, $url = '', $in_stock = false, $data, $filter) {
        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        $data['products_any_type'] = array();

        foreach ($products_any_type as $product_any_type) {
            $product_preview = array();
            $product_variants = array();
            $product_manufacturer = array();

            if ($product_any_type['parent_product']) {
                $filter_data = array(
                    'filter_category_id'  => $product_any_type['id'],
                    'filter_in_stock'     => $in_stock,
                    'filter_sub_category' => false,
                    'filter_min_price'    => !empty($filter['filter_min_price']) ? $filter['filter_min_price'] : false,
                    'filter_max_price'    => !empty($filter['filter_max_price']) ? $filter['filter_max_price'] : false,
                    'filter_attribute'    => !empty($filter['filter_attribute']) ? $filter['filter_attribute'] : false,
                    'sort'                => !empty($filter['sort']) ? $filter['sort'] : false,
                    'order'               => !empty($filter['order']) ? $filter['order'] : false
                );

                if ($products_type == 'products_special') {
                    $variants = $this->model_catalog_product->getProductSpecials($filter_data);
                } else {
                    $variants = $this->model_catalog_product->getProducts($filter_data);
                }

                if ($variants) {
                    foreach ($variants as $variant) {
                        if ($variant['image']) {
                            $variant_img = $this->model_tool_image->resize($variant['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
                        } else {
                            $variant_img = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
                        }

                        $special_logged_info = $this->model_catalog_product->getProductSpecialsLogged($variant['product_id']);

                        if (!empty($special_logged_info['price']) && $variant['quantity'] > 0) {
                            $special_percent = round(($variant['price'] - $special_logged_info['price']) / ($variant['price']/100)) . '%';
                        } elseif ((float)$variant['special'] && $variant['quantity'] > 0) {
                            $special_percent = round(($variant['price'] - $variant['special']) / ($variant['price']/100)) . '%';
                        } else {
                            $special_percent = false;
                        }

                        $attribute_display = array();

                        if ($product_any_type['attribute_display']) {
                            $filter_attribute_display = array (
                                'product_id'   => $variant['product_id'],
                                'attribute_id' => $product_any_type['attribute_display']
                            );

                            $attribute_display = $this->model_catalog_product->getProductAttributeValue($filter_attribute_display);
                        }

                        $product_variant = array(
                            'name'            => $variant['name'],
                            'product_id'      => $variant['product_id'],
                            'special_percent' => $special_percent,
                            'image'           => $variant_img,
                            'attribute'       => $attribute_display ? $attribute_display['text'] : false
                        );

                        if ($product_any_type['attribute_groups']) {
                            $filter_attribute_groups = array (
                                'product_id'   => $variant['product_id'],
                                'attribute_id' => $product_any_type['attribute_groups']
                            );

                            $attribute = $this->model_catalog_product->getProductAttributeValue($filter_attribute_groups);

                            $attribute_group = $attribute['text'] ? mb_strtolower($attribute['text']) : 'empty';

                            $product_variants['groups'][$attribute_group][] = $product_variant;
                        } else {
                            $product_variants[] = $product_variant;
                        }
                    }

                    if (!empty($product_variants['groups'])) {
                        ksort($product_variants['groups']);

                        $first_group = reset($product_variants['groups']);
                        $first_product = reset($first_group);
                    } else {
                        $first_product = reset($product_variants);
                    }

                    $product_preview_info = $this->model_catalog_product->getProduct($first_product['product_id']);

                    if ($product_preview_info) {
                        if ($product_preview_info['image'] && (!empty($category_info['thumb_width']) && !empty($category_info['thumb_height'])) && ($category_info['thumb_width'] && $category_info['thumb_height'])) {
                            $category_image = $this->model_tool_image->resize($product_preview_info['image'], $category_info['thumb_width'], $category_info['thumb_height']);
                        } else if ($product_preview_info['image'] ) {
                            $category_image = $this->model_tool_image->resize($product_preview_info['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                        } else if ((!empty($category_info['thumb_width']) && !empty($category_info['thumb_height'])) && ($category_info['thumb_width'] && $category_info['thumb_height'])) {
                            $category_image = $this->model_tool_image->resize('placeholder.png', $category_info['thumb_width'], $category_info['thumb_height']);
                        } else {
                            $category_image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                        }

                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                            $price = $this->currency->format($this->tax->calculate($product_preview_info['price'], $product_preview_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        $special_logged_info = $this->model_catalog_product->getProductSpecialsLogged($product_preview_info['product_id']);

                        if (!empty($special_logged_info['price']) && $product_preview_info['quantity'] > 0) {
                            $special_logged_text = sprintf($this->language->get('text_special_logged'), $this->currency->format($this->tax->calculate($product_preview_info['price'] - $special_logged_info['price'], $product_preview_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']));
                        } else {
                            $special_logged_text = false;
                        }

                        if ((float)$product_preview_info['special'] && $product_preview_info['quantity'] > 0) {
                            $special = $this->currency->format($this->tax->calculate($product_preview_info['special'], $product_preview_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                            $special_percent = round(($product_preview_info['price'] - $product_preview_info['special']) / ($product_preview_info['price']/100)) . '%';
                        } else {
                            $special = false;
                            $special_percent = false;
                        }

                        if ($this->config->get('config_tax')) {
                            $tax = $this->currency->format((float)$product_preview_info['special'] ? $product_preview_info['special'] : $product_preview_info['price'], $this->session->data['currency']);
                        } else {
                            $tax = false;
                        }

                        $points = $product_preview_info['points'];

                        $stock = $this->language->get('text_stock') . ' ';
                        if ($product_preview_info['quantity'] <= 0) {
                            $stock .= $product_preview_info['stock_status'];
                        } elseif ($this->config->get('config_stock_display')) {
                            $stock .= $product_preview_info['quantity'];
                        }

                        $product_discounts = $this->model_catalog_product->getProductDiscounts($product_preview_info['product_id']);

                        $discounts = array();

                        foreach ($product_discounts as $discount) {
                            $discounts[] = array(
                                'quantity' => $discount['quantity'],
                                'price'    => $this->currency->format($this->tax->calculate($discount['price'], $variant['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                            );
                        }

                        $product_preview = array(
                            'name'            => $product_any_type['name'],
                            'product_id'      => $product_preview_info['product_id'],
                            'stock_qty'       => $product_preview_info['quantity'],
                            'stock'           => $stock,
                            'price'           => $price,
                            'special'         => $special,
                            'special_percent' => $special_logged_text ? $special_logged_text : $special_percent,
                            'image'           => $category_image,
                            'tax'             => $tax,
                            'points'          => $points,
                            'discount'        => $discounts,
                            'href'            => $this->url->link('product/product', 'product_id=' . $product_preview_info['product_id']),
                            'minimum'         => $product_preview_info['minimum'] > 0 ? $product_preview_info['minimum'] : 1
                        );
                    }
                }
            } else {
                $product_info = $this->model_catalog_product->getProduct($product_any_type['id']);

                if ($product_info) {
                    if ($product_info['image'] && (!empty($category_info['thumb_width']) && !empty($category_info['thumb_height'])) && ($category_info['thumb_width'] && $category_info['thumb_height'])) {
                        $category_image = $this->model_tool_image->resize($product_info['image'], $category_info['thumb_width'], $category_info['thumb_height']);
                    } else if ($product_info['image']) {
                        $category_image = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                    } else if ((!empty($category_info['thumb_width']) && !empty($category_info['thumb_height'])) && ($category_info['thumb_width'] && $category_info['thumb_height'])) {
                        $category_image = $this->model_tool_image->resize('placeholder.png', $category_info['thumb_width'], $category_info['thumb_height']);
                    } else {
                        $category_image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }

                    $special_logged_info = $this->model_catalog_product->getProductSpecialsLogged($product_info['product_id']);

                    if (!empty($special_logged_info['price']) && $product_info['quantity'] > 0) {
                        $special_logged_text = sprintf($this->language->get('text_special_logged'), $this->currency->format($this->tax->calculate($product_info['price'] - $special_logged_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']));
                    } else {
                        $special_logged_text = false;
                    }

                    if ((float)$product_info['special'] && $product_info['quantity'] > 0) {
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        $special_percent = round(($product_info['price'] - $product_info['special']) / ($product_info['price'] / 100)) . '%';
                    } else {
                        $special = false;
                        $special_percent = false;
                    }

                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
                    } else {
                        $tax = false;
                    }

                    $points = $product_info['points'];

                    $stock = $this->language->get('text_stock') . ' ';
                    if ($product_info['quantity'] <= 0) {
                        $stock .= $product_info['stock_status'];
                    } elseif ($this->config->get('config_stock_display')) {
                        $stock .= $product_info['quantity'];
                    }

                    $product_discounts = $this->model_catalog_product->getProductDiscounts($product_info['product_id']);

                    $discounts = array();

                    foreach ($product_discounts as $discount) {
                        $discounts[] = array(
                            'quantity' => $discount['quantity'],
                            'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                        );
                    }

                    $attribute_display = array();

                    if ($product_any_type['attribute_display']) {
                        $filter_attribute_display = array (
                            'product_id'   => $product_any_type['id'],
                            'attribute_id' => $product_any_type['attribute_display']
                        );

                        $attribute_display = $this->model_catalog_product->getProductAttributeValue($filter_attribute_display);
                    }

                    $product_preview = array(
                        'name'            => $product_info['name'],
                        'product_id'      => $product_info['product_id'],
                        'stock_qty'       => $product_info['quantity'],
                        'stock'           => $stock,
                        'price'           => $price,
                        'special'         => $special,
                        'special_percent' => $special_logged_text ? $special_logged_text : $special_percent,
                        'image'           => $category_image,
                        'attribute'       => $attribute_display ? $attribute_display['text'] : false,
                        'tax'             => $tax,
                        'points'          => $points,
                        'discount'        => $discounts,
                        'href'            => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                        'minimum'         => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1
                    );
                }
            }

            if (!empty($product_any_type['manufacturer_id']) && empty($data['manufacturer_logo'])) {
                $product_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_any_type['manufacturer_id']);

                if ($product_manufacturer_info) {
                    if ($product_manufacturer_info['image']) {
                        $image = $this->model_tool_image->resize($product_manufacturer_info['image'], $this->config->get($this->config->get('config_theme') . '_manufacturer_image_product_width'), $this->config->get($this->config->get('config_theme') . '_manufacturer_image_product_height'));
                    } else {
                        $image = false;
                    }

                    if ($products_type == 'products_special')
                        $filter_href = $this->url->link('product/special', 'manufacturer_id=' . $product_manufacturer_info['manufacturer_id']) . $url;
                    elseif ($products_type == 'products_category') {
                        $filter_href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&manufacturer_id=' . $product_manufacturer_info['manufacturer_id']);
                    } else {
                        $filter_href = false;
                    }

                    $product_manufacturer = array(
                        'name'        => $product_manufacturer_info['name'],
                        'image'       => $image,
                        'id'          => $product_manufacturer_info['manufacturer_id'],
                        'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_manufacturer_info['manufacturer_id']),
                        'filter_href' => $filter_href
                    );
                }
            }

            if (!empty($product_preview)) {
                $data['products_any_type'][] = array(
                    'id'               => $product_any_type['id'],
                    'product_preview'  => $product_preview,
                    'product_variants' => !empty($product_variants) ? $product_variants : false,
                    'variants_display' => (!empty($product_variants) && count($product_variants) > 1) || !empty($product_variants['groups']) ? true : false,
                    'manufacturer'     => $product_manufacturer
                );
            }
        }

        if ($category_info['products_display'] && file_exists(DIR_TEMPLATE  . $this->config->get('config_theme') . '/template/custom/category/category_products/' . $category_info['products_display'])) {
            $product_any_type_view = $this->load->view('custom/category/category_products/' . $category_info['products_display'], $data);
        } else {
            $product_any_type_view = $this->load->view('product/category_products', $data);
        }

        return $product_any_type_view;
    }

    public function getProductVariantsView($product_id, $product_variants, $category_info) {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $data['product_id'] = $product_id;

        $data['product_variants'] = array();

        foreach ($product_variants as $product_variant) {
            $special_logged_info = $this->model_catalog_product->getProductSpecialsLogged($product_variant['product_id']);

            if (!empty($special_logged_info['price']) && $product_variant['quantity'] > 0) {
                $special_percent = round(($product_variant['price'] - $special_logged_info['price']) / ($product_variant['price']/100)) . '%';
            } elseif ((float)$product_variant['special'] && $product_variant['quantity'] > 0) {
                $special_percent = round(($product_variant['price'] - $product_variant['special']) / ($product_variant['price']/100)) . '%';
            } else {
                $special_percent = false;
            }

            if ($product_variant['image']) {
                $image = $this->model_tool_image->resize($product_variant['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
            }

            $attribute_display = array();

            if ($category_info['attribute_display']) {
                $filter_attribute_display = array (
                    'product_id'   => $product_variant['product_id'],
                    'attribute_id' => $category_info['attribute_display']
                );

                $attribute_display = $this->model_catalog_product->getProductAttributeValue($filter_attribute_display);
            }

            $variant = array(
                'product_id'          => $product_variant['product_id'],
                'current_category_id' => $category_info['category_id'],
                'name'                => $product_variant['name'],
                'thumb'               => $image,
                'special_percent'     => $special_percent,
                'attribute'           => $attribute_display ? $attribute_display['text'] : false
            );

            if ($category_info['attribute_groups']) {
                $filter_attribute = array(
                    'product_id'   => $product_variant['product_id'],
                    'attribute_id' => $category_info['attribute_groups']
                );

                $attribute = $this->model_catalog_product->getProductAttributeValue($filter_attribute);

                $attribute_group =  $attribute['text'] ? $attribute['name'] . ' - ' . $attribute['text'] : '<span style="color:red;">value is undefined</span>';
//                if ($attribute['text'] && strrchr($attribute['name'], ',')) {
//                    $attribute_group .= substr(strrchr($attribute['name'], ','), 1);
//                }

                $data['product_variants']['groups'][$attribute_group][] = $variant;
            } else {
                $data['product_variants'][] = $variant;
            }
        }

        if (!empty($data['product_variants']['groups'])) {
            ksort($data['product_variants']['groups']);
        }

        if ($category_info['variations_display'] && file_exists(DIR_TEMPLATE  . $this->config->get('config_theme') . '/template/custom/product/product_variants/' . $category_info['variations_display'])) {
            $product_variants_view = $this->load->view('custom/product/product_variants/' . $category_info['variations_display'], $data);
        } else {
            $product_variants_view = $this->load->view('product/product_variants', $data);
        }

        return $product_variants_view;
    }
}
