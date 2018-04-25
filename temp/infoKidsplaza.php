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

$url = 'https://www.kidsplaza.vn/khan-giay-uot-wesser.html';
$content = getContent($url);

if(preg_match('~<p class="product-image">.+?data-large="(.+?)"~s', $content, $matches)){
    $list = 'https:'.$matches[1];
}
echo '<img src="'.$list.'">';

if(preg_match('~<div class="short-desc">(.+?)<\/div>~', $content, $matches)){
    $des_short = $matches[1];
}
var_dump($des_short);

if(preg_match('~<div class="desc">(.+?)<\/div>~', $content, $matches)){
    $des_full = $matches[1];
}
vdd($des_full);