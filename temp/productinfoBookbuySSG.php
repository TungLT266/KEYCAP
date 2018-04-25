<?php

switch ($type) {
    case 'des_short':
        if(preg_match('~<div itemprop="description" class="des-des">(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if(preg_match('~<div itemprop="description" class="des-des">(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image':
        if(preg_match('~<div class="main-img img-view ">\s*<a href="(.+?)"~', $content, $matches)){
            $result['data'] = 'https://bookbuy.vn'.$matches[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}