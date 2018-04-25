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

$url = "https://www.lotte.vn/catalog/product/view/id/560845/s/apple-iphone-8-64gb-grey-hang-nhap-khau-464635/";
$output = getContent($url);

if (preg_match('/<div class="boxArticle">(.+?)<\/div>/s', $output, $matches)) {
    $content = $matches[1];
}

if (preg_match_all('/<(?:h2|p) style="text-align: center;"><span style="font-size: medium;"><a class="preventdefault" href=".+?><img class="lazy".+?\/><\/a><\/span><\/(?:h2|p)>/s', $content, $matches)) {
    for($i=0; $i<sizeof($matches[0]); $i++){
        $content = str_replace($matches[0][$i], "", $content);
    }
}

if (preg_match_all('/<a .+?>/s', $content, $matches)) {
    for($i=0; $i<sizeof($matches[0]); $i++){
        $content = str_replace($matches[0][$i], "", $content);
    }
    $content = str_replace("</a>", "", $content);
}

vdd($content);