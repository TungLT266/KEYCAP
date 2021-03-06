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

//$content = getContent('https://www.vuivui.com/sac-dtdd?page=5');
//vdd($content);

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'vuivui';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_query($conn, "SET NAMES 'UTF8'");

/*
$sql = "SELECT url FROM category WHERE product='1'";
$categoryUrlList = $conn->query($sql);
//vdd($categoryUrlList);

if ($categoryUrlList->num_rows > 0) {
    while ($row = $categoryUrlList->fetch_assoc()) {
        $content = getContent($row['url']);
//        vdd($row['url']);

        if(isset($content)){
            if(preg_match_all('~<div class="itmpro prohv"><a href=([^\s]+)~s', $content,$productListMatch)){
                foreach ($productListMatch[1] as $productLink){
//                    vdd('https://www.vuivui.com'.$productLink);
                    $content = getContent('https://www.vuivui.com'.$productLink);
//                    vdd($content);

                    if(preg_match('~<h1 class=productname>(.+?)<\/h1>~', $content, $nameMatch)){
                        vdd($nameMatch[1]);
                    }else{
                        echo 'Error: Can\'t get product name of https://www.vuivui.com'.$productLink.', from category '.$row['url'].'<br>';
                        flush();
                    }
                }
            }else{
                echo 'Error: Can\'t get product from '.$row['url'].'<br>';
                flush();
            }
        }else{
            echo 'Error: Can\'t get content '.$row['url'].'<br>';
            flush();
        }

//        $page = 1;
//        while(true){
//            $content = getContent($categoryLinkMatch[1].'?page='.$page);
//
//            if(preg_match('~<html><head><title>Object moved<\/title><\/head><body>~', $content)){
//                break;
//            }elseif(preg_match('~<div id=grid-product.+?<div class=pagination>~s', $content, $matches)) {
//                if(preg_match_all('~<div class="itmpro prohv"><a href=(.+?) class=itempicture>~', $matches[0], $linkListMatch)) {
//                    foreach ($linkListMatch[1] as $linkProduct) {
//                        echo $linkProduct.'<br>';
//                        flush();
//                    }
//                }
//            }
//
//            $page++;
//        }
        die();
    }
}else{
    echo 'Error: Category record is null<br>';
    flush();
}
*/

$url = 'https://www.vuivui.com/sitemap.xml';
$content = getContent($url);
//vdd($content);

$dom = new DOMDocument();
$dom->loadXML($content);
$categoryLinkList = $dom->getElementsByTagName('loc');

