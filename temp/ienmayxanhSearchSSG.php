<?php

$keyword = urlencode($keyword);
$url = "https://www.dienmayxanh.com/webapi/suggestsearch?keywords=$keyword&provinceId=3&categoryId=-1";

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);

if (preg_match_all('~<li>.+?<\/li>~s', $content, $matches)) {
    $index = 0;
    foreach ($matches[0] as $item) {
        if (preg_match('~href="(.+?)".+?data-img="(.+?)".+?title="(.+?)".+?class="price price-color">(.+?)₫~s', $item, $matches1)) {
            $itemList[] = [
                'url' => 'https://www.dienmayxanh.com'.$matches1[1],
                'image' => $matches1[2],
                'title' => $matches1[3],
                'price' => (int)str_replace('.', '', $matches1[4])
            ];
            $index++;
        }
        if ( $index == MAX_ITEM )
            break;
    }
}