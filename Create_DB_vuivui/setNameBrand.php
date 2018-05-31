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

$sql = "SELECT id, name FROM brand ORDER BY id ASC";
$brandRecord = $conn->query($sql);

if ($brandRecord->num_rows > 0) {
    while ($row = $brandRecord->fetch_assoc()) {
        $name = html_entity_decode($row['name'], ENT_COMPAT, 'UTF-8');
        $sql = "UPDATE brand SET name='$name' WHERE id='$row[id]'";
        if (!$conn->query($sql) === TRUE) {
            echo 'Error update name category: ' . $conn->error;
        }
    }
}