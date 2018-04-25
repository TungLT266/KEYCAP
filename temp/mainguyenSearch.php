<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
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

    $url = 'https://www.mainguyen.vn';

    $item = array();

    $key = str_replace(' ', '+', $key);
    $content = getContent($url . '/search/suggest?keyword=' . $key);

    if (preg_match_all('~<div class="table-cell suggest-blk">.+?<\/div>~s', $content, $matches)) {
        foreach ($matches[0] as $content) {
            if (preg_match_all('~<li>.+?href="(.+?)".+?src="(.+?)".+?<h4.+?>(.+?)<\/h4>~s', $content, $matches1)) {
                for ($i = 0; $i < sizeof($matches1[0]); $i++) {
                    $item[] = [
                        'url' => $url . $matches1[1][$i],
                        'name' => $matches1[3][$i],
                        'image' => $url . $matches1[2][$i]
                    ];
                }
            }
        }
    }

    showResult($item);
} elseif (isset($_GET['url'])){
    $image = $_GET['url'];
    $content = getContent($image);

    $image = array();
    if(preg_match_all('~<li data-src="(.+?)"~', $content, $matches)){
        foreach ($matches[1] as $item){
            $image[] = 'https://www.mainguyen.vn'.$item;
        }
    }
    showImages($image);
}