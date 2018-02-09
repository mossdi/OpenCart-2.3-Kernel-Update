<?php
class ModelExtensionPaymentPaymentForm extends Model {
    public function getInvoice($order_info = array(), $style = '') {
        $this->load->language('extension/payment/payment_form');

        $logo = nl2br($this->config->get('config_logo'));
        if ($logo){
            $logo ='<p><img class="logo" src="image/' . $logo . '"/></p>';
        } else {
            $logo = '';
        }

        //Account info
        $invoice = $order_info['order_id'] . $this->language->get('text_from') . date($this->language->get('date_format_short'), strtotime($order_info['date_added'])) . $this->language->get('text_year_short');
        $payer = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'];
        $consignee = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'] . ', ' . $order_info['shipping_city'] . ', ' . $order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2'];

        //Products
        $this->load->model('account/order');

        $products = $this->model_account_order->getOrderProducts($order_info['order_id']);

        $product_list_body = '';

        foreach ($products as $key => $product) {
            $product_list_body .= '
            <tr>
                <td>' . ++$key . '</td>
                <td>' . $product['name'];

                //Options
                $options = $this->model_account_order->getOrderOptions($order_info['order_id'], $product['order_product_id']);

                if ($options) {
                    foreach ($options as $option) {
                        $product_list_body .= '<br/><span class="font-size-10"><small>- ' . $option['name'] . ' : ' . $option['value'] . '</small></span>';
                    }
                }

                $product['price'] = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
                $product['total'] = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);

                $product_list_body .= '</td>
                <td>' . $this->language->get('text_pieces_short') . '</td>
                <td>x' . $product['quantity'] . '</td>
                <td class="text-right">' . $product['price'] . '</td>
                <td class="text-right">' . $product['total'] . '</td>
            </tr>';
        }

        //Total
        $totals = $this->model_account_order->getOrderTotals($order_info['order_id']);

        foreach ($totals as $total) {
            $product_list_body .= '
            <tr>
                <td colspan="5" class="text-right border-none"><strong>' . strip_tags($total['title']) . '</strong></td>
                <td class="text-right">' . $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'], true) . '</td>
            </tr>';
        }

        $total_in_words = $this->numbers(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false))), " ."));

        //Attachments type
        if ($style == 'pdf') {
            $styles = '
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style type="text/css">                
                body{
                  font-family: DejaVu Sans;
                  max-width: 100%;
                  padding: 20px;
                  font-size: 12px;
                }
                h2 {
                  font-weight: bold;
                  font-size: 16px;
                }
                .logo, .head {
                    
                }
                .text-right {
                    text-align: right;
                }
                .text-center {
                    text-align: center;
                }
                .border-none {
                    border: none;
                }
                table {
                    width: 100%;
                    border-collapse:collapse;
                }
                table.border td {
                    border: 1px solid;
                }
                table.padding tr td {
                    padding: 10px;
                } 
                table thead tr td {
                    font-weight: bold;
                }
                hr {
                    margin-bottom: 20px;
                    border: none;
                }
            </style>';
        }

        //Payment details
        $payment_details = $this->config->get('dm_project_payment_details');

        //HTML
        $html = $styles;

        $html .= $logo;

        $html .= '
            <div class="head">
              <h2>' . $payment_details['payment_receiver_name'] . '</h2>
              <p>
              ' . $payment_details['payment_receiver_address'] . '<br>
              ' . $this->config->get('config_telephone') . '
              </p>
            </div>';

        $html .= '
            <table class="padding" border="1">
              <tr>
                <td><strong>' . $this->language->get('text_inn') . '</strong> ' . $payment_details['payment_receiver_inn'] . '</td>
                <td>' . $payment_details['payment_receiver_kpp'] . '</td>
                <td rowspan="2" valign="bottom"><strong>' . $this->language->get('text_account_num') . '</strong></td>
                <td rowspan="2" valign="bottom">' . $payment_details['payment_receiver_account'] . '</td>
              </tr>
              <tr>
                <td colspan="2">
                <strong>' . $this->language->get('text_payment_receiver') . '</strong><br>
                ' . $payment_details['payment_receiver_name'] . '
                </td>            
              </tr>
              <tr>
                <td colspan="2">
                <strong>' . $this->language->get('text_payees_bank') . '</strong><br>
                ' . $payment_details['payment_receiver_bank_name'] . '
                </td>
                <td>
                <strong>' . $this->language->get('text_bank_bic') . '</strong><br>
                <strong>' . $this->language->get('text_account_num') . '</strong>
                </td>
                <td>
                ' . $payment_details['payment_receiver_bank_bic'] . '<br>
                ' . $payment_details['payment_receiver_bank_cor_acct'] . '
                </td>
              </tr>
            </table>';

        $html .= '
            <hr>
            <h1>' . $this->language->get('text_account_num') . 'SR_' . $invoice . '</h1>
            <p>
                <span><strong>' . $this->language->get('text_payer') . '</strong>: ' . $payer . '</span><br/>
                <span><strong>' . $this->language->get('text_consignee') . '</strong>: ' . $consignee . '</span>
            </p>
            <hr>';

        $html .= '
            <table class="border text-center" border="1" cellpadding="5px">
              <thead>
                <tr>    
                  <td>№</td>
                  <td>' . $this->language->get('text_product_name') . '</td>
                  <td>' . $this->language->get('text_unit') . '</td>
                  <td>' . $this->language->get('text_qty') . '</td>
                  <td class="text-right">' . $this->language->get('text_price') . '</td>
                  <td class="text-right">' . $this->language->get('text_total') . '</td>
                </tr>
              </thead>
              <tbody>
                ' . $product_list_body . '                
              </tbody>
            </table>
            <hr>';

        $html .= '<p>' . $this->language->get('text_total_unit') . ' <strong>' . $key . '</strong>, ' . $this->language->get('text_for_sum') . ' <strong>' . number_format(floatval(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false))), " .")), 2, '.', ' ') . ' ' . $this->language->get('text_rub') . '</strong><br/>
                 (<b>' . $total_in_words . '</b>)';

        $this->load->model('tool/dompdf');

        $file = $this->model_tool_dompdf->getFile($html, 'Order_Invoice_№SR_' . $order_info['order_id'], 'order_invoices', $style);
        
        if ($file) {
            return $file;
        } else {
            return false;
        }
    }

    public function numbers($num) {
        $nul='ноль';
        
        $ten = array(
            array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
            array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        );
        
        $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        
        $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        
        $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        
        $unit = array(
            array('копейка',  'копейки',  'копеек',     1),
            array('рубль',    'рубля',    'рублей',     0),
            array('тысяча',   'тысячи',   'тысяч',      1),
            array('миллион',  'миллиона', 'миллионов',  0),
            array('миллиард', 'милиарда', 'миллиардов', 0),
        );
        
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        
        $out = array();
        
        if (intval($rub)>0) {
            foreach(str_split($rub,3) as $uk=>$v) {
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1;
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));

                $out[] = $hundred[$i1];
                if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3];
                else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3];

                if ($uk>1) $out[]= $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            }
        } else {
            $out[] = $nul;
        }
        
        $out[] = $this->morph(intval($rub),$unit[1][0],$unit[1][1],$unit[1][2]);
        $out[] = $kop.' '.$this->morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]);
        
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    public function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;

        if ($n > 10 && $n < 20) return $f5;

        $n = $n % 10;

        if ($n > 1 && $n < 5) return $f2;

        if ($n == 1) return $f1;

        return $f5;
    }
}
