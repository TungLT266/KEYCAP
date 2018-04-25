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

$url = 'https://concung.com/sua-bot-cho-be/sua-enfamil-a-3-vani-360-do-brain-plus-voi-pdx-gos-1-2-tuoi-900g-26640.html';
$content = getContent($url);

if(preg_match('~<div class="main">.+?href="(.+?)"~s', $content, $matches)){
    $list = $matches[1];
}
echo '<img src="'.$list.'">';

if(preg_match('~<div id="short_description_content">(.+?)<\/div>~s', $content, $matches)){
    $des_short = $matches[1];
}
var_dump($des_short);

if(preg_match('~<div class="content-detail">(.+?)<span class="read_more hide"~s', $content, $matches)){
    $des_full = $matches[1];
}
vdd($des_full);