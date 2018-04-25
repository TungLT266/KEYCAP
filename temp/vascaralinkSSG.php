<?php

$totalPage = 0;
$stackSize = 10;
$leechLinkList = array();

function extractVascaraLinks($content, &$leechLinkList){
    if (preg_match_all('~<figure class="item-product"><a href="([^"]+?)".+?<img src="([^"]+?)".+?</figure>~s', $content, $matches)) {
        vdd($matches);
        foreach ($matches[0] as $index => $item){
            if (stripos($item, '4741523441601.png') === false) {

                    $leechLinkList[] = $matches[1][$index];
            }
        }
    }
}

$content = \CoreSSG\Helpers\GeneralHelper::getContent($categoryLink);

if(preg_match('~<div class="count-item-page">~', $content)){
    if (preg_match('~<span class="viewmore-totalitem">(\d+)<\/span>~', $content, $matches)) {
        if (preg_match('~id="hdn_cate_id" value="(\d+)"~', $content, $cate)) {
            $cate = $cate[1];
        }

        $totalPage = intval(ceil((12 + (int)$matches[1])/12));

        $urlPage = array();
        for ($i=1; $i<=$totalPage; $i++){

            $urlPage[] = "https://www.vascara.com/product/filterproduct?page=$i&cate=$cate&viewmore=1&viewcol=3";
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
            $contents = \CoreSSG\Helpers\GeneralHelper::getContentMulti($urlPageTemp);

            foreach ($contents as $value){
                $content = json_decode($value['content'])->html;
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