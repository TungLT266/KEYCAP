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
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
$output = getContent("https://tiki.vn/ipad-wifi-32gb-new-2017-hang-chinh-hang-p694884.html");

$error = 0;
$result = [

    'title' => '',
    'price' => 0,
    'product' => '',
    'content' => '',
    'gioithieu' => '',
    'img' => '',
];

if (preg_match('/<div class="item-box">\s*<h1 class="item-name" itemprop="name" id="product-name">\s*(.+?)\s*<\/h1>/', $output, $matches)) {
    $result['title'] = $matches[1];
}else{
    $error = $error|1;
}

if (preg_match('/<span id="span-price">(.+?).{2}₫<\/span>/', $output, $matches)) {
    $result['price'] = intval(str_replace(".", "", $matches[1]));
}else{
    $error = $error|2;
}

if (preg_match('/<i class="tikicon icon-store"><\/i>\s*<div class="text">\s*<span>(.+?)<\/span>/', $output, $matches)) {
    $result['product'] = $matches[1];
}else{
    $error = $error|4;
}

if (preg_match('/<div class="top-feature-item bullet-wrap">\s*((?:.*|\s*)*?)\s*<\/div>/', $output, $matches)) {
    $result['content'] = $matches[1];
}else{
    $error = $error|8;
}

if (preg_match('/<div id="gioi-thieu" class="content js-content" itemprop="description">\s*((?:.+|\s*)*?)\s*<p class="show-more">.*?\s*<\/div>/', $output, $matches)) {
    $result['gioithieu'] = $matches[1];
}else{
    $error = $error|16;
}

if (preg_match('/<script type="text\/javascript">\s*var masterProductId = \d+;\s*var images = (.*?);/', $output, $matches)) {
    $img = json_decode($matches[1]);
    for($i=0;$i<sizeof($img);$i++){
        $a[$i] = $img[$i]->large_url;
    }
    $result['img'] = implode(",", $a);
}else{
    $error = $error|32;
}

if($error==0){
    var_dump($result);
}else{
    if(($error&1)==1){
        echo "Title"."<br>";
    }
    if (($error&2)==2){
        echo "Price"."<br>";
    }
    if (($error&4)==4){
        echo "Product"."<br>";
    }
    if (($error&8)==8){
        echo "Content"."<br>";
    }
    if (($error&16)==16){
        echo "Gioithieu"."<br>";
    }
    if (($error&32)==32){
        echo "Img"."<br>";
    }
}
