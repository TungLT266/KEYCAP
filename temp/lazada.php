<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getAll($url, $count){
    $pageNumber = intval(ceil($count/40));
    $result = [];
    for ($i=1; $i<=$pageNumber; $i++){
        $temp = getContent($url."?ajax=true&page=$i");
        $temp = json_decode($temp);
        $temp = $temp->mods->listItems;

        foreach($temp as $value)
        {
            $result[] = [
                'url' => 'https:'.$value->productUrl,
                'name' => $value->name,
                'price' => intval($value->price),
                'image' => $value->image
            ];
        }
//            $resultAll = array_merge($resultAll, $result);
//            vdd($resultAll);
//        echo count($result)."<br>\r\n";
//            flush();
    }
    return $result;
}

function vdd($var){
    var_dump($var);
    die();
}
$start = time();
$url = "https://www.lazada.vn/lo-vi-song/";

$content = getContent($url."?ajax=true&page=1");

$content = json_decode($content);
$count = intval($content->mainInfo->dataLayer->page->resultNr);
$content = getAll($url, $count);

$myfile = fopen("lazadaFile.csv", "w");
$result = "";
foreach ($content as $value){
    $result = $result.$value['url']."\t".$value['name']."\t".$value['price']."\t".$value['image']."\n";
}
//echo "<p>".$result."</p>";

fwrite($myfile, $result);

fclose($myfile);

//if (preg_match('/<script>window.pageData=(.+?)<\/script>/s', $content, $matches)) {
//    $temp = json_decode($matches[1]);
//    $temp = $temp->mods->listItems;
//
//    for($i=0; $i<sizeof($temp); $i++){
//        $result[$i] = [
//            'url' => 'https:'.$temp[$i]->productUrl,
//            'name' => $temp[$i]->name,
//            'price' => intval($temp[$i]->price),
//            'image' => $temp[$i]->image
//        ];
//    }
//
////    vdd($result);
////
////    vdd($temp[0]);
//}

//$url = "https://www.lazada.vn/lo-vi-song/?page=2";
//$content = getContent($url);
//
//if (preg_match('/<script>window.pageData=(.+?)<\/script>/s', $content, $matches)) {
//    $temp = json_decode($matches[1]);
//    $temp = $temp->mods->listItems;
//
//    for($i=0; $i<sizeof($temp); $i++){
//        $result2[$i] = [
//            'url' => 'https:'.$temp[$i]->productUrl,
//            'name' => $temp[$i]->name,
//            'price' => intval($temp[$i]->price),
//            'image' => $temp[$i]->image
//        ];
//    }
//
////    vdd($result);
////
////    vdd($temp[0]);
//}

$time = time() - $start;
echo "\r\n Time: $time";