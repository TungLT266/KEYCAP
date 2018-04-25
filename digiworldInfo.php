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

$url = 'http://digiworldhanoi.vn/MAY-ANH/p9944/Fujifilm-GFX-50S-Body-Khuyen-mai-lon-den-30-12-2017.html';
$content = getContent($url);
if(preg_match('~<div class="summary_overview_product">\s*(.+?)\s*<\/div>~', $content, $matches)){
    $des_short = $matches[1];
}
if(preg_match('~<div class="tab1_content_1 book_tab_ct".+?>\s*(.+?)\s*<\/div>\s*<div class="tab1_content_2 book_tab_ct"~s', $content, $matches)){
    $des_full = $matches[1];
}
vdd($des_full);