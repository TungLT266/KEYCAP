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

$url = 'https://www.vuivui.com/sitemap.xml';
$content = getContent($url);
//vdd($content);

$dom = new DOMDocument();
$dom->loadXML($content);
$categoryList = $dom->getElementsByTagName('loc');

$error = [];

foreach ($categoryList as $category) {
    $error[] = $category->nodeValue;
}

while ($error != []) {
    echo 'Link error: ' . sizeof($error) . '<br>';
    flush();

    $categogySitemapList = $error;
    $error = [];

    foreach ($categogySitemapList as $categogySitemap) {
        $content = getContent($categogySitemap);

        if (preg_match('~sitemap_(\d+)\.xml$~', $categogySitemap, $categoryIdMatch)) {
            $categoryId = $categoryIdMatch[1];
//        vdd($categoryId);
        } else {
            echo 'Error1 : Can\'t get category id <a target="_blank" href="' . $categogySitemap . '">' . $categogySitemap . '</a><br>';
            flush();
        }

        if (preg_match('~<loc>\s*(https:\/\/www\.vuivui\.com\/.+?)\/~', $content, $categoryLinkMatch)) {
            $content = getContent($categoryLinkMatch[1]);
//        $content = getContent('https://www.vuivui.com/do-dung-gia-dinh');
//        vdd($content);

            if (preg_match('~<nav class="flex bread">(.+?<\/h1><\/a>).*?<\/nav>~s', $content, $categoryListMatch)) {
//            vdd($categoryListMatch[1]);
                if (preg_match_all('~<a .+?<\/a>~', $categoryListMatch[1], $categoryMatch)) {
                    $categoryCount = sizeof($categoryMatch[0]);
//                vdd($categoryMatch[0][0]);

                    if (preg_match('~href="\/".+?Trang chủ~', $categoryMatch[0][0])) {
                        if ($categoryCount == 2) {
                            if (preg_match('~<h1.+?>(.+?)<\/h1>~', $categoryMatch[0][1], $nameMatch)) {
                                $name = html_entity_decode($nameMatch[1], ENT_COMPAT, 'UTF-8');

                                echo $name . ': ' . $categoryLinkMatch[1] . '<br>';
                                $sql = "INSERT INTO category (name, url, parent_id, level, category_id_vuivui) VALUES ('$name', '$categoryLinkMatch[1]', '0', '1', '$categoryId')";
                                mysqli_query($conn, $sql);
                                flush();
//                        die();
                            } else {
                                echo 'Error 2: Can\'t get category end <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                                flush();
                            }
                        } elseif ($categoryCount > 2) {
                            $categoryParent = [];

                            for ($i = 1; $i < $categoryCount; $i++) {
                                if (preg_match('~<a href=(.+?) .+?><h1.+?>(.+?)<\/h1><\/a>~', $categoryMatch[0][$i], $nameLinkMatch)) {
                                    $urlCategory = 'https://www.vuivui.com' . $nameLinkMatch[1];
                                    $name = html_entity_decode($nameLinkMatch[2], ENT_COMPAT, 'UTF-8');
                                    $level = sizeof($categoryParent) + 1;
                                    $parentUrl = end($categoryParent)['url'];

                                    echo $name . ': ' . $urlCategory . '<br>';
                                    $sql = "INSERT INTO category (name, url, parent_url, level, category_id_vuivui) VALUES ('$name', '$urlCategory', '$parentUrl', '$level', '$categoryId')";
                                    mysqli_query($conn, $sql);
                                    flush();
//                            die();
                                } elseif (preg_match('~<a href=(.+?) .+?>(.+?)<\/a>~', $categoryMatch[0][$i], $nameLinkMatch)) {
                                    $temp = [];
                                    $temp['url'] = 'https://www.vuivui.com' . $nameLinkMatch[1];
                                    $temp['name'] = html_entity_decode($nameLinkMatch[2], ENT_COMPAT, 'UTF-8');
                                    $categoryParent[] = $temp;
                                } else {
                                    echo 'Error 3: Can\'t name and link category <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                                    flush();
                                }
                            }
//                    vdd($categoryParent);

                            foreach ($categoryParent as $index => $item) {
                                if ($index == 0) {
//                            vdd($item);
                                    echo $item['name'] . ': ' . $item['url'] . '<br>';
                                    $sql = "INSERT IGNORE INTO category (name, url, parent_id, level) VALUES ('$item[name]', '$item[url]', '0', '1')";
                                    mysqli_query($conn, $sql);
                                    flush();
                                } else {
                                    $level = $index + 1;
                                    $parentUrl = $categoryParent[$index - 1]['url'];

                                    echo $item['name'] . ': ' . $item['url'] . '<br>';
                                    $sql = "INSERT IGNORE INTO category (name, url, parent_url, level) VALUES ('$item[name]', '$item[url]', '$parentUrl', '$level')";
                                    mysqli_query($conn, $sql);
                                    flush();
                                }
                            }
//                    die();
                        } else {
                            echo 'Error 4: Count category <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                            flush();
                        }
                    } else {
                        echo 'Error 5: category is not Trang chủ<a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                        flush();
                    }
                } else {
                    echo 'Error 6: Category is empty <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                    flush();
                }
            } elseif (preg_match('~<ul class=breadcrumb>(.+?)<\/ul>~', $content, $categoryListMatch)) {
//            vdd($categoryListMatch);
                if (preg_match_all('~<a .+?<\/a>~', $categoryListMatch[1], $categoryMatch)) {
                    $categoryCount = sizeof($categoryMatch[0]);
//                vdd($categoryMatch[0]);
                    if ($categoryCount > 1) {
                        $categoryParent = [];

                        foreach ($categoryMatch[0] as $categoryItem) {
//                        vdd($categoryItem);
                            if (preg_match('~<a href=(.+?)><h1>(.+?)<\/h1>~', $categoryItem, $nameLinkMatch)) {
                                $urlCategory = 'https://www.vuivui.com' . $nameLinkMatch[1];
                                $name = html_entity_decode($nameLinkMatch[2], ENT_COMPAT, 'UTF-8');
                                $level = sizeof($categoryParent) + 1;
                                $parentUrl = end($categoryParent)['url'];

                                echo $name . ': ' . $urlCategory . '<br>';
                                $sql = "INSERT INTO category (name, url, parent_url, level, category_id_vuivui) VALUES ('$name', '$urlCategory', '$parentUrl', '$level', '$categoryId')";
                                mysqli_query($conn, $sql);
                                flush();
//                            die();
                            } elseif (preg_match('~<a href=(.+?)>(.+?)<\/a>~', $categoryItem, $nameLinkMatch)) {
                                $temp = [];
                                $temp['url'] = 'https://www.vuivui.com' . $nameLinkMatch[1];
                                $temp['name'] = html_entity_decode($nameLinkMatch[2], ENT_COMPAT, 'UTF-8');
                                $categoryParent[] = $temp;
                            } else {
                                echo 'Error 7: Can\'t name and link category khu mỹ phẩm <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                                flush();
                            }
                        }
//                    vdd($categoryParent);

                        foreach ($categoryParent as $index => $item) {
                            if ($index == 0) {
//                            vdd($item);
                                echo $item['name'] . ': ' . $item['url'] . '<br>';
                                $sql = "INSERT IGNORE INTO category (name, url, parent_id, level) VALUES ('$item[name]', '$item[url]', '0', '1')";
                                mysqli_query($conn, $sql);
                                flush();
                            } else {
                                $level = $index + 1;
                                $parentUrl = $categoryParent[$index - 1]['url'];

                                echo $item['name'] . ': ' . $item['url'] . '<br>';
                                $sql = "INSERT IGNORE INTO category (name, url, parent_url, level) VALUES ('$item[name]', '$item[url]', '$parentUrl', '$level')";
                                mysqli_query($conn, $sql);
                                flush();
                            }
                        }
//                    die();
                    } else {
                        echo 'Error 8: Count category khu mỹ phẩm <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                        flush();
                    }
                } else {
                    echo 'Error 9: Category of khu mỹ phẩm is empty <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                    flush();
                }
            } else {
                echo 'Error 10: Can\'t get Category <a target="_blank" href="' . $categoryLinkMatch[1] . '">' . $categoryLinkMatch[1] . '</a><br>';
                flush();
            }
        } else {
            $error[] = $categogySitemap;

            echo 'Error 11: Can\'t get content <a target="_blank" href="' . $categogySitemap . '">' . $categogySitemap . '</a><br>';
            flush();
        }
    }
}
