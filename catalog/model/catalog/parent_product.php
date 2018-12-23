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
}
