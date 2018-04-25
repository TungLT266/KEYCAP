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
    echo "<p>";
    var_dump($var);
    echo "</p>";
}

$url = "https://www.dienmayxanh.com/may-lanh/sharp-ah-a25uew";
$output = getContent($url);

if (preg_match('/<div class="box_content">\s*<aside class="left_content">.*?<div class="box-article ">\s*<article id="tinh-nang" class="area_article">(.+?)<div class="likeshare">/s', $output, $matches)) {
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $matches[1]);
}

$imgs = $doc->getElementsByTagName('img');
foreach ($imgs as $img){
    $img->parentNode->removeChild($img);
}

//while ($list->length > 0) {
//    $p = $list->item(0);
//    $p->parentNode->removeChild($p);
//}

$content = $doc->saveHTML();
vdd($content);