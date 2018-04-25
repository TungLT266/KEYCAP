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
    die;
}

$doc = new DOMDocument('1.0','utf-8');

$url = "https://www.dienmayxanh.com/may-lanh/sharp-ah-a25uew";
$output = getContent($url);

$temp = '<a href="https://www.dienmayxanh.com/may-lanh/sharp-ah-a25uew" target="_blank" title="Máy lạnh Sharp 1 HP AH-A25UEW" type="Máy lạnh Sharp 1 HP AH-A25UEW">Máy lạnh Sharp 1 HP AH-A25UEW</a> có đường nét thiết kế mềm mại, thanh lịch, kết hợp với sắc trắng tinh khôi tạo nên vẻ đẹp trẻ trung, phù hợp với mọi kiểu không gian phòng. ';
//$searchPage = mb_convert_encoding($temp, 'HTML-ENTITIES', "UTF8");
//    vdd($matches[1]);
$doc->loadHTML($temp);
$content = $doc->saveHTML();

vdd($content);
if (preg_match('/<div class="box_content">\s*<aside class="left_content">.*?<div class="box-article ">\s*<article id="tinh-nang" class="area_article">(.+?)<div class="likeshare">/s', $output, $matches)) {
    $searchPage = mb_convert_encoding($matches[1], 'HTML-ENTITIES', "UTF-8");
//    vdd($matches[1]);
    $doc->loadHTML($searchPage);
}

//$xpath = new DOMXPath($doc);
//$imgs = $xpath->evaluate("//img");

$imgs = $doc->getElementsByTagName('img');

foreach ($imgs as $img){
    $img->removeAttribute('alt');
    $img->removeAttribute('style');
    $img->removeAttribute('title');
    $img->removeAttribute('class');

    $url = $img->getAttribute('data-src');
    $img->removeAttribute('data-src');
    $url = str_replace('https://', '', $url);
    $img->setAttribute("data-src", $url);
}

$content = $doc->saveHTML();

vdd($content);

/*if (preg_match_all('/<img .+?>/s', $content, $matches)) {*/
//    for($i=0; $i<sizeof($matches[0]); $i++){
//        $content = str_replace($matches[0][$i], "", $content);
//    }
//}
//
/*if (preg_match_all('/<a href=".+?>/s', $content, $matches)) {*/
//    for($i=0; $i<sizeof($matches[0]); $i++){
//        $content = str_replace($matches[0][$i], "", $content);
//    }
//    $content = str_replace("</a>", "", $content);
//}
//
//$content = str_replace("h3", "h4", $content);

//foreach ($doc as $item){
//    if($item->name == "img"){

//    }
//}

