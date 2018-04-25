<?php

function login($url, $user)
{
    $ch = curl_init($url);

    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $user);

    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $cookie = dirname(__FILE__).'/cookie.txt';
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);


    $output = curl_exec($ch);
//    curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getContent($url)
{
    $ch = curl_init($url);

    curl_setopt ($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $cookie = dirname(__FILE__).'/cookie.txt';
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);

    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var){
    echo "<p>";
    var_dump($var);
    echo "</p>";
    die;
}

$url = "http://www.vn-zoom.com/";
$urlLogin = "http://www.vn-zoom.com/login.php?do=login";
$userLogin = "vb_login_username=thanhtung33333&vb_login_password=&x=22&y=16&s=&securitytoken=1523332200-b620add3b9989819a7f53923b2c35ff9cf6682b2&do=login&vb_login_md5password=f616eaf17c49cb9075b757702c9f3084&vb_login_md5password_utf=f616eaf17c49cb9075b757702c9f3084";

$content = getContent($url);


while(1){
    $content = getContent($url);
    if (preg_match('/name="vb_login_username".*name="vb_login_password"/s', $content, $matches)) {
        login($urlLogin, $userLogin);
    }elseif(preg_match('/return log_out/', $content, $matches)){
        vdd($content);
    }
}

//if (preg_match('/name="vb_login_username".*name="vb_login_password"/s', $content)) {
//    $result = login($urlLogin, $userLogin);
//
//    if(preg_match('/strong> đã đăng nhập thành công<br/', $result))
//    {
//        $content = getContent($url);
//        vdd($content);
//    }
//    else{
//        echo $result;
//    }
//}else{
//    vdd($content);
//}
