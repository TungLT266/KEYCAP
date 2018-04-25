<?php

switch ($type) {
    case 'des_short':
        if(preg_match('~<div class="short-desc">(.+?)<\/div>~', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if(preg_match('~<div class="desc">(.+?)<\/div>~', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image':
        if(preg_match('~<p class="product-image">.+?data-large="(.+?)"~s', $content, $matches)){
            $result['data'] = 'https:'.$matches[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}