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

$url = 'http://ngoccamera.vn/san-pham/canon-eos-m50-id3391';
$content = getContent($url);
if(preg_match('~<div class="detail-picture-big">.+?<img src="([^"]+)"~', $content, $imageMatch)){
    $list = 'http://ngoccamera.vn'.$imageMatch[1];
}
echo '<img src="'.$list.'">';
