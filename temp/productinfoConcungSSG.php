<?php

switch ($type) {
    case 'des_short':
        if(preg_match('~<div id="short_description_content">(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if(preg_match('~<div class="content-detail">(.+?)<span class="read_more hide"~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image':
        if(preg_match('~<div class="main">.+?href="(.+?)"~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}