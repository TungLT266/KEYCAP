<?php

//switch ( $type ) {
//    case 'des_short':
//        if ( preg_match('/<ul class="specs">.+?<\/ul>/s', $content, $matches) ) {
//            $result['data'] = trim(preg_replace('/<([a-zA-Z]+)([^>]*)>/', '<$1>', $matches[0]));
//        }
//        break;
//    case 'des_full':
/*        if ( preg_match('/<article id="tinh-nang".*?>(.+?)<\/article>/s', $content, $matches) ) {*/
//            $data = preg_replace('/<div class="likeshare".+<\/form>/s', '', $matches[1]);
//            $result['data'] = $data;
//        }
//        break;
//    case 'image':
//        if ( preg_match('/<meta property="og:image" content="([^">]+)"/', $content, $matches) ) {
//            $result['data'] = trim($matches[1]);
//        }
//        break;
//    case 'image_list':
//        $productId = null;
//        $categoryId = null;
//        $list = [];
//        if ( preg_match('/document\.productId = (\d+);/', $content, $matches) ) {
//            $productId = $matches[1];
//        }
//        if ( preg_match('/document\.categoryId = (\d+);/', $content, $matches) ) {
//            $categoryId = $matches[1];
//        }
//        if ( $productId && $categoryId ) {
//            $url = sprintf('https://www.dienmayxanh.com/aj/ProductV2/Gallery?categoryId=%d&productId=%d&_=%d', $categoryId, $productId, time());
//            $content = \CoreSSG\Helpers\GeneralHelper::getContent($url);
//            if ( $content ) {
//                if ( preg_match_all('/<img width="120" src="(.+?)"/', $content, $matches) ) {
//                    $list = preg_replace('/-480x480/', '', $matches[1]);
//                }
//            }
//        }
//        if ( preg_match('/<div class="box-article(.+?)(?=class="productrelate")/s', $content, $matches)) {
//            $content = $matches[1];
//            if ( preg_match_all('/<img[^>]+data-src=(.+?) class=lazy>/', $content, $matches) ) {
//                $list = array_merge($list, $matches[1]);
//            }
//        }
//        $result['data'] = $list;
//        break;
//}

switch ($type) {
    case 'des_short':
        if (preg_match('/<ul class="specs">.+?<\/ul>/s', $content, $matches)) {
            $result['data'] = trim(preg_replace('/<([a-zA-Z]+)([^>]*)>/', '<$1>', $matches[0]));
        }
        break;
    case 'des_full':
        if (preg_match('/<article id="tinh-nang".*?>(.+?)<\/article>/s', $content, $matches)) {
            $data = preg_replace('/<div class="likeshare".+<\/form>/s', '', $matches[1]);
            $result['data'] = $data;
        }
        break;
    case 'image':
        if (preg_match('/<meta property="og:image" content="([^">]+)"/', $content, $matches)) {
            $result['data'] = trim($matches[1]);
        }
        break;
    case 'image_list':
        $list = [];
        if (preg_match('~var GL_CATEGORYID =(\d+);.*?var GL_PRODUCTID=(\d+);~s', $content,$matches)) {
            $content = \CoreSSG\Helpers\GeneralHelper::getContent("https://www.dienmayxanh.com/aj/ProductV2/GetGalleryData?categoryId=$matches[1]&productId=$matches[2]");
            $content = json_decode($content);
            foreach ($content as $itemList){
                foreach ($itemList as $item){
                    $list[] = $item->pictureField;
                }
            }

            $content = \CoreSSG\Helpers\GeneralHelper::getContent("https://www.dienmayxanh.com/aj/ProductV2/Gallery?categoryId=$matches[1]&productId=$matches[2]&isUnbox=true");
            if(preg_match('~<img data-lazy="([^"]+)"~', $content,$imageBoxMatch)){
                $list[] = $imageBoxMatch[1];
            }
        }
        $result['data'] = $list;
        break;
}