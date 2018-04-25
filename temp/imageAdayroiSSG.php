<?php

//switch ($type) {
//    case 'image':
//        if (preg_match('/"carouselUrl":"([^"]+)",/', $content, $matches)) {
//            $result['data'] = $matches[1];
//        }
//        break;
//    case 'des_short':
//        if (preg_match('/<div class="short-des__content".+?<\/ul>\s+<\/div>/s', $content, $matches)) {
//            $result['data'] = $matches[0];
//        }
//        break;
//    case 'des_full':
//        $desc = '';
//        $specs = '';
//        if (preg_match('/(<div class="product-detail__description">.+?)<\/div>/s', $content, $matches)) {
//            $desc = trim($matches[1]);
//        }
//        if (preg_match_all('/<div class="product-specs__table">(.+?<\/table)/s', $content, $matches)) {
//            $specs = trim(end($matches[1]));
//        }
//        $result['data'] = $desc . $specs;
//        break;
//    case 'image_list':
//        $list = [];
//        if (preg_match_all('/"carouselUrl":"([^"]+)"/', $content, $matches)) {
//            $list = $matches[1];
//        }
//        $result['data'] = $list;
//        break;
//    case 'cat_promo':
//        $items = [];
//        if (preg_match_all('/<div class="col-lg-3 col-xs-4">.+?<!-- item -->/s', $content, $arr) > 0) {
//            $extractPattern = '/data-merchant-id="(.+?)".+?href="(.+?)" title="(.+?)">.+?data-src="(.+?)".+?<span class="amount-1">(.+?)<\/span>.+?<span class="amount-2">(.+?)<\/span>/s';
//            foreach ($arr[0] as $key => $content) {
//                if (preg_match($extractPattern, $content, $matches)) {
//                    $item = [];
//                    $merchantId = intval($matches[1]);
//                    $discountPrice = $originalPrice = intval(str_replace('.', '', $matches[6]));
//                    /*-------------------------------------------------------------*/
//                    $specialPromotion = false;
//                    $productDiscountPrice = 0;
//                    $merchant5percent = [2282, 12824, 14497, 14495, 14494, 14493, 14487];
//                    $merchant10percent = [7766, 152750];
//
//                    $discountNormal = 0.02;
//                    $discount5Percent = 0.05;
//                    $discount10Percent = 0.10;
//
//                    if ($specialPromotion) {
//                        $now_date = strtotime(date('Y-m-d'));
//                        $time_start = '2016-11-08';
//                        $time_end = '2016-11-11';
//                        if ($now_date >= strtotime($time_start) && $now_date <= strtotime($time_end)) {
//                            $discountNormal = 0.04;
//                            $discount5Percent = 0.07;
//                            $discount10Percent = 0.12;
//                        }
//                    }
//
//                    if (in_array($merchant->id, $merchant5percent)) {
//                        $productDiscountPrice = $originalPrice * $discount5Percent;
//
//                    } elseif (in_array($merchant->id, $merchant10percent)) {
//                        $productDiscountPrice = $originalPrice * $discount10Percent;
//                    }
//
//                    if ($productDiscountPrice > 0) {
//                        $discountPrice = $originalPrice - ceil($productDiscountPrice / 1000) * 1000;
//                    } else {
//                        $discountPrice = round(($originalPrice - $originalPrice * $discountNormal) / 1000) * 1000;
//                    }
//                    /*-------------------------------------------------------------*/
//
//                    $item['url'] = 'https://www.adayroi.com' . html_entity_decode(trim($matches[2]));
//                    $item['url'] = preg_replace("/\#.*/", '', $item['url']);
//                    $item['name'] = html_entity_decode($matches[3], ENT_COMPAT, 'UTF-8');
//                    $item['img'] = $matches[4];
//                    $item['price_before'] = $originalPrice;
//                    $item['price_after'] = $discountPrice;
//
//                    $items[] = $item;
//                }
//            }
//        }
//        $result['data'] = $items;
//        break;
//}

