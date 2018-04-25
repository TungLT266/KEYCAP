<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function mergeResult($url,$result, $countResults){
    $page = ceil($countResults/23);

    for($i=2; $i <= $page; $i++)
    {
        $output = getContent($url.'?page='.$i);
        $result = array_merge($result, getResult($output));
        return $result;
    }

}

function vdd($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function getResult($output){
    if (preg_match_all('/<div data-seller-product-id="\d+"\s*data-title="(.+?)" data-price="(\d+)".*?<a.*?href="(.*?)".*?<img class="product-image img-responsive" src="(.*?)"/s', $output, $matches)) {
        for($i=0; $i<sizeof($matches[1]); $i++){
            $result[$i] = [
                'url' => $matches[3][$i],
                'name' => $matches[1][$i],
                'price' => $matches[2][$i],
                'image' => $matches[4][$i]
            ];
        }
    }
    return $result;
}

$url = "https://tiki.vn/dien-thoai-may-tinh-bang/c1789";
$output = getContent($url);

$result = getResult($output);

if (preg_match('/<h4 name="results-count">\s*(\d+).*?<\/h4>/s', $output, $matches)) {
    $count = intval($matches[1]);
    if($count>23){
        $result = mergeResult($url, $result, $count);
    }
}

vdd($result);