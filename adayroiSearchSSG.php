<?php

$keyword = urlencode($keyword);
$url = 'https://www.adayroi.com/tim-kiem?text='.$keyword;

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match_all('~<div class="product-item__container">.+?href="([^"]+)">.+?data-original="([^"]+)".+?title="([^"]+)"\/>.+?class="product-item__info-price-sale">\s*([0-9\.]+)đ~s', $content,$matches)) {
    foreach ($matches[1] as $index => $item){
        $itemList[] = [
            'url' => 'https://www.adayroi.com'.$item,
            'image' => $matches[2][$index],
            'title' => $matches[3][$index],
            'price' => (int)str_replace('.', '', $matches[4][$index])
        ];
    }
}

