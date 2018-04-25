<?php

//switch ( $type ) {
//    case 'image':
//        if ( preg_match('/<meta property=og:image content=([^>]+)>/', $content, $matches) ) {
//            $result['data'] = $matches[1];
//        }
//        break;
//    case 'des_short':
//        if ( preg_match('/<div class=short>.+?<\/div>/s', $content, $matches) ) {
//            $result['data'] = trim($matches[0]);
//        }
//        break;
//    case 'des_full':
//        if ( preg_match('/<div class=full>.+?<\/div>/s', $content, $matches) ) {
//            $result['data'] = trim($matches[0]);
//        }
//        break;
//    case 'image_list':
//        $productId = null;
//        $categoryId = null;
//        $list = [];
//        if ( preg_match('/<meta itemprop="image" content="(.+?)" \/>/', $content, $matches) ) {
//            if ( preg_match('/\/(\d+)\/(\d+)\//', $matches[1], $info) ) {
//                $categoryId = $info[1];
//                $productId = $info[2];
//            }
//        }
//        if ( $productId && $categoryId ) {
//            $url = sprintf('https://www.vuivui.com/aj/Product/PopupGallery?categoryId=%d&productId=%d', $categoryId, $productId);
//            $content = \CoreSSG\Helpers\GeneralHelper::getContent($url);
//            if ( $content ) {
//                $pattern = '/src=(.+?)\s/';
//                if ( preg_match_all($pattern, $content, $matches) ) {
//                    $list = $matches[1];
//                }
//            }
//        }
//        if ( preg_match('/<aside class="leftcontent">(.+?)(?=<\/aside>)/s', $content, $matches)) {
//            $content = $matches[1];
//            if ( preg_match_all('/<img[^>]+data-original=(.+?)\s/', $content, $matches) ) {
//                $list = array_merge($list, $matches[1]);
//            }
//        }
//        $result['data'] = $list;
//        break;
//}

switch ($type) {
    case 'image':
        if (preg_match('/<meta property=og:image content=([^>]+)>/', $content, $matches)) {
            $result['data'] = $matches[1];
        }
        break;
    case 'des_short':
        if (preg_match('/<div class=short>.+?<\/div>/s', $content, $matches)) {
            $result['data'] = trim($matches[0]);
        }
        break;
    case 'des_full':
        if (preg_match('/<div class=full>.+?<\/div>/s', $content, $matches)) {
            $result['data'] = trim($matches[0]);
        }
        break;
    case 'image_list':
        if (preg_match('~var GL_CATEGORYID=(\d+).*?var GL_PRODUCTID=(\d+)~', $content, $matches)) {
            $content2 = \CoreSSG\Helpers\GeneralHelper::getContent("https://www.vuivui.com/aj/Product/PopupGallery?categoryId=$matches[1]&productId=$matches[2]");
            if (preg_match_all('~src=(.+?) width~', $content2, $list)) {
                $result['data'] = $list[1];
            }
        }
        break;
}