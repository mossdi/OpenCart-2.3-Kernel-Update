<?php
class ControllerEventLastModified extends Controller {
	private $layout_route = array(
	    'common/home',
	    'product/product',
	    'product/category',
	    'information/information',
	    'product/manufacturer',
	    'product/manufacturer/info'
	);
	
	private function changeLayoutDate($layout_route) {
		$sql = "SELECT * FROM " . DB_PREFIX . "last_modified WHERE layoute_route = '" . $this->db->escape($layout_route) . "'";
		$res = $this->db->query($sql);
		if ($res->num_rows) {
			$sql = "UPDATE " . DB_PREFIX . "last_modified SET date_modified = NOW() WHERE layoute_route = '" . $this->db->escape($layout_route) . "'";
		} else {
			$sql = "INSERT INTO " . DB_PREFIX . "last_modified SET date_modified = NOW(),
			layoute_route = '" . $this->db->escape($layout_route) . "'";
		}
		$this->db->query($sql);
	}
	
//	admin\model\design\layout.php
//	editLayout($this->request->get['layout_id'], $this->request->post);
	public function changeLayoutsDate(&$layout_id, &$dat) {
		if (!$this->config->get('last_modified_enable')) return;
		$data = $dat[1];
		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $l_r) {
				if (in_array($l_r['route'], $this->layout_route)) {
					$this->changeLayoutDate($l_r['route']);
				}
			}
		}
	}
	
//	admin\model\setting\setting.php	
//	public function editSetting($code, $data, $store_id = 0) {
	public function changeEditSetting(&$method, &$data) {
		if (!$this->config->get('last_modified_enable')) return;
		$code = $data[0];
		$sql = "
            SELECT 
              lm.`layout_module_id`,
              lm.`layout_id`,
              lm.code,
              lr.route
            FROM " . DB_PREFIX . "layout_module lm
            JOIN " . DB_PREFIX . "layout l ON lm.layout_id = l.layout_id
            JOIN " . DB_PREFIX . "layout_route lr ON lr.layout_id = lm.layout_id 
            WHERE lm.code = '" . $this->db->escape($code) . "'";
		$res = $this->db->query($sql);
		foreach ($res->rows as $row) {
			$this->changeLayoutDate($row['route']);
		}

	}
	
// 	admin\model\extension\module.php
//	public function editModule($module_id, $data) {
	public function changeModuleDate(&$route, &$dat) {
		if (!$this->config->get('last_modified_enable')) return;
		$module_id = $dat[0];
		$sql_in = implode("','", $this->layout_route);
		$sql = "
            SELECT  
              lm.`layout_module_id`,
              lm.`layout_id`,
              lm.code,
              lr.route
            FROM " . DB_PREFIX . "layout_module lm
            JOIN " . DB_PREFIX . "layout l ON lm.layout_id = l.layout_id
            LEFT JOIN " . DB_PREFIX . "module  m ON lm.code LIKE CONCAT(m.code,'%')
            JOIN " . DB_PREFIX . "layout_route lr ON lr.layout_id = lm.layout_id 
            WHERE lr.route IN ('" . $sql_in . "')
            AND m.module_id = '" . (int)$module_id . "'";
		$res = $this->db->query($sql);
		foreach ($res->rows as $row) {
			$this->changeLayoutDate($row['route']);
		}
	}

// 	admin\model\catalog\category.php
	public function changeCategoryDate(&$route, &$dat, &$output) {
		if (!$this->config->get('last_modified_enable')) return;
		$category_id = $dat[0];
		$sql = "SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$sql = "UPDATE " . DB_PREFIX . "category SET date_modified = NOW() WHERE category_id = '" . (int)$query->row['parent_id'] . "'";
			$this->db->query($sql);
		}
	}

// 	admin\model\catalog\product.php
	public function changeProductDate(&$route, &$dat, &$output) {
		if (!$this->config->get('last_modified_enable')) return;
		
		if ($route == 'catalog/product/deleteProduct' || $route == 'catalog/product/editProduct' || $route == 'catalog/product/addProduct') {
			$product_id = $dat[0];
			if ($product_id) {
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category	WHERE product_id = '" . (int)$product_id . "'");
				foreach ($query->rows as $row) {
					$sql = "UPDATE " . DB_PREFIX . "category SET date_modified = NOW() WHERE category_id = '" . (int)$row['category_id'] . "'";
					$this->db->query($sql);
				}
					
				$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product	WHERE product_id ='" . (int)$product_id . "'");
				if ($query->num_rows) {
					$sql = "UPDATE " . DB_PREFIX . "manufacturer SET date_modified = NOW() WHERE manufacturer_id = '" . (int)$query->row['manufacturer_id'] . "'";
					$this->db->query($sql);
				}
			}
		}
		if ($route == 'catalog/product/editProduct') {
			$product_id = $dat[0];
			$data = $dat[1];
			$sql = "UPDATE " . DB_PREFIX . "manufacturer SET date_modified = NOW() WHERE manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
			$this->db->query($sql);
			if ($product_id) {
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category	WHERE product_id = '" . (int)$product_id . "'");
				foreach ($query->rows as $row) {
					$sql = "UPDATE " . DB_PREFIX . "category SET date_modified = NOW() WHERE category_id = '" . (int)$row['category_id'] . "'";
					$this->db->query($sql);
				}
			}
		}
	}
}
