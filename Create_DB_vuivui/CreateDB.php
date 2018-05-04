<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'tunglt';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE brand (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            logo VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            status TINYINT NOT NULL DEFAULT '1')";
if (!$conn->query($sql) === TRUE) {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE category (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            status TINYINT NOT NULL DEFAULT '1',
            parent_id INT UNSIGNED NULL,
            level TINYINT NULL)";
if (!$conn->query($sql) === TRUE) {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE category (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
            description TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            status TINYINT NOT NULL DEFAULT '1',
            parent_id INT UNSIGNED NULL,
            level TINYINT NULL)";
if (!$conn->query($sql) === TRUE) {
    echo "Error creating table: " . $conn->error;
}

mysqli_query($conn,"SET NAMES 'UTF8'");
die();