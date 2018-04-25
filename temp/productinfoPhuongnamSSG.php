<?php

switch ($type) {
    case 'des_short':
        if(preg_match('~<div class="wysiwyg" itemprop="description">(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if(preg_match('~<div class="wysiwyg" itemprop="description">(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image':
        if(preg_match('~<div id="product-thumb-\d+" class="product-thumb">.+?href="([^"]+)"~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}