foreach ($categoryLinkList as $categoryLink) {
    $content = getContent($categoryLink->nodeValue);
//    vdd($content);

    if (preg_match('~sitemap_(\d+)\.xml$~', $categoryLink->nodeValue, $categoryIdMatch)) {
        $categoryId = $categoryIdMatch[1];
//        vdd($categoryId);
    } else {
        echo 'Error 1: Can\'t get category id <a target="_blank" href="' . $categoryLink->nodeValue . '">' . $categoryLink->nodeValue . '</a><br>';
        flush();
    }

    if (preg_match_all('~<loc>(.+?)<\/loc>~', $content, $productLinkList)) {
//        vdd($productLinkList[1]);

        foreach ($productLinkList[1] as $productLink) {
            $result = [];
            $content = getContent($productLink);
//            $content = getContent('https://www.vuivui.com/dien-thoai-di-dong/htc-desire-620g');
//            vdd($content);

            // Name
            if (preg_match('~<h1 class="*productname"*>(.+?)<\/h1>~', $content, $nameMatch)) {
                $result['name'] = html_entity_decode($nameMatch[1], ENT_COMPAT, 'UTF-8');
//                vdd($result['name']);

                // Stock
                if (preg_match('~<span class="*status\s*"*>\s*Còn hàng\s*<\/span>~', $content)) {
                    $result['stock'] = 1;

                    // Price
                    if (preg_match('~<div class="*boxprice"*>\s*<div class="*prices"*>\s*<span class="*new"*>([0-9\.]+)₫<\/span>\s*<span class="*line"*>([0-9\.]+)~', $content, $priceMatch)) {
                        $result['price'] = (int)str_replace('.', '', $priceMatch[1]);
                        $result['price_sale'] = (int)str_replace('.', '', $priceMatch[2]);
//                    echo $result['price'].' and '.$result['price_sale'].'<br>';
//                    flush();
                    } elseif (preg_match('~<div class="*boxprice"*>\s*<div class="*prices"*>\s*<span class="*new"*>([0-9\.]+)~', $content, $priceMatch)) {
                        $result['price_sale'] = (int)str_replace('.', '', $priceMatch[1]);
//                    echo $price.'<br>';
//                    flush();
                    } else {
                        echo 'Error 2: Can\'t get product price of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                        flush();
                    }
                } elseif (preg_match('~<span class="status tamhethang">\s*Tạm hết hàng\s*<\/span>~', $content)) {
                    $result['stock'] = 0;

                    //Price
                    if (preg_match('~<span class="*new"*>([0-9\.]+)₫<\/span>\s*<span class="status tamhethang">~', $content, $priceMatch)) {
                        $result['price_sale'] = (int)str_replace('.', '', $priceMatch[1]);
                    } else {
                        echo 'Error 3: Can\'t get product price of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                        flush();
                    }
                } else {
                    echo 'Error 4: Can\'t get product stock of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                    flush();

                    //Price
                    if (preg_match('~<div class="*boxprice"*>\s*<div class="*prices"*>\s*<span class="*new"*>([0-9\.]+)₫<\/span>\s*<span class="*line"*>([0-9\.]+)~', $content, $priceMatch)) {
                        $result['price'] = (int)str_replace('.', '', $priceMatch[1]);
                        $result['price_sale'] = (int)str_replace('.', '', $priceMatch[2]);
                    } elseif (preg_match('~<div class="*boxprice"*>\s*<div class="*prices"*>\s*<span class="*new"*>([0-9\.]+)~', $content, $priceMatch)) {
                        $result['price_sale'] = (int)str_replace('.', '', $priceMatch[1]);
                    } elseif (preg_match('~<span class="*new"*>([0-9\.]+)₫<\/span>\s*<span class="status tamhethang">~', $content, $priceMatch)) {
                        $result['price_sale'] = (int)str_replace('.', '', $priceMatch[1]);
                    } else {
                        echo 'Error 5: Can\'t get product price of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                        flush();
                    }
                }

                // Description
                if (preg_match('~<article class="*description"*>(.+?)<\/article>~s', $content, $descriptionMatch)) {
//                    echo $productLink;
//                    vdd($descriptionMatch[1]);
                    $result['description'] = $descriptionMatch[1];
                } else {
                    echo 'Error 6: Can\'t get product description of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                    flush();
                }

                // Brand Id
                if (preg_match('~<h3 class="*brand"*>(.+?)<\/h3>~s', $content, $brandMatch)) {
//                    vdd($brandMatch[0]);
                    if (preg_match('~<a href="*\/thuong-hieu-[^\s>"]+-(\d+)"*~', $brandMatch[1], $brandIdMatch)) {
                        $sql = "SELECT id FROM brand WHERE id = '$brandIdMatch[1]'";

                        if ($conn->query($sql)->num_rows > 0) {
                            $result['brand_id'] = $brandIdMatch[1];
//                            var_dump($brandId);
//                            die();
                        } else {
                            echo 'Error 7: Can\'t get product brand 3 of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                            flush();
                        }
                    } elseif (preg_match('~<span>(.+?)<\/span>~', $brandMatch[1], $brandNameMatch)) {

                        $brandName = html_entity_decode($brandNameMatch[1], ENT_COMPAT, 'UTF-8');
                        if ($brandName != 'Khác') {
//                        vdd($brandName);
                            $sql = "SELECT id, name FROM brand WHERE name = '$brandName'";

                            $brand = $conn->query($sql);
                            if ($brand->num_rows > 0) {
//                            vdd($brand);
                                $result['brand_id'] = $brand->fetch_assoc()['id'];
//                            vdd($brandId);

//                                echo $brandId . '<br>';
//                                flush();
                            } else {
                                echo 'Error 8: Can\'t find brand 4 in <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                                flush();
                            }
                        }
//                        die();
                    } else {
                        echo 'Error 9: Can\'t find brand 2 in <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                        flush();
                    }
                } else {
                    echo 'Error 10: Can\'t get product brand 1 of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                    flush();
                }

                // Category Id
                $sql = "SELECT id, category_id_vuivui FROM category WHERE category_id_vuivui = '$categoryId' LIMIT 1";
                $categorySelect = $conn->query($sql);
                if ($categorySelect->num_rows < 0) {
                    $categoryId = $categorySelect->fetch_assoc()['id'];
//                    vdd($categoryId);
                } else {
                    if (preg_match('~<nav class="flex bread">(.+?)<\/nav>~s', $content, $categoryListMatch)) {
                        if (preg_match('~<a href=([^\s]+) class="[^"]+"><h1~', $categoryListMatch[1], $urlCategoryMatch)) {
                            $urlCategory = 'https://www.vuivui.com' . $urlCategoryMatch[1];
//                            vdd($urlCategory);

                            $sql = "SELECT id, url FROM category WHERE url = '$urlCategory' LIMIT 1";
                            $categorySelect = $conn->query($sql);
                            if ($categorySelect->num_rows > 0) {
                                $result['category_id'] = $categorySelect->fetch_assoc()['id'];
//                                vdd($categoryId);
                            } else {
                                echo 'Error 11: Can\'t find url category of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                                flush();
                            }
                        } else {
                            echo 'Error 12: Can\'t find category of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                            flush();
                        }
                    } elseif (preg_match('~<ul class=breadcrumb>(.+?)<\/ul>~', $content, $categoryListMatch)) {
                        if (preg_match('~<a href=([^\s>]+)><h1>~', $categoryListMatch[1], $urlCategoryMatch)) {
                            $urlCategory = 'https://www.vuivui.com' . $urlCategoryMatch[1];
//                            vdd($urlCategory);

                            $sql = "SELECT id, url FROM category WHERE url = '$urlCategory' LIMIT 1";
                            $categorySelect = $conn->query($sql);
                            if ($categorySelect->num_rows > 0) {
                                $result['category_id'] = $categorySelect->fetch_assoc()['id'];
//                                vdd($categoryId);
                            } else {
                                echo 'Error 13: Can\'t find url category of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                                flush();
                            }
                        } else {
                            echo 'Error 14: Can\'t find category of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                            flush();
                        }
                    } else {
                        echo 'Error 15: Can\'t find category of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                        flush();
                    }
                }

                // Image and Image List
                if (preg_match('~<div class=item><img src=([^\s]+)~', $content, $imageMatch)) {
                    $result['image'] = $imageMatch[1];
//                    vdd($result['image']);

                    // Image List
                    $result['image_list'] = $imageMatch[1];
                    if (preg_match_all('~<div class=item><img class=[^\s]+ data-src=([^\s]+)~', $content, $imageListMatch)) {
                        foreach ($imageListMatch[1] as $item) {
                            $result['image_list'] = $result['image_list'] . ',' . $item;
                        }
//                        vdd($result['image_list']);
                    } else {
                        echo 'Error 19: Can\'t get image list of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                        flush();
                    }
                } else {
                    echo 'Error 18: Can\'t get image of product <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                    flush();
                }

                // Image List
            } else {
                echo 'Error 16: Can\'t get product name of <a target="_blank" href="' . $productLink . '">' . $productLink . '</a><br>';
                flush();
            }


            if (isset($result['name'])) {
                echo $result['name'] . '<br>';
                $sql1 = "INSERT IGNORE INTO product (name";
                $sql2 = "VALUES ('$result[name]'";

                if (isset($result['price'])) {
                    $sql1 = $sql1 . ', price';
                    $sql2 = $sql2 . ", '$result[price]'";
                }

                if (isset($result['price_sale'])) {
                    $sql1 = $sql1 . ', price_sale';
                    $sql2 = $sql2 . ", '$result[price_sale]'";
                }

                if (isset($result['description'])) {
                    $sql1 = $sql1 . ', description';
                    $sql2 = $sql2 . ", '$result[description]'";
                }

                if (isset($result['image'])) {
                    $sql1 = $sql1 . ', image';
                    $sql2 = $sql2 . ", '$result[image]'";
                }

                if (isset($result['image_list'])) {
                    $sql1 = $sql1 . ', image_list';
                    $sql2 = $sql2 . ", '$result[image_list]'";
                }

                if (isset($result['stock'])) {
                    $sql1 = $sql1 . ', stock';
                    $sql2 = $sql2 . ", '$result[stock]'";
                }

                if (isset($result['brand_id'])) {
                    $sql1 = $sql1 . ', brand_id';
                    $sql2 = $sql2 . ", '$result[brand_id]'";
                }

                if (isset($result['category_id'])) {
                    $sql1 = $sql1 . ', category_id';
                    $sql2 = $sql2 . ", '$result[category_id]'";
                }

                $sql = $sql1 . ') ' . $sql2 . ')';
                mysqli_query($conn, $sql);
                flush();
            }
//            $sql = "INSERT IGNORE INTO product (name, price, price_sale, description, image, image_list, stock, brand_id, category_id) VALUES ('$result[name]', '$result[price]', '$result[price_sale]', '$result[description]', '$result[image]', '$result[image_list]', '$result[stock]', '$result[brand_id]', '$result[category_id]')";
//            mysqli_query($conn, $sql);
//            flush();

//            if(isset($result['name'])){
//                echo 'Name: '.$result['name'];
//
//                if(isset($result['price'])){
//                    echo ', Price: '.$result['price'];
//                }
//
//                if(isset($result['price_sale'])){
//                    echo ', Price Sale: '.$result['price_sale'];
//                }
//
//                if(isset($result['description'])){
//                    echo ', Description: '.$result['description'];
//                }
//
//                if(isset($result['image'])){
//                    echo ', Image: '.$result['image'];
//                }
//
//                if(isset($result['image_list'])){
//                    echo ', Image List: '.$result['image_list'];
//                }
//
//                if(isset($result['stock'])){
//                    echo ', Stock: '.$result['stock'];
//                }
//
//                if(isset($result['brand_id'])){
//                    echo ', Brand Id: '.$result['brand_id'];
//                }
//
//                if(isset($result['category_id'])){
//                    echo ', Category Id: '.$result['category_id'];
//                }
//
//                echo '<br>';
//                flush();
//            }
        }
//        die();
    } else {
        echo 'Error 17: Can\'t get content category sitemap link <a target="_blank" href="' . $categoryLink->nodeValue . '">' . $categoryLink->nodeValue . '</a><br>';
        flush();
    }
}