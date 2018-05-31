<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vuivui';

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = 'CREATE DATABASE ' . $database;
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
            name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            url VARCHAR(255) NOT NULL UNIQUE,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            status TINYINT NOT NULL DEFAULT '1',
            parent_id INT UNSIGNED NULL,
            parent_url VARCHAR(255) NULL,
            level TINYINT NULL,
            category_id_vuivui INT UNSIGNED NOT NULL DEFAULT '0') ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";
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
            stock TINYINT NULL,
            brand_id INT UNSIGNED NULL,
            CONSTRAINT fk_brand_id FOREIGN KEY (brand_id) REFERENCES brand(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            category_id INT UNSIGNED NULL,
            CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            status TINYINT NOT NULL DEFAULT '1') ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";
if (!$conn->query($sql) === TRUE) {
    echo 'Error creating table product: ' . $conn->error;
}
