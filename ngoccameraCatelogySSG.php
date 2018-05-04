<?php

$totalPage = 0;
$stackSize = 10;

$content = \CoreSSG\Helpers\GeneralHelper::getContent($categoryLink);

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
        $linkPage[] = $categoryLink . '?page=' . $i;
    }

    while (!empty($linkPage)) {
        $urlList = array_splice($linkPage, 0, $stackSize);

        $contentList = \CoreSSG\Helpers\GeneralHelper::getContentMulti($urlList);

        foreach ($contentList as $content) {
            if (preg_match_all('~<li class="product-item text-center col-post">\s+<a href="(.+?)".+?<p class="price-new price">(.+?)<\/p>~', $content['content'], $matches)) {
                foreach ($matches[1] as $index => $link) {
                    if ($matches[2][$index] != 'Liên hệ') {
                        $leechLinkList[] = 'http://ngoccamera.vn' . $link;
                    }
                }
            }
        }
    }
    $expectedNumOfPMs = count($leechLinkList);
} else {
    if (preg_match_all('~<li class="product-item text-center col-post">\s+<a href="(.+?)".+?<p class="price-new price">(.+?)<\/p>~', $content, $matches)) {
        foreach ($matches[1] as $index => $link) {
            if ($matches[2][$index] != 'Liên hệ') {
                $leechLinkList[] = 'http://ngoccamera.vn' . $link;
            }
        }
        $expectedNumOfPMs = count($leechLinkList);
    } else {
        $expectedNumOfPMs = 0;
    }
}