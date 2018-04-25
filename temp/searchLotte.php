<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getContent2($url, $key)
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch,CURLOPT_POSTFIELDS, $key );
	curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

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
    $payload = json_encode(array( "params"=> "query=$key"));
    $url = 'https://els.lotte.vn/api/v1/products/query';

    $item = array();

    $content = getContent2($url, $payload);

    $listItems = json_decode($content)->hits;

    foreach ($listItems as $value){
        if(preg_match('~https:\/\/www\.lotte\.vn\/catalog\/product\/view\/id\/(\d+)\/s\/(.+?)\/~', $value->url, $matches)){
            $item[] = [
                'url' => "https://www.lotte.vn/product/$matches[1]/$matches[2]",
                'name' => $value->name,
                'image' => 'http:'.$value->image_url,
                'price' => $value->price_default
            ];
        } else{
            $item[] = [
                'url' => $value->url,
                'name' => $value->name,
                'image' => 'http:'.$value->image_url,
                'price' => $value->price_default
            ];
        }
    }

    showResult($item);
} elseif (isset($_GET['url'])){
    $url = $_GET['url'];
    if(preg_match('~https:\/\/www\.lotte\.vn\/product\/(\d+)\/~', $url, $idMatch)){
        $content = getContent("https://www.lotte.vn/rest/V1/lotte_product/details?id=$idMatch[1]&isDetailPage=1");
        $content = json_decode($content)->extension_attributes->base_url_media;

        $image = array();
        foreach ($content as $item){
            $image[] = $item->large_image_url;
        }
        showImages($image);
    }
}

