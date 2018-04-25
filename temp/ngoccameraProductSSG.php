<?php

if (preg_match('~Đường dẫn trang không tồn tại.~', $output)) {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_NOT_FOUND;
    return $result;
}

$result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_IN;

if (preg_match('~<div class="row detail-table-price">.+?class="text-danger">\s*([0-9\.]+)~', $output, $matches)) {
    $result['price'] = (int)str_replace('.', '', $matches[1]);
    if ( $result['price'] == 0 ) {
        $result['error_status'] = PRODUCT_MERCHANT_ERROR_PRICE;
        return $result;
    }
} else {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_PATTERN;
    return $result;
}

if (preg_match('~<div class="detail-picture-big">.+?src="(.+?)"~', $output, $matches)) {
    $result['img'] = 'http://ngoccamera.vn'.$matches[1];
} else
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_IMAGE;

if ( preg_match('~<h1 class="detail-title"><span>(.+?)<\/span><\/h1>~', $output, $matches) ) {
    $result['name'] = $matches[1];
} else
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_NAME;

$result['merchant_brand'] = $result['merchant_category'] = '';
if ( preg_match('~<ol class="breadcrumb">.+?<\/ol>~', $output, $matches) ) {
    if ( preg_match_all('~<li.+?itemprop="title">(.+?)<\/span>~', $matches[0], $categoryMatch) ) {
        $result['merchant_category'] = end($categoryMatch[1]);
        $result['breadcrumb_list'] = [end($categoryMatch[1])];
    }
}

if ( preg_match('~\d+$~', $url, $matches) ) {
    $result['merchant_product_id'] = $matches[0];
} else
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_MERCHANT_PRODUCT_ID;

\CoreSSG\Helpers\ProductMerchantHelper::getMerchantBrandFromName($result['merchant_brand'], $result['name'], 1);