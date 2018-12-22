<?php
class ModelServiceQuickEditProduct extends Model {
    public function editProduct($product_id, $data) {
        $this->db->query(
            "UPDATE " . DB_PREFIX . "product SET            
            model           = '" . $this->db->escape($data['model']) . "',      
            sku             = '" . $this->db->escape($data['sku']) . "',
            manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
            status          = '" . (int)$data['status'] . "',
            date_modified   = NOW()        
            WHERE product_id = '" . (int)$product_id . "'"
        );

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query(
                "INSERT INTO " . DB_PREFIX . "product_description SET                 
                product_id       = '" . (int)$product_id . "',
                language_id      = '" . (int)$language_id . "',
                name             = '" . $this->db->escape($value['name']) . "',
                description      = '" . $this->db->escape($value['description']) . "',
                tag              = '" . $this->db->escape($value['tag']) . "',
                meta_title       = '" . $this->db->escape($value['meta_title']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword     = '" . $this->db->escape($value['meta_keyword']) . "'"
            );
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

        if (!empty($data['product_attribute'])) {
            foreach ($data['product_attribute'] as $product_attribute) {
                if ($product_attribute['attribute_id']) {
                    // Removes duplicates
                    $this->db->query(
                        "DELETE FROM " . DB_PREFIX . "product_attribute
                        WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'"
                    );

                    foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
                        $this->db->query(
                            "INSERT INTO " . DB_PREFIX . "product_attribute SET                            
                            product_id   = '" . (int)$product_id . "',
                            attribute_id = '" . (int)$product_attribute['attribute_id'] . "',
                            language_id  = '" . (int)$language_id . "',
                            text         = '" .  $this->db->escape($product_attribute_description['text']) . "'"
                        );
                    }
                }
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query(
                    "INSERT INTO " . DB_PREFIX . "product_to_category SET            
                    product_id  = '" . (int)$product_id . "',
                    category_id = '" . (int)$category_id . "'"
                );
            }
        }

        if (isset($data['main_category_id']) && $data['main_category_id'] > 0) {
            $this->load->model('catalog/product');

            $this->db->query(
                "DELETE FROM " . DB_PREFIX . "product_to_category
                WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['main_category_id'] . "'"
            );
            $this->db->query(
                "INSERT INTO " . DB_PREFIX . "product_to_category SET            
                product_id    = '" . (int)$product_id . "',
                category_id   = '" . (int)$data['main_category_id'] . "',
                main_category = '1'"
            );
            $this->db->query(
                "UPDATE " . DB_PREFIX . "product SET               
                variation        = '" . (int)$this->model_catalog_product->getMainCategoryInfo((int)$data['main_category_id']) . "'              
                WHERE product_id = '" . (int)$product_id . "'"
            );
        } elseif (isset($data['product_category'][0])) {
            $this->db->query(
                "UPDATE " . DB_PREFIX . "product_to_category SET
                main_category    = '1'
                WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$data['product_category'][0] . "'"
            );
            $this->db->query(
                "UPDATE " . DB_PREFIX . "product SET
                variation        = '" . (int)$this->model_catalog_product->getMainCategoryInfo((int)$data['product_category'][0]) . "'
                WHERE product_id = '" . (int)$product_id . "'"
            );
        } else {
            $this->db->query(
                "UPDATE " . DB_PREFIX . "product SET           
                variation        = '0'
                WHERE product_id = '" . (int)$product_id . "'"
            );
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

        if ($data['keyword']) {
            $this->db->query(
                "INSERT INTO " . DB_PREFIX . "url_alias SET            
                query   = 'product_id=" . (int)$product_id . "',
                keyword = '" . $this->db->escape($data['keyword']) . "'"
            );
        }

        $this->cache->delete('product');
    }
}
