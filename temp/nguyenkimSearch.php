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
    $key = str_replace(' ', '%20', $key);

    $item = array();

    $content = getContent("https://solr.nguyenkim.com/?keyword=$key&request=search");
    if(preg_match('~^\(({product:{.+?),\s*category:{~s', $content, $matches)){
        $content = $matches[1].'}';
        $content = preg_replace('~^{product:~', '{"product":', $content);
        $content = json_decode($content);
        if(isset($content)!=null){
            $productList = $content->product->response->docs;
            foreach ($productList as $product){
                $item[] = [
                    'url' => $product->link,
                    'name' => $product->product,
                    'image' => json_decode($product->main_pair)->image_path,
                    'price' => $product->price
                ];
            }
        }
    }
    showResult($item);
} elseif (isset($_GET['url'])){
    $image = $_GET['url'];
    $content = getContent($image);
    if(preg_match_all('~data-full="(.+?)"~', $content, $matches)){
        showImages($matches[1]);
    }
}