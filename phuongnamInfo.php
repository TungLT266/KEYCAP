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

function showImages($arr){
    $show = '';
    foreach ($arr as $item){
        $show = $show.'<a target="_blank" href="'.$item.'"><img style="max-width:200px;max-height:200px;" src="'.$item.'"></a>';
    }
    echo $show;
}

$url = 'http://nhasachphuongnam.com/chieu-menh-mong-p95349.html';
$content = getContent($url);

if(preg_match('~<div id="product-thumb-\d+" class="product-thumb">.+?href="([^"]+)"~s', $content, $matches)){
    $list = $matches[1];
}
echo '<img src="'.$list.'">';

if(preg_match('~<div class="wysiwyg" itemprop="description">(.+?)<\/div>~s', $content, $matches)){
    $des = $matches[1];
}
vdd($des);

//if(preg_match('~<div class="wysiwyg" itemprop="description">(.+?)<\/div>~s', $content, $matches)){
//    $des_full = $matches[1];
//}