switch ($type) {
    case 'image':
        if (preg_match('/"carouselUrl":"([^"]+)",/', $content, $matches)) {
            $result['data'] = $matches[1];
        }
        break;
    case 'des_short':
        if (preg_match('/<div class="short-des__content".+?<\/ul>\s+<\/div>/s', $content, $matches)) {
            $result['data'] = $matches[0];
        }
        break;
    case 'des_full':
        $desc = '';
        $specs = '';
        if (preg_match('/(<div class="product-detail__description">.+?)<\/div>/s', $content, $matches)) {
            $desc = trim($matches[1]);
        }
        if (preg_match_all('/<div class="product-specs__table">(.+?<\/table)/s', $content, $matches)) {
            $specs = trim(end($matches[1]));
        }
        $result['data'] = $desc . $specs;
        break;
    case 'image_list':
        $list = [];
        if (preg_match('~<script type="text\/javascript">\s*var productJsonMedias =\s*(.+?);\s*var pdpTemplateType~', $content,$matches)) {
            $imageList = json_decode($matches[1]);
            foreach ($imageList as $item){
                $list[] = $item->zoomUrl;
            }
        }
        $result['data'] = $list;
        break;
    case 'cat_promo':
        $items = [];
        if (preg_match_all('/<div class="col-lg-3 col-xs-4">.+?<!-- item -->/s', $content, $arr) > 0) {
            $extractPattern = '/data-merchant-id="(.+?)".+?href="(.+?)" title="(.+?)">.+?data-src="(.+?)".+?<span class="amount-1">(.+?)<\/span>.+?<span class="amount-2">(.+?)<\/span>/s';
            foreach ($arr[0] as $key => $content) {
                if (preg_match($extractPattern, $content, $matches)) {
                    $item = [];
                    $merchantId = intval($matches[1]);
                    $discountPrice = $originalPrice = intval(str_replace('.', '', $matches[6]));
                    /*-------------------------------------------------------------*/
                    $specialPromotion = false;
                    $productDiscountPrice = 0;
                    $merchant5percent = [2282, 12824, 14497, 14495, 14494, 14493, 14487];
                    $merchant10percent = [7766, 152750];

                    $discountNormal = 0.02;
                    $discount5Percent = 0.05;
                    $discount10Percent = 0.10;

                    if ($specialPromotion) {
                        $now_date = strtotime(date('Y-m-d'));
                        $time_start = '2016-11-08';
                        $time_end = '2016-11-11';
                        if ($now_date >= strtotime($time_start) && $now_date <= strtotime($time_end)) {
                            $discountNormal = 0.04;
                            $discount5Percent = 0.07;
                            $discount10Percent = 0.12;
                        }
                    }

                    if (in_array($merchant->id, $merchant5percent)) {
                        $productDiscountPrice = $originalPrice * $discount5Percent;

                    } elseif (in_array($merchant->id, $merchant10percent)) {
                        $productDiscountPrice = $originalPrice * $discount10Percent;
                    }

                    if ($productDiscountPrice > 0) {
                        $discountPrice = $originalPrice - ceil($productDiscountPrice / 1000) * 1000;
                    } else {
                        $discountPrice = round(($originalPrice - $originalPrice * $discountNormal) / 1000) * 1000;
                    }
                    /*-------------------------------------------------------------*/

                    $item['url'] = 'https://www.adayroi.com' . html_entity_decode(trim($matches[2]));
                    $item['url'] = preg_replace("/\#.*/", '', $item['url']);
                    $item['name'] = html_entity_decode($matches[3], ENT_COMPAT, 'UTF-8');
                    $item['img'] = $matches[4];
                    $item['price_before'] = $originalPrice;
                    $item['price_after'] = $discountPrice;

                    $items[] = $item;
                }
            }
        }
        $result['data'] = $items;
        break;
}