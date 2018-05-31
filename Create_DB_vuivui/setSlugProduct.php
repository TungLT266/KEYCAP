<?php

function vnToSrt($str)
{
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ|D|Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        '-' => '\s+'
    );

    foreach ($unicode as $index => $uni) {
        $str = preg_replace("~($uni)~i", $index, $str);
    }

    $str = preg_replace('~[^\d\w-]~', '', $str);
    $str = preg_replace('~--+~', '-', $str);
    $str = strtolower($str);

    return $str;
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
    die('Connection failed: ' . $conn->connect_error);
}
mysqli_query($conn, "SET NAMES 'UTF8'");

$sql = "SELECT id, name, slug FROM product ORDER BY id ASC";
$productList = $conn->query($sql);

if ($productList->num_rows > 0) {
    while ($row = $productList->fetch_assoc()) {

        if ($row['slug'] != null) {
            $slug = vnToSrt($row['name']);

            $sql = "SELECT slug FROM product WHERE slug='$slug'";
            if ($conn->query($sql)->num_rows > 0) {
                $i = 1;

                while ($i != 0) {
                    $slug2 = $slug . '-' . $i;

                    $sql = "SELECT slug FROM product WHERE slug='$slug2'";
                    if ($conn->query($sql)->num_rows > 0) {
                        $i++;
                    } else {
                        $sql = "UPDATE product SET slug='$slug2' WHERE id='$row[id]'";
                        if ($conn->query($sql) === TRUE) {
                            echo $row['id'] . ': ' . $slug2 . '<br>';
                            flush();
                        } else {
                            echo $row['id'] . ': Error ' . $slug2 . '<br>';
                            flush();
                        }

                        $i = 0;
                    }
                }
            } else {
                $sql = "UPDATE product SET slug='$slug' WHERE id='$row[id]'";
                if ($conn->query($sql) === TRUE) {
                    echo $row['id'] . ': ' . $slug . '<br>';
                    flush();
                } else {
                    echo $row['id'] . ': Error ' . $slug . '<br>';
                    flush();
                }
            }


        }
    }
}