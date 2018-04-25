<?php

$param = 'productID=1234&imageType=abc&colorID=12'; //tham số truyền vào cách nhau bởi &
$ch = curl_init($url);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $param);
$cookie = dirname(__FILE__).'/cookie.txt';
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);