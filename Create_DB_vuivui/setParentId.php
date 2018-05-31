<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vuivui';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = "SELECT id, url, parent_id, parent_url FROM category ORDER BY id ASC";
$categoryRecord = $conn->query($sql);

if ($categoryRecord->num_rows > 0) {
    while ($row = $categoryRecord->fetch_assoc()) {
        if ($row['parent_id'] == null) {
            $sql = "SELECT id, url FROM category WHERE url = '$row[parent_url]'";
            $parent = $conn->query($sql);

            if ($parent->num_rows > 0) {
                $parent = $parent->fetch_assoc();

                echo 'Set parent_id=' . $parent['id'] . ' for ' . $row['id'];

                $sql = "UPDATE category SET parent_id='$parent[id]' WHERE id='$row[id]'";
                if (!$conn->query($sql) === TRUE) {
                    echo 'Error update category: ' . $conn->error;
                    flush();
                }
            } else {
                echo 'Error 1: Can\'t find parent_id =' . $row['parent_id'] . ' of ' . $row['id'] . '<br>';
                flush();
            }
        }
    }
}
