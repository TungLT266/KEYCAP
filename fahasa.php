<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_COOKIE, 'BPC2=350c99c23a3da4092ca63b2e81e79a31;');//350c99c23a3da4092ca63b2e81e79a31

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
        curl_setopt($curly[$id], CURLOPT_URL, $url);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
        curl_multi_add_handle($mh, $curly[$id]);
    }
    $running = null;
    do {
        while (CURLM_CALL_MULTI_PERFORM === curl_multi_exec($mh, $running)) ;
        if (!$running) break;
        while (($res = curl_multi_select($mh)) === 0) {
        };
        if ($res === false) break;
    } while (true);
    foreach ($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }
    curl_multi_close($mh);
    return $result;
}

function vdd($var)
{
    var_dump($var);
    die();
}

$output = getContent('https://www.fahasa.com/');

vdd(htmlentities($output));