<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getContent2($url, $param)
{
    $ch = curl_init($url);

    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $param);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var){
    var_dump($var);
    die();
}

$url = 'https://phongvu.vn/linh-kien-may-tinh/psu-nguon-may-tinh/cooler-master/ngu-n-psu-cooler-master-elite-400w-non-modular-120mm-fan.html';
$content = getContent($url);

if(preg_match('~<div class="detail-main-img">.+?data-large-img-url="(.+?)"~s', $content, $matches)){
    $list = $matches[1];
}
echo '<img src="'.$list.'">';

if(preg_match('~<div class="detail-product-desc-content">(.+?)<\/div>~s', $content, $matches)){
    $des_short = $matches[1];
}
var_dump($des_short);

if(preg_match('~data-product-id="(\d+)"~', $content, $matches)){
    $content = getContent2('https://phongvu.vn/newcatalog/product/getDescriptionContent/', "product_id=$matches[1]");
    $des_full = json_decode($content)->description_content;
}
vdd($des_full);