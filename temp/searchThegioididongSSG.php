<?php

$keyword = urlencode($keyword);
$url = "https://www.thegioididong.com/aj/CommonV3/SuggestSearch?keyword=$keyword";

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match_all('~<li>.+?href="([^"]+)">.+?src="([^"]+)".+?<h3>(.+?)<\/h3>.+?class="price">.*?([0-9\.]+)₫~s', $content, $matches)) {
    foreach ($matches[1] as $index => $item) {
        $itemList[] = [
            'url' => 'https://www.thegioididong.com' . $item,
            'image' => $matches[2][$index],
            'title' => $matches[3][$index],
            'price' => (int)str_replace('.', '', $matches[4][$index])
        ];
        if ($index == MAX_ITEM)
            break;
    }
}