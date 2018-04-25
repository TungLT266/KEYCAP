<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var){
    var_dump($var);
    die();
}

function showResult($arr){
    $show = "<table style='width:100%'; border='1px solid black'>";
    foreach ($arr as $item){
        $show = $show.'<tr><td><a target="_blank" href="'.$item['url'].'"><img style="max-width:128px;max-height:128px;" src="'.$item['image'].'" alt="'.$item['title'].'"></a></td><td><a target="_blank" href="'.$item['url'].'">'.$item['title'].'</a></td></tr>';
    }
    $show = $show.'</table>';
    echo $show;
}

function showImages($arr){
    $show = '';
    foreach ($arr as $item){
        $show = $show.'<a target="_blank" href="'.$item.'"><img style="max-width:200px;max-height:200px;" src="'.$item.'"></a>';
    }
    echo $show;
}

if (isset($_GET['keyword'])) {
    $key = $_GET['keyword'];

    $itemList = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent('https://www.adayroi.com/tim-kiem?text='.$key);

    if (preg_match_all('~<div class="product-item__container">.+?href="([^"]+)">.+?data-original="([^"]+)".+?title="([^"]+)"\/>.+?class="product-item__info-price-sale">\s*([0-9\.]+)đ~s', $content,$matches)) {
        foreach ($matches[1] as $index => $item){
            $itemList[] = [
                'url' => 'https://www.adayroi.com'.$item,
                'image' => $matches[2][$index],
                'title' => $matches[3][$index],
                'price' => (int)str_replace('.', '', $matches[4][$index])
            ];
        }
        vdd($itemList);
    }

    showResult($itemList);
} elseif (isset($_GET['url'])){
    $image = $_GET['url'];

    $content = getContent($image);
    if (preg_match('~<script type="text\/javascript">\s*var productJsonMedias =\s*(.+?);\s*var pdpTemplateType~', $content,$matches)) {
        $content = json_decode($matches[1]);

        $image = array();
        foreach ($content as $item){
            $image[] = $item->zoomUrl;
        }
    }


    showImages($image);
}

