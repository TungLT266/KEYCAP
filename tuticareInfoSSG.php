<?php

switch ($type) {
    case 'des_short':
        if(preg_match('~<div id="tab1".+?>(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if(preg_match('~<div id="tab1".+?>(.+?)<\/div>~s', $content, $matches)){
            $result['data'] = $matches[1];
        }
        break;
    case 'image':
        if(preg_match('~<div id="img-large".+?src="(.+?)"~s', $content, $matches)){
            $result['data'] = 'https://www.tuticare.com'.$matches[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}