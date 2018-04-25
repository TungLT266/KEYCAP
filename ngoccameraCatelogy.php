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

function getLinkList($content){
    $linkList = [];
    if(preg_match_all('~<li class="product-item text-center col-post">\s+<a href="(.+?)"~', $content, $matches)){
        foreach ($matches[1] as $link){
            $linkList[] = 'http://ngoccamera.vn'.$link;
        }
    }
    return $linkList;
}

$totalPage = 0;
$stackSize = 10;
$leechLinkList = array();

$url = 'http://ngoccamera.vn/san-pham/may-anh-ong-kinh-roi-cid3';

$content = getContent($url);
if(preg_match_all('~<li class=\'page-item\'>.+?<\/li>~', $content, $matches)){
    if(preg_match('~>(\d+)<\/a>~', $matches[0][sizeof($matches[0])-2], $matches)){
        $totalPage = $matches[1];
    }
}

if($totalPage > 0 ){
    $linkPage = [];
    for($i=1; $i<=$totalPage; $i++){
        $linkPage[] = $url.'?page='.$i;
    }
} else{
    $leechLinkList = getLinkList($content);
}

vdd($leechLinkList);



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