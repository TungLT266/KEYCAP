<?php

$keyword = urlencode($keyword);
$url = 'https://tiki.vn/search?q='.$keyword;

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match_all('~<div data-seller-product-id.+?data-title="([^"]+)"\s*data-price="(\d+)".+?href="([^"]+)".+?src="([^"]+)"~s', $content, $matches)) {
    foreach ($matches[1] as $index => $item) {
        $itemList[] = [
            'url' => $matches[3][$index],
            'image' => $matches[4][$index],
            'title' => $item,
            'price' => (int)$matches[2][$index]
        ];
        if ( $index == MAX_ITEM )
            break;
    }
}