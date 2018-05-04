<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var){
    var_dump($var);
    die();
}

$result = [];
$url = 'http://ngoccamera.vn/san-pham/canon-eos-m3-id242';
$output = getContent($url);

if (preg_match('~Đường dẫn trang không tồn tại.~', $output)) {
    vdd('trang 404');
}

//if ( preg_match('~<h1 class="detail-title">~', $output) ) {
//    $result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_IN;
//} else {
//    $result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_OUT;
//}

if ( preg_match_all('~<div class="row detail-table-price">.+?class="text-danger">\s*([0-9\.]+)~', $output, $matches) ) {
    $result['price'] = (int)str_replace('.', '', end($matches[1]));
}

if ( preg_match('~<h1 class="detail-title"><span>(.+?)<\/span><\/h1>~', $output, $matches) ) {
    $result['name'] = $matches[1];
}

if ( preg_match('~<div class="detail-picture-big">.+?src="(.+?)"~', $output, $matches) ) {
    $result['img'] = 'http://ngoccamera.vn'.$matches[1];
}

$result['merchant_brand'] = $result['merchant_category'] = '';
if ( preg_match('~<ol class="breadcrumb">.+?<\/ol>~', $output, $matches) ) {
    if ( preg_match_all('~<li.+?<a href="(.+?)".+?itemprop="title">(.+?)<\/span>~', $matches[0], $categoryMatch) ) {
        $result['merchant_category'] = end($categoryMatch[2]);
        $result['breadcrumb_list'] = [end($categoryMatch[1])];
    }
}

if ( preg_match('~\d+$~', $url, $matches) ) {
    $result['merchant_product_id'] = $matches[0];
}

$result['merchant_product_sku'] = '';

vdd($result);