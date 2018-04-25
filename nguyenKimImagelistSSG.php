<?php

//switch ( $type ) {
//    case 'des_short':
//        if ( preg_match('~<div class="productInfo_description">(.+?)</div>~s', $content, $matches) ) {
//            $result['data'] = trim($matches[1]);
//        }
//        break;
//    case 'des_full':
//        if ( preg_match('~<div class="productFeature_content">(.+?)<div class="remove-this-clss">~s', $content, $matches) ) {
//            //https://www.nguyenkim.com/binh-luong-tinh-elmich-inox-304-k5-500ml.html
//            $result['data'] = trim($matches[1]);
//        } else if ( preg_match('~<section id="nks-product-description-general-v2" class="nks-product-description-general-v2 nks-column-[\d]+ nks-column">(.+?)</section>~s', $content, $matches) ) {
//            //https://www.nguyenkim.com/noi-fujihoro-irv-16w.html?pid=50660
//            $result['data'] = trim($matches[1]);
//        }
//        break;
//    case 'image':
//        if ( preg_match('/<meta itemprop="image" content="([^"]+)">/', $content, $matches) ) {
//            $result['data'] = trim($matches[1]);
//        }
//        break;
//    case 'image_list':
//        $list = [];
//        if ( preg_match_all('/<a[^>]+nkhref="(.+?)"/', $content, $matches) ) {
//            $list = $matches[1];
//        }
//        $result['data'] = $list;
//        break;
//}

switch ($type) {
    case 'des_short':
        if (preg_match('~<div class="productInfo_description">(.+?)</div>~s', $content, $matches)) {
            $result['data'] = trim($matches[1]);
        }
        break;
    case 'des_full':
        if (preg_match('~<div class="productFeature_content">(.+?)<div class="remove-this-clss">~s', $content, $matches)) {
            //https://www.nguyenkim.com/binh-luong-tinh-elmich-inox-304-k5-500ml.html
            $result['data'] = trim($matches[1]);
        } else if (preg_match('~<section id="nks-product-description-general-v2" class="nks-product-description-general-v2 nks-column-[\d]+ nks-column">(.+?)</section>~s', $content, $matches)) {
            //https://www.nguyenkim.com/noi-fujihoro-irv-16w.html?pid=50660
            $result['data'] = trim($matches[1]);
        }
        break;
    case 'image':
        if (preg_match('/<meta itemprop="image" content="([^"]+)">/', $content, $matches)) {
            $result['data'] = trim($matches[1]);
        }
        break;
    case 'image_list':
        if(preg_match_all('~data-full="(.+?)"~', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
}