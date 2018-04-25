<?php

$keyword = urlencode($keyword);
$url = 'https://www.mainguyen.vn/search/suggest?keyword='.$keyword;

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match_all('~<div class="table-cell suggest-blk">.+?<\/div>~s', $content, $matches)) {
    $index = 0;
    foreach ($matches[0] as $content) {
        if (preg_match_all('~<li>.+?href="(.+?)".+?src="(.+?)".+?<h4.+?>(.+?)<\/h4>~s', $content, $matches1)) {
            for ($i = 0; $i < sizeof($matches1[0]); $i++) {
                $itemList[] = [
                    'url' => 'https://www.mainguyen.vn'.$matches1[1][$i],
                    'image' => $url . $matches1[2][$i],
                    'title' => $matches1[3][$i],
                    'price' => 0
                ];
                $index++;
                if ( $index == MAX_ITEM )
                    break;
            }
        }
        if ( $index == MAX_ITEM )
            break;
    }
}