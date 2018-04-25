<?php

switch ($type) {
    case 'des_short':
        $result['data'] = '';
        break;
    case 'des_full':
        $result['data'] = '';
        break;
    case 'image':
        if(preg_match('~<div class="detail-picture-big">.+?<img src="([^"]+)"~', $content, $imageMatch)){
            $result['data'] = 'http://ngoccamera.vn'.$imageMatch[1];
        }
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}