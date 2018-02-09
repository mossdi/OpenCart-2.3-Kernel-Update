<?php
class ControllerExtensionAnalyticsYandexMetrics extends Controller {
    public function index() {
		return html_entity_decode($this->config->get('yandex_metrics_code'), ENT_QUOTES, 'UTF-8');
	}
}
