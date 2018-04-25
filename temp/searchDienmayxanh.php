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
    $url = 'https://www.dienmayxanh.com/';

    $item = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent($url . "webapi/suggestsearch?keywords=$key&provinceId=3&categoryId=-1");
//    vdd($content);

    if (preg_match_all('~<li>.+?<\/li>~s', $content, $matches)) {
        foreach ($matches[0] as $value) {
            if (preg_match('~href="(.+?)".+?data-img="(.+?)".+?title="(.+?)".+?class="price price-color">(.+?)₫~s', $value, $matches2)) {
                $item[] = [
                    'url' => 'https://www.dienmayxanh.com' . $matches2[1],
                    'name' => $matches2[3],
                    'image' => $matches2[2],
                    'price' => (int)str_replace('.', '', $matches2[4])
                ];
            }
        }
    }

    showResult($item);
} elseif (isset($_GET['url'])){
    $url = $_GET['url'];
    $image = [];
    $content = getContent($url);

    if (preg_match('~var GL_CATEGORYID =(\d+);.*?var GL_PRODUCTID=(\d+);~s', $content,$matches)) {
        $content = getContent("https://www.dienmayxanh.com/aj/ProductV2/GetGalleryData?categoryId=$matches[1]&productId=$matches[2]");
        $content = json_decode($content);
        foreach ($content as $items){
            foreach ($items as $item){
                $image[] = $item->pictureField;
            }
        }

        $content = getContent("https://www.dienmayxanh.com/aj/ProductV2/Gallery?categoryId=$matches[1]&productId=$matches[2]&isUnbox=true");
        if(preg_match('~<img data-lazy="([^"]+)"~', $content,$imageBoxMatch)){
            $image[] = $imageBoxMatch[1];
        }
    }

    showImages($image);
}