<?php

switch ($type) {
    case 'des_short':
        if(preg_match('~<div class="detail-product-desc-content">(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if(preg_match('~data-product-id="(\d+)"~', $content, $matches)){
            $content = \CoreSSG\Helpers\GeneralHelper::getContent('https://phongvu.vn/newcatalog/product/getDescriptionContent/', "product_id=$matches[1]");
            $result['data'] = json_decode($content)->description_content;
        }
        break;
    case 'image':
        if(preg_match('~<div class="detail-main-img">.+?data-large-img-url="(.+?)"~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}