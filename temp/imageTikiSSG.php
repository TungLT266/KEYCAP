<?php

//switch ( $type ) {
//    case 'image':
//        if ( preg_match('/class="product-magiczoom" itemprop="image" src="([^"]+)"/', $content, $matches) ) {
//            $result['data'] = preg_replace('/\s/', '%20', $matches[1]);
//        }
//        break;
//    case 'des_short':
//        if ( preg_match('/<div class="top-feature-item bullet-wrap">(.+?)<\/div>/s', $content, $matches) ) {
//            $result['data'] = trim($matches[1]);
//        }
//        break;
//    case 'des_full':
//        $pattern_data = '/<div id="gioi-thieu" class="content js-content" itemprop="description">(?:\s+<p>(?:<span[^>]*>)?<strong>[^<]+<\/strong>(?:<\/span>)?<\/p>)?(.*?)<\/div>\s+<p class="show-more">/s';
//        if ( preg_match($pattern_data, $content, $matches) > 0 ) {
//            $str            = $matches[1];
//            $str            = preg_replace('/style=".*?"/i', '', $str);
//            $result['data'] = $str;
//        } else if ( preg_match('/<h3 class="product-table-title">Thông Tin Chi Tiết<\/h3>(.+?)<div itemprop="weight"/s', $content, $matches) > 0 ) {
//            $str            = $matches[1];
//            $str            = preg_replace('/<td>SKU<\/td>.+?<\/tr>/s', '</tr>', $str);
//            $str            = preg_replace('/class=".*?"/i', '', $str);
//            $result['data'] .= $str;
//        }
//        break;
//    case 'image_list':
//        $list = [];
//        $pattern = '/<a class="swiper-slide"[^>]*data-zoom-image="(.+?)"|<img class="img-responsive swiper-lazy" data-src="(.+?)"/';
//        if ( preg_match_all($pattern, $content, $matches) ) {
//            $list = array_merge($matches[1], $matches[2]);
//            $list = array_values(array_filter($result, function($img){
//                return !empty($img);
//            }));
//        }
//        if ( preg_match('/class="product-content-detail"(.+?)(?=class="show-more")/s', $content, $matches) ) {
//            $content = $matches[1];
//            if ( preg_match_all('/<img[^>]+src="(.+?)" alt/', $content, $matches) ) {
//                $list = array_merge($list, $matches[1]);
//            }
//        }
//        $result['data'] = $list;
//        break;
//}

switch ($type) {
    case 'image':
        if (preg_match('/class="product-magiczoom" itemprop="image" src="([^"]+)"/', $content, $matches)) {
            $result['data'] = preg_replace('/\s/', '%20', $matches[1]);
        }
        break;
    case 'des_short':
        if (preg_match('/<div class="top-feature-item bullet-wrap">(.+?)<\/div>/s', $content, $matches)) {
            $result['data'] = trim($matches[1]);
        }
        break;
    case 'des_full':
        $pattern_data = '/<div id="gioi-thieu" class="content js-content" itemprop="description">(?:\s+<p>(?:<span[^>]*>)?<strong>[^<]+<\/strong>(?:<\/span>)?<\/p>)?(.*?)<\/div>\s+<p class="show-more">/s';
        if (preg_match($pattern_data, $content, $matches) > 0) {
            $str = $matches[1];
            $str = preg_replace('/style=".*?"/i', '', $str);
            $result['data'] = $str;
        } else if (preg_match('/<h3 class="product-table-title">Thông Tin Chi Tiết<\/h3>(.+?)<div itemprop="weight"/s', $content, $matches) > 0) {
            $str = $matches[1];
            $str = preg_replace('/<td>SKU<\/td>.+?<\/tr>/s', '</tr>', $str);
            $str = preg_replace('/class=".*?"/i', '', $str);
            $result['data'] .= $str;
        }
        break;
    case 'image_list':
        $list = [];
        if(preg_match('~var images =\s*(.+?);\s*var imageGalery~s', $content, $matches)){
            $imageList = json_decode($matches[1]);
            foreach ($imageList as $image){
                $list[] = $image->large_url;
            }
        }
        $result['data'] = $list;
        break;
}