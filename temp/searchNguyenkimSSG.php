<?php

$keyword = urlencode($keyword);
$url = "https://solr.nguyenkim.com/?keyword=$keyword&request=search";

$content = \CoreSSG\Helpers\GeneralHelper::getContent($url);
if (preg_match('~^\(({product:{.+?),\s*category:{~s', $content, $matches)) {
    $content = $matches[1] . '}';
    $content = preg_replace('~^{product:~', '{"product":', $content);
    $content = json_decode($content);
    if (isset($content) != null) {
        $productList = $content->product->response->docs;
        foreach ($productList as $index => $product) {
            $itemList[] = [
                'url' => $product->link,
                'image' => json_decode($product->main_pair)->image_path,
                'title' => $product->product,
                'price' => $product->price
            ];
            if ($index == MAX_ITEM)
                break;
        }
    }
}