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

    foreach ($data as $id => $url) {
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
        while (CURLM_CALL_MULTI_PERFORM === curl_multi_exec($mh, $running)) ;

        if (!$running) break;

        while (($res = curl_multi_select($mh)) === 0) {
        };

        if ($res === false) break;
    } while (true);


    // get content and remove handles
    foreach ($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
}

function vdd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}

$totalPage = 0;
$stackSize = 10;
$leechLinkList = array();

$url = 'http://ngoccamera.vn/san-pham/may-anh-ong-kinh-roi-cid3';

$content = getContent($url);
if (preg_match_all('~<li class=\'page-item\'>.+?<\/li>~', $content, $matches)) {
    if (sizeof($matches[0]) > 3) {
        if (preg_match('~>(\d+)<\/a>~', $matches[0][sizeof($matches[0]) - 2], $matches)) {
            $totalPage = (int)$matches[1];
        }
    }
}

if ($totalPage > 0) {
    $linkPage = [];
    for ($i = 1; $i <= $totalPage; $i++) {
        $linkPage[] = $url . '?page=' . $i;
    }
//    vdd($linkPage);
    $contentList = multiCurl($linkPage);

    vdd($contentList[3]);
//    vdd($contentList[3]);
//    getLinkList($contentList[3], $leechLinkList);
//    vdd($leechLinkList);

    foreach ($contentList as $content){
        if (preg_match_all('~<li class="product-item text-center col-post">\s+<a href="(.+?)".+?<p class="price-new price">(.+?)<\/p>~', $content, $matches)) {
            foreach ($matches[1] as $index => $link) {
                if($matches[2][$index]!='Liên hệ'){
                    $leechLinkList[] = 'http://ngoccamera.vn' . $link;
                }
            }
        }
    }
} else {
    if (preg_match_all('~<li class="product-item text-center col-post">\s+<a href="(.+?)".+?<p class="price-new price">(.+?)<\/p>~', $content, $matches)) {
        foreach ($matches[1] as $index => $link) {
            if($matches[2][$index]!='Liên hệ'){
                $leechLinkList[] = 'http://ngoccamera.vn' . $link;
            }
        }
    }
}

vdd($leechLinkList);