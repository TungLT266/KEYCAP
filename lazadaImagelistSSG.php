<?php

//switch ( $type ) {
//    case 'image':
//        if ( preg_match('~<meta name="og:image" content="(.+?)"~', $content, $matches) ) {
//            $result['data'] = preg_replace('~(.+?)-catalog.jpg~', '$1-product.jpg', $matches[1]);
//            $result['data'] = str_replace('http:', 'https:', $result['data']);
//            if ( stripos($result['data'], 'http') === false ) {
//                $result['data'] = 'https:' . $result['data'];
//            }
//            //$result['data'] = preg_replace('/(.*\/p\/)(.*)(-\d*-[\w\d]*?-product.jpg)/', '$1image$3', $result['data']);
//        }
//        break;
//    case 'des_short':
//        if ( preg_match('~<div class="html-content pdp-product-highlights">(.+?)</div><div class="html-content detail-content">~s', $content, $matches) ) {
//            $result['data'] = $matches[1];
//        }
//        break;
//    case 'des_full':
//        if ( preg_match('~<div class="html-content detail-content">(.+?)<div class="box-content">~s', $content, $matches) ) {
//            $result['data'] = trim($matches[0]);
//        }
//        break;
//    case 'image_list':
//        $list    = [];
//        $pattern = '/<div data-big="(.+?)" class="productImage/';
//        if ( preg_match_all($pattern, $content, $matches) ) {
//            $list = array_unique($matches[1]);
//        }
//        if ( preg_match('/product-description__block(.+?)(?=product-description__block)/s', $content, $matches) ) {
//            $content = $matches[1];
//            if ( preg_match_all('/<img[^>]+data-original=\n?"(.+?)"/', $content, $matches) ) {
//                $list = array_merge($list, array_map('trim', $matches[1]));
//            }
//        }
//        $result['data'] = $list;
//        break;
//    case 'cat_promo':
//        $items     = [];
//        $dom       = \CoreSSG\Helpers\GeneralHelper::loadHTML($content);
//        $xpath     = new \DOMXPath($dom);
//        $promoList = $xpath->query('//*[@class="c-product-item__price-old"]/ancestor::a[contains(@class,"c-product-item")]');
//        if ( $promoList->length === 0 ) {
//            $promoList = $xpath->query('//*[@class="c-product-card__old-price"]/ancestor::div[contains(@class,"c-product-list__item")]');
//        }
//        foreach ( $promoList as $promo ) {
//            $item  = [
//                'url'          => $url,
//                'img'          => '',
//                'name'         => '',
//                'price_before' => 0,
//                'price_after'  => 0
//            ];
//            $elStr = $dom->saveHTML($promo);
//            if ( $promo->tagName === 'a' ) {
//                $item['url'] = $promo->getAttribute('href');
//            } else {
//                if ( preg_match('/href=(?:"|\')([^"\']+)/', $elStr, $matches) ) {
//                    $item['url'] = trim($matches[1]);
//                }
//            }
//            if ( $item['url'][0] === '/' ) {
//                $item['url'] = 'http://www.lazada.vn' . $item['url'];
//            }
//            if ( $el = $xpath->query('.//*[@class="c-product-item__price-old" or @class="c-product-card__old-price"]', $promo)->item(0) ) {
//                $item['price_before'] = preg_replace('/[^\d]/', '', $el->nodeValue);
//            }
//            if ( $el = $xpath->query('.//*[@class="c-product-item__price" or @class="c-product-card__price-final"]', $promo)->item(0) ) {
//                $item['price_after'] = preg_replace('/[^\d]/', '', $el->nodeValue);
//            }
//            if ( $el = $xpath->query('.//*[@class="c-product-item__title" or @class="c-product-card__name"]', $promo)->item(0) ) {
//                $item['name'] = trim($el->nodeValue);
//            }
//            if ( preg_match('/((?:https?|\/)[^\'">]+(?:jpg|jpe?g|png)\b)/', $elStr, $matches) ) {
//                $item['img'] = trim($matches[1]);
//            }
//            $items[] = $item;
//        }
//        $result['data'] = $items;
//        break;
//}

switch ($type) {
    case 'image':
        if (preg_match('~<meta name="og:image" content="(.+?)"~', $content, $matches)) {
            $result['data'] = preg_replace('~(.+?)-catalog.jpg~', '$1-product.jpg', $matches[1]);
            $result['data'] = str_replace('http:', 'https:', $result['data']);
            if (stripos($result['data'], 'http') === false) {
                $result['data'] = 'https:' . $result['data'];
            }
            //$result['data'] = preg_replace('/(.*\/p\/)(.*)(-\d*-[\w\d]*?-product.jpg)/', '$1image$3', $result['data']);
        }
        break;
    case 'des_short':
        if (preg_match('~<div class="html-content pdp-product-highlights">(.+?)</div><div class="html-content detail-content">~s', $content, $matches)) {
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if (preg_match('~<div class="html-content detail-content">(.+?)<div class="box-content">~s', $content, $matches)) {
            $result['data'] = trim($matches[0]);
        }
        break;
    case 'image_list':
        $list = [];
        if (preg_match_all('~<img class="pdp-mod-common-image item-gallery__thumbnail-image" src="(.+?)"~', $content, $matches)) {
            foreach ($matches[1] as $item){
                $list[] = 'https:'.preg_replace('~-catalog\.jpg.+\.jpg$~', '.jpg', $item);
            }
        }
        $result['data'] = $list;
        break;
    case 'cat_promo':
        $items = [];
        $dom = \CoreSSG\Helpers\GeneralHelper::loadHTML($content);
        $xpath = new \DOMXPath($dom);
        $promoList = $xpath->query('//*[@class="c-product-item__price-old"]/ancestor::a[contains(@class,"c-product-item")]');
        if ($promoList->length === 0) {
            $promoList = $xpath->query('//*[@class="c-product-card__old-price"]/ancestor::div[contains(@class,"c-product-list__item")]');
        }
        foreach ($promoList as $promo) {
            $item = [
                'url' => $url,
                'img' => '',
                'name' => '',
                'price_before' => 0,
                'price_after' => 0
            ];
            $elStr = $dom->saveHTML($promo);
            if ($promo->tagName === 'a') {
                $item['url'] = $promo->getAttribute('href');
            } else {
                if (preg_match('/href=(?:"|\')([^"\']+)/', $elStr, $matches)) {
                    $item['url'] = trim($matches[1]);
                }
            }
            if ($item['url'][0] === '/') {
                $item['url'] = 'http://www.lazada.vn' . $item['url'];
            }
            if ($el = $xpath->query('.//*[@class="c-product-item__price-old" or @class="c-product-card__old-price"]', $promo)->item(0)) {
                $item['price_before'] = preg_replace('/[^\d]/', '', $el->nodeValue);
            }
            if ($el = $xpath->query('.//*[@class="c-product-item__price" or @class="c-product-card__price-final"]', $promo)->item(0)) {
                $item['price_after'] = preg_replace('/[^\d]/', '', $el->nodeValue);
            }
            if ($el = $xpath->query('.//*[@class="c-product-item__title" or @class="c-product-card__name"]', $promo)->item(0)) {
                $item['name'] = trim($el->nodeValue);
            }
            if (preg_match('/((?:https?|\/)[^\'">]+(?:jpg|jpe?g|png)\b)/', $elStr, $matches)) {
                $item['img'] = trim($matches[1]);
            }
            $items[] = $item;
        }
        $result['data'] = $items;
        break;
}