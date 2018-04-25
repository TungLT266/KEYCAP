<?php

$keyword = urlencode($keyword);
$url = 'https://www.lazada.vn/catalog/?q='.$keyword;

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match('~(?<=<script>window\.pageData=).+?(?=<\/script>)~', $content, $matches)) {
    $items = json_decode($matches[0])->mods->listItems;
    foreach ($items as $index => $item) {
        $itemList[] = [
            'url' => 'https:'.$item->productUrl,
            'image' => $item->image,
            'title' => $item->name,
            'price' => (int)$item->price
        ];
        if ( $index == MAX_ITEM )
            break;
    }
}