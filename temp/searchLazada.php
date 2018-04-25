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
    $url = 'https://www.lazada.vn';

    $item = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent($url . '/catalog/?q=' . $key);
//    vdd($content);

    if (preg_match('~(?<=<script>window\.pageData=).+?(?=<\/script>)~', $content, $matches)) {
        $listItems = json_decode($matches[0])->mods->listItems;
        foreach ($listItems as $value) {
            $item[] = [
                'url' => 'https:' . $value->productUrl,
                'name' => $value->name,
                'image' => $value->image,
                'price' => (int)$value->price
            ];
        }
    }
    showResult($item);
} elseif (isset($_GET['url'])){
    $image = $_GET['url'];
    $content = getContent($image);

    if (preg_match_all('~<img class="pdp-mod-common-image item-gallery__thumbnail-image" src="(.+?)"~', $content, $matches)) {
        $image = array();
        foreach ($matches[1] as $item){
            $image[] = 'https:'.preg_replace('~-catalog\.jpg.+\.jpg$~', '.jpg', $item);
        }
        showImages($image);
    }
}