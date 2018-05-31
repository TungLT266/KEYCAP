<?php

function getContent($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function vdd($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vuivui';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_query($conn, "SET NAMES 'UTF8'");

for ($i = 1; $i < 2400; $i++) {
    $result = [];
    $url = 'https://www.vuivui.com/thuong-hieu-xxx-' . $i;
    $content = getContent($url);
//    vdd($content);

    if (!preg_match('~<html><head><title>Object moved<\/title><\/head><body>~', $content)) {
        $result['id'] = $i;

        if (preg_match('~<h2 class="title">.+?<b>(.+?)<\/b>~s', $content, $nameMatch)) {
            $result['name'] = html_entity_decode($nameMatch[1], ENT_COMPAT, 'UTF-8');

            if (preg_match('~<div class="info ">(.+?<\/div>)\s*<\/div>~s', $content, $descriptionMatch)) {
                $result['description'] = $descriptionMatch[1];
            } elseif (preg_match('~<div class="info hvd">(.+?<\/div>)\s*<\/div>~s', $content, $descriptionMatch)) {
                $result['description'] = $descriptionMatch[1];
            } else {
                $result['description'] = '';

                echo 'Error 2: Can\'t get description <a target="_blank" href="' . $url . '">' . $url . '</a><br>';
                flush();
            }

            if (preg_match('~<figure class="companylogo">.+?src="(.+?)"~s', $content, $logoMatch)) {
                $result['logo'] = 'https:' . $logoMatch[1];
            } else {
                $result['logo'] = '';

                echo 'Error 3: Can\'t get logo <a target="_blank" href="' . $url . '">' . $url . '</a><br>';
                flush();
            }

            echo $result['id'] . ': ' . $result['name'] . '<br>';
            $sql = "INSERT INTO brand (id, name, description, logo) VALUES ('$result[id]', '$result[name]', '$result[description]', '$result[logo]')";
            mysqli_query($conn, $sql);

            flush();
        } else {
            echo 'Error 1: Can\'t get name <a target="_blank" href="' . $url . '">' . $url . '</a><br>';
            flush();
        }
    }
}
