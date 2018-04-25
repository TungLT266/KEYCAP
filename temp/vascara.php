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
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

$output = getContent('https://www.vascara.com/balo/balo-bac-0083-mau-xanh-da-troi');

if ( preg_match('/<title>Trang không tìm thấy \| VASCARA <\/title>/', $output)) {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_NOT_FOUND;
    return $result;
}

if ( preg_match('/<div class="button buy-now">MUA NGAY<\/div>/', $output) ) {
    $result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_IN;
} else {
    $result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_OUT;
}

if ( preg_match('/<li class="discount-price">(.+?)\s*?VND<\/li>/', $output, $matches) ) {
    $result['price'] = (int)str_replace('.', '', $matches[1]);

    if ( $result['price'] === 0 ) {
        $result['error_status'] = PRODUCT_MERCHANT_ERROR_PRICE;
        return $result;
    }
} elseif(preg_match('/<li class="price">(.+?)\s*?VND<\/li>/', $output, $matches)){
    $result['price'] = (int)str_replace('.', '', $matches[1]);

    if ( $result['price'] === 0 ) {
        $result['error_status'] = PRODUCT_MERCHANT_ERROR_PRICE;
        return $result;
    }
}else {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_PATTERN;
    return $result;
}

if ( preg_match('/<h1 class="title-product">(.+?)<\/h1>/', $output, $matches) ) {
    $result['name'] = $matches[1];
} else {
    $result['error_status'] |=  PRODUCT_MERCHANT_ERROR_NAME;
}

if ( preg_match('/<div class="list-images-product slide-detail owl-carousel">.*?<img src="(.+?)"/s', $output, $matches) ) {
    $result['img'] = $matches[1];
    if (preg_match('/^(.+?)@2x(\.jpg)/', $matches[1], $matches)){
        $result['img'] = $matches[1].$matches[2];
    }
} else {
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_IMAGE;
}

$result['merchant_brand'] = $result['merchant_category'] = '';
if ( preg_match('/<div class="breadcrumb">.*?(?:<li><a.*?>(.+?)<\/a><\/li>.*?)*?<\/ul>\s*<\/div>/s', $output, $matches) ) {
    $result['merchant_category'] = $matches[1];
}

//id san pham
if ( preg_match('/id="productId".*?value="(\d+)"/', $output, $matches) ) {
    $result['merchant_product_id'] = $matches[1];
} else {
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_MERCHANT_PRODUCT_ID;
}

//id kho hang
if ( preg_match('/id="productCode".*?value="(.*?)"/', $output, $matches) ) {
    $result['merchant_product_sku'] = $matches[1];
} else {
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_MERCHANT_PRODUCT_SKU;
}

return $result;