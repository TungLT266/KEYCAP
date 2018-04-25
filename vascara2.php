<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function multiCurl($data)
{
    $curly = array();
    $result = array();
    $mh = curl_multi_init();

    foreach ($data as $id => $url)
    {
        $curly[$id] = curl_init();

//        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
        curl_setopt($curly[$id], CURLOPT_URL, $url);
//        curl_setopt($curly[$id], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1');
//        curl_setopt($curly[$id], CURLOPT_ENCODING, 'gzip,deflate,sdch');
//        curl_setopt($curly[$id], CURLOPT_HEADER,         0);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curly[$id], CURLOPT_TIMEOUT, 20);
//        curl_setopt($curly[$id], CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($curly[$id], CURLOPT_FRESH_CONNECT, true);
//        curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, false);

        curl_multi_add_handle($mh, $curly[$id]);
    }

    // execute the handles
    $running = null;

    do {
        while (CURLM_CALL_MULTI_PERFORM === curl_multi_exec($mh, $running));

        if (!$running) break;

        while (($res = curl_multi_select($mh)) === 0) {};

        if ($res === false) break;
    } while (true);


    // get content and remove handles
    foreach($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
}

function vdd($var){
    var_dump($var);
    die();
}

$totalPage = 0;
$stackSize = 10;
$leechLinkList = array();

function extractVascaraLinks($content, &$leechLinkList){
    if (preg_match_all('~<figure class="item-product">.+?<\/figure>~s', $content, $matches)) {
        foreach ($matches[0] as $item){
            if (!preg_match('~4741523441601\.png"~', $item)) {
                if (preg_match('~<figure class="item-product"><a href="(.+?)"~', $item,$matches2)) {
                    $leechLinkList[] = $matches2[1];
                }
            }
        }
    }
}

$content = getContent('https://www.vascara.com/tui-xach');

if(preg_match('~<div class="count-item-page">~', $content)){
    if (preg_match('~<span class="viewmore-totalitem">(\d+)<\/span>~', $content, $matches)) {
        if (preg_match('~id="hdn_cate_id" value="(\d+)"~', $content, $cate)) {
            $cate = $cate[1];
        }

        $totalPage = intval(ceil((12 + (int)$matches[1])/12));

        $urlPage = array();
        for ($i=1; $i<=$totalPage; $i++){
            $urlPage[$i-1] = "https://www.vascara.com/product/filterproduct?page=$i&cate=$cate&viewmore=1&viewcol=3";
        }

        $times = intval(ceil($totalPage/$stackSize));

        for($i=0; $i<$times; $i++){
            $urlPageTemp = array();
            for($j=0; $j<$stackSize; $j++){
                if(($i*$stackSize+$j)<$totalPage){
                    $urlPageTemp[] = $urlPage[$i*$stackSize+$j];
                } else {
                    break;
                }
            }
            $contents = multiCurl($urlPageTemp);

            foreach ($contents as $value){
                $content = json_decode($value)->html;
                extractVascaraLinks($content, $leechLinkList);
            }
        }

        $expectedNumOfPMs = count($leechLinkList);
    } else{
        extractVascaraLinks($content, $leechLinkList);
        if(!empty($leechLinkList)){
            $expectedNumOfPMs = count($leechLinkList);
        } else{
            $expectedNumOfPMs = 0;
        }
    }
} else{
    $expectedNumOfPMs = -1;
}

var_dump($expectedNumOfPMs);

vdd($leechLinkList);