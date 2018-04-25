<?php
$start = time();
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

        curl_setopt($curly[$id], CURLOPT_URL, $url);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

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

$url = "https://www.lazada.vn/lo-vi-song/";

$content = getContent($url."?ajax=true&page=1");

$content = json_decode($content);

$count = intval($content->mainInfo->dataLayer->page->resultNr);

$countPage = intval(ceil($count/40));

$data = [];
for ($i=1; $i<=$countPage; $i++){
    $data[$i-1] = $url."?ajax=true&page=$i";
}

$chia = intval(ceil($countPage/8));
$csvFile = fopen("lazadaFile.csv", "w");

for($i=0; $i<$chia; $i++){
    $dataTemp = [];
    for($j=0; $j<8; $j++){
        if(($i*8+$j)<$countPage){
            $dataTemp[] = $data[$i*8+$j];
        }
    }
    $contents = multiCurl($dataTemp);

    $result = '';
    foreach ($contents as $content){
        $temp = json_decode($content);
        $temp = $temp->mods->listItems;

        foreach($temp as $item) {
            $result = $result.'https:'.$item->productUrl."\t".$item->name."\t".intval($item->price)."\t".$item->image."\n";
        }
    }
    fwrite($csvFile, $result);
}

//$result = [];
//foreach ($content as $value){
//    $temp = json_decode($value);
//    $temp = $temp->mods->listItems;
//
//    foreach($temp as $item)
//    {
//        $result[] = [
//            'url' => 'https:'.$item->productUrl,
//            'name' => $item->name,
//            'price' => intval($item->price),
//            'image' => $item->image
//        ];
//    }
//}

//$myfile = fopen("lazadaFile.csv", "w");
//$result2 = "";
//foreach ($result as $value){
//    $result2 = $result2.$value['url']."\t".$value['name']."\t".$value['price']."\t".$value['image']."\n";
//}
//fwrite($myfile, $result2);

fclose($csvFile);


$time = time() - $start;
echo "Time: $time";