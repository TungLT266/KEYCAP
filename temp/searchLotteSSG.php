<?php

$url = 'https://els.lotte.vn/api/v1/products/query';
$params = json_encode(array( "params"=> "query=$keyword"));

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url, $params);

$listItems = json_decode($content)->hits;

foreach ($listItems as $index => $item){
    if(preg_match('~https:\/\/www\.lotte\.vn\/catalog\/product\/view\/id\/(\d+)\/s\/(.+?)\/~', $item->url, $matches)){
        $itemList[] = [
            'url' => "https://www.lotte.vn/product/$matches[1]/$matches[2]",
            'image' => 'http:'.$item->image_url,
            'title' => $item->name,
            'price' => $item->price_default
        ];
    } else{
        $itemList[] = [
            'url' => $item->url,
            'image' => 'http:'.$item->image_url,
            'title' => $item->name,
            'price' => $item->price_default
        ];
    }
    if ( $index == MAX_ITEM )
        break;
}

