<?php

$keyword = urlencode($keyword);
$url = 'https://www.vuivui.com/aj/Shared/SuggestSearch?keyword='.$keyword;

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match_all('~<img src=(.+?)>.+?<a href=(.+?)>(.+?)<\/a>.+?<div class=pricenew>([0-9\.]+)₫~', $content, $matches)) {
    foreach ($matches[1] as $index => $value) {
        $itemList[] = [
            'url' => 'https://www.vuivui.com' . $matches[2][$index],
            'image' => $matches[1][$index],
            'title' => $matches[3][$index],
            'price' => (int)str_replace('.', '', $matches[4][$index])
        ];
    }
}