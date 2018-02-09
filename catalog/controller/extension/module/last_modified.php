<?php
class ControllerExtensionModuleLastModified extends Controller {

	public function index($data = array()) {
		$ajax = (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		if ($ajax) return;
		if ($this->customer->isLogged()) return;
		if (isset($this->session->data['token'])) return;
		if (isset($this->session->data['is_cart'])) return;
		if (isset($this->session->data['api_id'])) return;
		if (isset($this->session->data['compare']) && $this->session->data['compare']) return;

		if ($this->config->get('last_modified_enable') && !$ajax) {
			if (isset($data['route'])) {
				$route = $data['route'];
			} else {
				$route = 'common/home';
			}

			switch ($route) {
				case 'common/home':
					if (!$this->config->get('last_modified_home')) return;
					$timestamp_modules = $this->getLastModifiedModules($route);
					break;
				case 'product/product':
					if (!$this->config->get('last_modified_product')) return;
					$timestamp_entity = strtotime($data['date_modified']);

					if ($this->config->get('last_modified_product_module')) {
						$timestamp_modules = max($this->getLastModifiedModules($route),$timestamp_entity);
					} else {
						$timestamp_modules = $timestamp_entity;
					}

					break;
				case 'product/category':
					if (!$this->config->get('last_modified_category')) return;
					$timestamp_entity = strtotime($data['date_modified']);
					if ($this->config->get('last_modified_category_module')) {
						$timestamp_modules = max($this->getLastModifiedModules($route),$timestamp_entity);
					} else {
						$timestamp_modules = $timestamp_entity;
					}
					break;
				case 'product/manufacturer':
				case 'product/manufacturer/info':
					if (!$this->config->get('last_modified_category')) return;
					$timestamp_entity = strtotime($data['date_modified']);
					if ($this->config->get('last_modified_category_module')) {
						$timestamp_modules = max($this->getLastModifiedModules($route),$timestamp_entity);
					} else {
						$timestamp_modules = $timestamp_entity;
					}
					break;
				case 'information/information':
					if (!$this->config->get('last_modified_information')) return;
					$timestamp_entity = strtotime($data['date_modified']);

					if ($this->config->get('last_modified_information_module')) {
						$timestamp_modules = max($this->getLastModifiedModules($route),$timestamp_entity);
					} else {
						$timestamp_modules = $timestamp_entity;
					}
					break;
			}

			$LastModified_unix = $timestamp_modules;
			$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
		
			$IfModifiedSince = false;
			$env = $this->request->clean($_ENV);
			if (isset($env['HTTP_IF_MODIFIED_SINCE'])) {
				$IfModifiedSince = strtotime(substr($env['HTTP_IF_MODIFIED_SINCE'], 5));  
			}
			if (isset($this->request->server['HTTP_IF_MODIFIED_SINCE'])) {
				$IfModifiedSince = strtotime(substr($this->request->server['HTTP_IF_MODIFIED_SINCE'], 5));
			}
			
			if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
				$protocol = (isset($this->request->server['SERVER_PROTOCOL'] ) ? $this->request->server['SERVER_PROTOCOL'] : 'HTTP/1.1');
				header($protocol . ' 304 Not Modified');
				exit;
			}
			
			$this->response->addHeader('Last-Modified: '. $LastModified);
			if ($this->config->get('last_modified_caching')) {
				$sec = round((float)str_replace(',', '.', $this->config->get('last_modified_expires')) * 60);
				if (!($sec > 0)) $sec = 60;
				$this->response->addHeader('Expires: ' . date('r', time() + $sec));
				$this->response->addHeader('Cache-Control: max-age=' . $sec);
			}
		}
	}

	private function getLastModifiedModules($route) {
		$sql = "SELECT date_modified FROM " . DB_PREFIX . "last_modified WHERE layoute_route = '" . $this->db->escape($route) . "'";
		$res = $this->db->query($sql);

		if ($res->num_rows) {
			return strtotime($res->row['date_modified']);
		} else {
			return false;
		}
	}
	
	private function getLastModifiedProductsCategory($category_id) {
		$sql = "
			SELECT date_modified FROM " . DB_PREFIX . "product p
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
			if ($category_id) {
				$sql .= "INNER JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			$sql .= " WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
					  p.status = '1'";
			if ($category_id) {
				$sql .= " AND p2c.category_id = " . (int)$category_id;
			}	
		$sql .=	" ORDER BY date_modified DESC LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows)
			return strtotime($query->row['date_modified']);
		else 
			return false;
	}
	
	private function getLastModifiedProductsManufacturer($manufacturer_id) {
		$sql = "
			SELECT date_modified FROM " . DB_PREFIX . "product p
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
			$sql .= " WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
					  p.status = '1'";
			$sql .= " AND p.manufacturer_id = " . (int)$manufacturer_id;
		$sql .=	" ORDER BY date_modified DESC LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows)
			return strtotime($query->row['date_modified']);
		else 
			return false;
	}
}