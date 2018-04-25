<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getContent2($url, $para)
{
    $ch = curl_init($url);

    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $para);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function multiCurl($url, $para)
{
    $curly = array();
    $result = array();
    $mh = curl_multi_init();

    foreach ($para as $id => $item)
    {
        $curly[$id] = curl_init();

        curl_setopt($curly[$id], CURLOPT_URL, $url);
        curl_setopt ($curly[$id], CURLOPT_POST, true);
        curl_setopt ($curly[$id], CURLOPT_POSTFIELDS, $item);
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
    $url = 'https://www.thegioididong.com';

    $item = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent($url . '/aj/CommonV3/SuggestSearch?keyword=' . $key);

    if (preg_match_all('~<li>.+?href="([^"]+)">.+?src="([^"]+)".+?<h3>(.+?)<\/h3>.+?class="price">.*?([0-9\.]+)₫~s', $content, $matches)) {
        foreach ($matches[1] as $index => $value) {
            $item[] = [
                'url' => $url.$value,
                'name' => $matches[3][$index],
                'image' => $matches[2][$index],
                'price' => (int)str_replace('.', '', $matches[4][$index])
            ];

        }
    }
    showResult($item);
} elseif (isset($_GET['url'])){
    $image = $_GET['url'];
    $content = getContent($image);
    $image = array();
    $para = array();

    if(preg_match('~var GL_PRODUCTID = (\d+)~', $content, $matches)&&preg_match_all('~onclick="gotoGallery\(([17]),(\d+)\)"~', $content, $matches1)){
        foreach ($matches1[1] as $index => $item){
            $para[] = 'productID='.$matches[1].'&imageType='.$matches1[1][$index].'&colorID='.$matches1[2][$index];
        }
        $contents = multiCurl('https://www.thegioididong.com/aj/ProductV4/GallerySlideFT/', $para);
        foreach ($contents as $index => $content){
            if(preg_match_all('~data-img="(.+?)"~', $content, $matches2)){
                if((int)$matches1[1][$index]==1){
                    foreach ($matches2[1] as $item){
                        $image[] = 'https:'.$item;
                    }
                } elseif ((int)$matches1[1][$index]==7){
                    foreach ($matches2[1] as $item){
                        $image[] = $item;
                    }
                }
            }
        }
        showImages($image);
    }


//    if(preg_match('~var GL_PRODUCTID = (\d+)~', $content, $matches)&&preg_match_all('~onclick="gotoGallery\(([17]),(\d+)\)"~', $content, $matches1)){
//        for($i=0; $i<sizeof($matches1[0]); $i++){
//            $para = 'productID='.$matches[1].'&imageType='.$matches1[1][$i].'&colorID='.$matches1[2][$i];
//            $content = getContent2('https://www.thegioididong.com/aj/ProductV4/GallerySlideFT/', $para);
//            if(preg_match_all('~data-img="(.+?)"~', $content, $matches2)){
//                if((int)$matches1[1][$i]==1){
//                    foreach ($matches2[1] as $item){
//                        $image[] = 'https:'.$item;
//                    }
//                } elseif ((int)$matches1[1][$i]==7){
//                    foreach ($matches2[1] as $item){
//                        $image[] = $item;
//                    }
//                }
//
//            }
//        }
//        showImages($image);
//    }
}