<?php

//switch ( $type ) {
//    case 'des_short':
//        if ( preg_match('/<ul class="specs">.+?<\/ul>/s', $content, $matches) ) {
//            $result['data'] = trim(preg_replace('/<([a-zA-Z]+)([^>]*)>/', '<$1>', $matches[0]));
//        }
//        break;
//    case 'des_full':
//        if ( preg_match('/<article class="area_article">.+?<\/article>/s', $content, $matches) ) {
//            $data = preg_replace('/<div class="boxRtAtc">.+?(?=<\/article>)/s', '', $matches[0]);
//            $data = preg_replace('/<([a-zA-Z]+)([^>]*)>/', '<$1>', $data);
//            $result['data'] = $data;
//        }
//        break;
//    case 'image_list':
//        $productId = null;
//        $colorList = [];
//        $list = [];
//        if ( preg_match_all('/gotoGallery\(1,(\d+)\)/', $content, $matches) ) {
//            $colorList = $matches[1];
//        }
//
//        if ( preg_match("/productID = '(\d+)';/", $content, $matches) ) {
//            $productId = (int)$matches[1];
//        }
//
//        if ( $productId && count($colorList) > 0 ) {
//            $requests = [];
//            foreach ($colorList as $colorId) {
//                $requests[] = [
//                    'url' => 'https://www.thegioididong.com/aj/ProductV4/GallerySlideFT',
//                    'form' => [
//                        'productID' => $productId,
//                        'imageType' => 1,
//                        'colorID' => $colorId
//                    ]
//                ];
//            }
//            $crawResults = \CoreSSG\Helpers\GeneralHelper::getContentMulti($requests, false, false, false, true);
//            foreach ($crawResults as $crawResult) {
//                if ( isset($crawResult['content']) ) {
//                    if ( preg_match_all('/data-img="(.+?)"/', $crawResult['content'], $matches) ) {
//                        foreach ($matches[1] as $img) {
//                            $list[] = "https:" . $img;
//                        }
//                    }
//                }
//            }
//        }
//        if ( preg_match('/class="boxArticle"(.+?)(?=class="boxRtAtc")/s', $content, $matches)) {
//            $content = $matches[1];
//            if ( preg_match_all('/class=\'lazy\' data-original="(.+?)"/', $content, $matches) ) {
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
        if (preg_match('/<article class="area_article">.+?<\/article>/s', $content, $matches)) {
            $data = preg_replace('/<div class="boxRtAtc">.+?(?=<\/article>)/s', '', $matches[0]);
            $data = preg_replace('/<([a-zA-Z]+)([^>]*)>/', '<$1>', $data);
            $result['data'] = $data;
        }
        break;
    case 'image_list':
        $list = [];

        if(preg_match('~var GL_PRODUCTID = (\d+)~', $content, $productMatch)&&preg_match_all('~onclick="gotoGallery\(([17]),(\d+)\)"~', $content, $typeColorMatch)){
            $temp = [];
            $temp['url'] = 'https://www.thegioididong.com/aj/ProductV4/GallerySlideFT/';
            foreach ($typeColorMatch[1] as $index => $imageType){
                $temp['params'] = [
                    'productID' => $productMatch[1],
                    'imageType' => $imageType,
                    'colorID' => $typeColorMatch[2][$index]
                ];
                $urlList[] = $temp;
            }
            $sourceList = \CoreSSG\Helpers\GeneralHelper::getContentMulti($urlList);
            foreach ($sourceList as $index => $source){
                if(isset($source['content'])){
                    if(preg_match_all('~data-img="(.+?)"~', $source['content'], $imageListMatch)){
                        if((int)$typeColorMatch[1][$index]==1){
                            foreach ($imageListMatch[1] as $image){
                                $list[] = 'https:'.$image;
                            }
                        } elseif ((int)$typeColorMatch[1][$index]==7){
                            foreach ($imageListMatch[1] as $image){
                                $list[] = $image;
                            }
                        }
                    }
                }
            }
        }
        $result['data'] = $list;
        break;
}