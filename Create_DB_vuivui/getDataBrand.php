<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "vuivui";

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

$url = 'https://www.vuivui.com/thuong-hieu-xxx-118';
$content = getContent($url);
$result = array();

if(!preg_match('~<h1>TRANG BẠN TÌM KHÔNG TỒN TẠI<\/h1>~', $content)){
    if(preg_match('~<h2 class="title">.+?<b>(.+?)<\/b>~s', $content, $matches)){
        $name = $matches[1];
    }

    if(preg_match('~\d+$~s', $url, $matches)){
        $id = $matches[0];
    }

    if(preg_match('~<div class="info ">(.+?<\/div>)\s*<\/div>~s', $content, $matches)){
        $description = $matches[1];
    }

    if(preg_match('~<figure class="companylogo">.+?src="(.+?)"~s', $content, $matches)){
        $logo = 'https:'.$matches[1];
    }

    $conn = new mysqli($servername, $username, $password, $database);

    $sql = "INSERT INTO brand (id, name, description, logo, status)
            VALUES ('12', 'a', 'a', 'a', '1')";
    mysqli_query($conn, $sql);
}

//vdd($result);