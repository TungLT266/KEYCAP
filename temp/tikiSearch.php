<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getContent2($url, $apikey)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $apikey);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var){
    var_dump($var);
    die();
}

function showResult($arr){
    $show = "<table style='width:100%'; border='1px solid black'>";
    foreach ($arr as $item){
        $show = $show.'<tr><td><a target="_blank" href="'.$item['url'].'"><img style="max-width:128px;max-height:128px;" src="'.$item['image'].'" alt="'.$item['name'].'"></a></td><td><a target="_blank" href="'.$item['url'].'">'.$item['name'].'</a></td></tr>';
    }
    $show = $show.'</table>';
    echo $show;
}

function showImages($arr){
    $show = '';
    foreach ($arr as $item){
        $show = $show.'<a target="_blank" href="'.$item.'"><img style="max-width:200px;max-height:200px;" src="'.$item.'"></a>';
    }
    echo $show;
}

if (isset($_GET['keyword'])) {
    $key = $_GET['keyword'];
    $url = 'https://tiki.vn/';

    $item = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent($url . "search?q=$key");

    if (preg_match_all('~<div data-seller-product-id.+?data-title="([^"]+)"\s*data-price="(\d+)".+?href="([^"]+)".+?src="([^"]+)"~s', $content, $matches)) {
        foreach ($matches[1] as $index => $value) {
            $item[] = [
                'url' => $matches[3][$index],
                'image' => $matches[4][$index],
                'name' => $value,
                'price' => (int)$matches[2][$index]
            ];
        }
    }
    showResult($item);
} elseif (isset($_GET['url'])){
    $url = $_GET['url'];

    $content = getContent($url);
    $image = array();
    if(preg_match('~var images =\s*(.+?);\s*var imageGalery~s', $content, $matches)){
        $imageList = json_decode($matches[1]);
        foreach ($imageList as $item){
            $image[] = $item->large_url;
        }
    }
//    if (preg_match('~https:\/\/tiki\.vn\/.+?(\d+).html~', $url, $matches)) {
//        $apikey = array('apikey:2cd335e2c2c74a6f9f4b540b91128e55');
//        $content = getContent2("https://tiki.vn/api/v2/reviews?product_id=$matches[1]&limit=-1&top=true", $apikey);
//        $content = json_decode($content);
//
//        foreach ($content as $value){
//            foreach ($value->images as $item){
//                $image[] = $item->full_path;
//            }
//        }
//        showImages($image);
//    }
}