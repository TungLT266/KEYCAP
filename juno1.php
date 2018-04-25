<?php

function getContent($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function juno ($url){
    $result['url'] = $url;
    $content = getContent($url);

    if (preg_match('/<title>\s*(.+?)\s*<\/title>.*?<div class="item itemdelete lazy".*?<img src="(.+?)"\/>.*?<label class="variant-price red">(.+?)<sup>.*?<\/sup><\/label>/s', $content, $matches)) {
        $result['name'] = $matches[1];
        $result['price'] = intval(str_replace(',', '', $matches[3]));
        $result['image'] = 'http:'.$matches[2];
    }

//    vdd($result);
    return $result;
}
//http://product.hstatic.net/1000003969/product/kem_cg07067_1_grande.jpg

vdd(juno('https://juno.vn/products/giay-cao-got-dang-kitten-heel-dinh-da-cg07067'));