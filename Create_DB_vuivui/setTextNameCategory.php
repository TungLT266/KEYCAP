<?php

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
    die('Connection failed: ' . $conn->connect_error);
}
mysqli_query($conn, "SET NAMES 'UTF8'");

$sql = "SELECT id, name FROM category ORDER BY id ASC";
$categoryRecord = $conn->query($sql);

if ($categoryRecord->num_rows > 0) {
    while ($row = $categoryRecord->fetch_assoc()) {
        $name = html_entity_decode($row['name'], ENT_COMPAT, 'UTF-8');
        $sql = "UPDATE category SET name='$name' WHERE id='$row[id]'";
        if (!$conn->query($sql) === TRUE) {
            echo 'Error update name category: ' . $conn->error;
        }
    }
}