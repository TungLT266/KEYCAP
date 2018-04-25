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

$url = 'https://www.tuticare.com/kem-duong-da-dexeryl-250g-chua-ne-cham-248-item15729.html';
$content = getContent($url);

if(preg_match('~<div id="img-large".+?src="(.+?)"~s', $content, $matches)){
    $list = 'https://www.tuticare.com'.$matches[1];
}
echo '<img src="'.$list.'">';

if(preg_match('~<div id="tab1".+?>(.+?)<\/div>~s', $content, $matches)){
    $des = $matches[1];
}
vdd($des);