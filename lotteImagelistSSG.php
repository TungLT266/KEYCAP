<?php

//$output   = \CoreSSG\Helpers\GeneralHelper::getContent($url, '', false, true);
//$productId = null;
//if ( preg_match('~Location:.+?/product/(\d+)/~', $output, $matches) ) {
//    $productId  = $matches[1];
//} else if ( preg_match('~product/(\d+)/.+?html~', $url, $matches) ) {
//    $productId  = $matches[1];
//} else if ( preg_match('~product/(\d+)/.+?$~', $url, $matches) ) {
//    $productId  = $matches[1];
//}
//if ( (int)$productId > 0 ) {
//    $jsonContent = \CoreSSG\Helpers\GeneralHelper::getContent("https://www.lotte.vn/rest/V1/lotte_product/details?id={$productId}&isDetailPage=1");
//    $jsonContent = json_decode($jsonContent, true);
//
//    if ( isset($jsonContent['extension_attributes']['final_price']) ) {
//        switch ( $type ) {
//            case 'image':
//                $result['data'] = "https://www.lotte.vn/media/catalog/product/{$jsonContent['extension_attributes']['image']}";
//                break;
//            case 'des_full':
//                $result['data'] = isset($jsonContent['extension_attributes']['description']) ? $jsonContent['extension_attributes']['description'] : '';
//                break;
//            case 'image_list':
//                $list           = $jsonContent['media_gallery_entries'];
//                $result['data'] = [];
//                foreach ( $list as $img ) {
//                    $result['data'][] = "https://www.lotte.vn/media/catalog/product/{$img['file']}";
//                }
//                break;
//        }
//    }
//}

$output = \CoreSSG\Helpers\GeneralHelper::getContent($url, '', false, true);
$productId = null;
if (preg_match('~Location:.+?/product/(\d+)/~', $output, $matches)) {
    $productId = $matches[1];
} else if (preg_match('~product/(\d+)/.+?html~', $url, $matches)) {
    $productId = $matches[1];
} else if (preg_match('~product/(\d+)/.+?$~', $url, $matches)) {
    $productId = $matches[1];
}
if ((int)$productId > 0) {
    $jsonContent = \CoreSSG\Helpers\GeneralHelper::getContent("https://www.lotte.vn/rest/V1/lotte_product/details?id={$productId}&isDetailPage=1");
    $jsonContent = json_decode($jsonContent, true);

    if (isset($jsonContent['extension_attributes']['final_price'])) {
        switch ($type) {
            case 'image':
                $result['data'] = "https://www.lotte.vn/media/catalog/product/{$jsonContent['extension_attributes']['image']}";
                break;
            case 'des_full':
                $result['data'] = isset($jsonContent['extension_attributes']['description']) ? $jsonContent['extension_attributes']['description'] : '';
                break;
            case 'image_list':
                $list = [];
                if (preg_match('~https:\/\/www\.lotte\.vn\/product\/(\d+)\/~', $url, $idMatch)) {
                    $content = \CoreSSG\Helpers\GeneralHelper::getContent("https://www.lotte.vn/rest/V1/lotte_product/details?id=$idMatch[1]&isDetailPage=1");
                    $content = json_decode($content)->extension_attributes->base_url_media;

                    foreach ($content as $item) {
                        $list[] = $item->large_image_url;
                    }
                }
                $result['data'] = $list;
                break;
        }
    }
}