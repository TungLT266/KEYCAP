<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vuivui';

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = 'CREATE DATABASE '.$database;
if (!$conn->query($sql) === TRUE) {
    echo 'Error creating database: ' . $conn->error;
}

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "CREATE TABLE brand (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            logo VARCHAR(255) NULL,
            status TINYINT NOT NULL DEFAULT '1') ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";
if (!$conn->query($sql) === TRUE) {
    echo 'Error creating table brand: ' . $conn->error;
}

$sql = "CREATE TABLE category (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            status TINYINT NOT NULL DEFAULT '1',
            parent_id INT UNSIGNED NULL,
            level TINYINT NULL) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";
if (!$conn->query($sql) === TRUE) {
    echo 'Error creating table category: ' . $conn->error;
}

$sql = "CREATE TABLE product (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
            price INT UNSIGNED NULL,
            price_sale INT UNSIGNED NULL,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            image VARCHAR(255) NULL,
            image_list TEXT NULL,
            stock TINYINT NOT NULL DEFAULT '1',
            brand_id INT UNSIGNED NULL,
            category_id INT UNSIGNED NULL,
            status TINYINT NOT NULL DEFAULT '1') ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";
if (!$conn->query($sql) === TRUE) {
    echo 'Error creating table product: ' . $conn->error;
}
