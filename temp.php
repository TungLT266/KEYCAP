<?php

$totalPage     = 0;
$stackSize     = 10;

function extractVascaraLinks($content, &$leechLinkList) {
    if ( preg_match_all('~<figure class="item-product"><a href="(.+?)"~', $content, $matches) ) {
        $leechLinkList = array_merge($matches[1], $leechLinkList);
    }
}

$content = \CoreSSG\Helpers\GeneralHelper::getContent($categoryLink);

$itemPerPage = 12;
if ( preg_match('~<div class="count-item-page">~', $content) ) {
    if ( preg_match('~<span class="viewmore-totalitem">(\d+)<\/span>~', $content, $matches) ) {
        if ( preg_match('~id="hdn_cate_id" value="(\d+)"~', $content, $cate) ) {
            $cate = $cate[1];
        }

        $totalPage = ceil(($itemPerPage + (int)$matches[1]) / $itemPerPage);

        $urlList = [];
        for ( $i = 1; $i <= $totalPage; $i++ ) {
            $urlList[] = "https://www.vascara.com/product/filterproduct?page=$i&cate=$cate&viewmore=1&viewcol=3";
        }

        while ( !empty($urlList) ) {
            $urlTempList = array_splice($urlList, 0, $stackSize);

            $contentList = \CoreSSG\Helpers\GeneralHelper::getContentMulti($urlTempList);
            foreach ( $contentList as $content ) {
                if ( isset($content['content']) ) {
                    $content = json_decode($content['content'])->html;
                    extractVascaraLinks($content, $leechLinkList);
                }
            }
        }

        $expectedNumOfPMs = count($leechLinkList);
    } else {
        extractVascaraLinks($content, $leechLinkList);
        if ( !empty($leechLinkList) ) {
            $expectedNumOfPMs = count($leechLinkList);
        } else {
            $expectedNumOfPMs = 0;
        }
    }
} else {
    $expectedNumOfPMs = -1;
}