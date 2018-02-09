<?php
class ModelExtensionPaymentNextpay extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/nextpay');

        $method_data = array(
            'code'       => 'nextpay',
            'title'      => $this->language->get('text_title'),
            'sort_order' => $this->config->get('nextpay_sort_order'),
            'terms'      => '',
        );

        return $method_data;
    }

    public function getPaymentUrl($order_info = array()) {
        $url = '';

        if ($order_info) {
            $fullName = $order_info['lastname'] . " " . $order_info['firstname'];
            $fullName = trim($fullName);

            $ext_order_cost = $this->caclAmount($order_info['total'], $order_info['currency_value']);
            $ext_order_cost_enc = urlencode($ext_order_cost);

            $order_id_enc = urlencode($this->to1251($order_info['order_id']));

            $product_id_enc = $this->to1251($this->config->get('nextpay_product'));
            $product_id_enc = urlencode($product_id_enc);

            $url .= "https://www.nextpay.ru/buy/index.php?product_id=$product_id_enc&command=show_product_form_ext&seller_ext_order_id=$order_id_enc&ext_order_cost=$ext_order_cost_enc";
            if ($order_info['email'] != null) {
                $email = $this->to1251($order_info['email']);
                $email = urlencode($email);
                $url .= "&np_email=$email";
            }
            if ($fullName != null) {
                $fullName = $this->to1251($fullName);
                $fullName = urlencode($fullName);
                $url .= "&np_payer=$fullName";
            }
        }

        return $url;
    }

    private function to1251($value) {
        return iconv("utf-8", "windows-1251", $value);
    }

    private function caclAmount($total, $currency_value) {
        $ret = $total * $currency_value;
        $ret = round($ret, 2);

        return $ret;
    }
}
