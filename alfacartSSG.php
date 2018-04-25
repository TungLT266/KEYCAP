<?php

if ( preg_match('/<title>Page Not Found<\/title>/', $output) ) {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_NOT_FOUND;
    return $result;
}

if ( preg_match('/id="stockProduct" value="(\d+)"/', $output, $matches) ) {
    if ( (int)$matches[1] > 0 ) {
        $result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_IN;
    } else {
        $result['stock_status'] = PRODUCT_MERCHANT_STOCK_STATUS_OUT;
    }
}

if ( preg_match('/"price":"(\d+)",/', $output, $matches) ) {
    $result['price'] = (int)$matches[1];
} else {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_PATTERN;
    return $result;
}
if ( $result['price'] === 0 ) {
    $result['error_status'] = PRODUCT_MERCHANT_ERROR_PRICE;
    return $result;
}

if ( preg_match('/id="productName">(.+?)</s', $output, $matches) ) {
    $result['name'] = trim($matches[1]);
} else {
    $result['error_status'] |=  PRODUCT_MERCHANT_ERROR_NAME;
}

if ( preg_match('/name="imgProduct"[^>]+src="([^"]+)"/', $output, $matches) ) {
    $result['img'] = trim($matches[1]);
} else {
    $result['error_status'] |= PRODUCT_MERCHANT_ERROR_IMAGE;
}

$result['merchant_brand'] = $result['merchant_category'] = '';
if ( preg_match('/var productDetailInfo = ({[^;]+});/', $output, $matches) ) {
    $productDetail = json_decode($matches[1], true);
    if ( $productDetail && isset($productDetail['parent']) ) {
        $result['merchant_brand'] = $productDetail['parent']['brand'];
        $categories = explode('/', $productDetail['parent']['category']);
        $result['merchant_category'] = end($categories);
    }
}

if ( preg_match('/name="productid" value="(\d+)/', $output, $matches) ) {
    $result['merchant_product_id'] = (int)$matches[1];
}
