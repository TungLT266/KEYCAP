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
        $show = $show.'<tr><td><a target="_blank" href="'.$item['url'].'"><img style="max-width:128px;max-height:128px;" src="'.$item['image'].'" alt="'.$item['name'].'"></a></td><td><a target="_blank" href="'.$item['url'].'">'.$item['name'].'</a></td></tr>';
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
    $url = 'https://www.vuivui.com';

    $itemList = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent($url . '/aj/Shared/SuggestSearch?keyword=' . $key);
//    vdd($content);

    if (preg_match_all('~<img src=(.+?)>.+?<a href=(.+?)>(.+?)<\/a>.+?<div class=pricenew>([0-9\.]+)₫~', $content, $matches)) {
        foreach ($matches[1] as $index => $value) {
            $itemList[] = [
                'url' => 'https://www.vuivui.com' . $matches[2][$index],
                'image' => $matches[1][$index],
                'name' => $matches[3][$index],
                'price' => (int)str_replace('.', '', $matches[4][$index])
            ];
        }
//        vdd($itemList);
        showResult($itemList);
    }
} elseif (isset($_GET['url'])){
    $image = $_GET['url'];
    $content = getContent($image);
    if (preg_match('~var GL_CATEGORYID=(\d+).*?var GL_PRODUCTID=(\d+)~', $content, $matches)) {
        $content = getContent("https://www.vuivui.com/aj/Product/PopupGallery?categoryId=$matches[1]&productId=$matches[2]");
        if (preg_match_all('~src=(.+?) width~', $content, $matches1)) {
            showImages($matches1[1]);
        }
    }

}