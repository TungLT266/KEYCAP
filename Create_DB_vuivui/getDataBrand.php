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
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vuivui';


$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_query($conn,"SET NAMES 'UTF8'");

$stackSize = 8;
$resultList = array();

for($i=1; $i<2400; $i+=$stackSize){
    $urlList = [];
    for($j=0; $j<$stackSize; $j++){
        $urlList[] = 'https://www.vuivui.com/thuong-hieu-xxx-'.($i+$j);
    }

    $contentList = multiCurl($urlList);
    foreach ($contentList as $index => $content){
        if(!preg_match('~<h1>TRANG BẠN TÌM KHÔNG TỒN TẠI<\/h1>~', $content)){
            $result = [];
            // id
            if(preg_match('~\d+$~s', $urlList[$index], $matches)){
                $result[0] = $matches[0];
            }

            // name
            if(preg_match('~<h2 class="title">.+?<b>(.+?)<\/b>~s', $content, $matches)){
                $result[1] = $matches[1];
            }

            // description
            if(preg_match('~<div class="info ">(.+?<\/div>)\s*<\/div>~s', $content, $matches)){
                $result[2] = $matches[1];
            }

            // logo
            if(preg_match('~<figure class="companylogo">.+?src="(.+?)"~s', $content, $matches)){
                $result[3] = 'https:'.$matches[1];
            }

            if(isset($result[1])){
                $resultList[] = $result;
            }
        }
    }
}

$sql = '';
foreach ($resultList as $result){
    if($sql == ''){
        $sql = "INSERT INTO brand (id, name, description, logo) VALUES ('$result[0]', '$result[1]', '$result[2]', '$result[3]')";
    } else{
        $sql = $sql . ", ('$result[0]', '$result[1]', '$result[2]', '$result[3]')";
    }
}

if($sql != ''){
    mysqli_query($conn, $sql);
    echo 'success';
}
