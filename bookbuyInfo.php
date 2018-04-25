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

$url = 'https://bookbuy.vn/qua-tang/thu-nhoi-bong-goi-om-icon-cam-xuc-facebook-made-in-viet-nam-nhieu-mau-tuy-chon-p75661.html';
$content = getContent($url);

if(preg_match('~<div class="main-img img-view ">\s*<a href="(.+?)"~', $content, $imageMatch)){
    $image = 'https://bookbuy.vn'.$imageMatch[1];
}
echo '<img src="'.$image.'">';

if(preg_match('~<div itemprop="description" class="des-des">(.+?)<\/div>~s', $content, $desMatch)){
    $des = $desMatch[1];
}
vdd($des);

/*if(preg_match('~<div class="tab1_content_1 book_tab_ct".+?>\s*(.+?)\s*<\/div>\s*<div class="tab1_content_2 book_tab_ct"~s', $content, $matches)){*/
//    $des_full = $matches[1];
//}
