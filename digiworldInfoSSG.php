<?php

switch ($type) {
    case 'des_short':
        if (preg_match('~<div class="summary_overview_product">\s*(.+?)\s*<\/div>~', $content, $matches)) {
            $result['data'] = $matches[1];
        }
        break;
    case 'des_full':
        if (preg_match('~<div class="tab1_content_1 book_tab_ct".+?>\s*(.+?)<\/div>\s*<div class="tab1_content_2 book_tab_ct"~s', $content, $matches)) {
            $result['data'] = $matches[1];
        }
        break;
    case 'image':
        $result['data'] = '';
        break;
    case 'image_list':
        $result['data'] = [];
        break;
}