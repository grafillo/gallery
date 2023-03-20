<?php
require_once __DIR__.'/settings.php';
require_once __DIR__.'/thumbs.php';


if (!isset($_GET['name']) || !isset($_GET['size'])) {
    die("Не задан размер или название.");
}


if (!file_exists(GALLERY.'/'.$_GET['name'])) {
    die("Файла с таким именем не существует");
}

list($width, $height) = getimagesize(GALLERY.'/'.$_GET['name']);

try {
    $DBH = new PDO("mysql:host=".HOSTNAME.";dbname=gallery_db", USERNAME, PASSWORD);
    $STH = $DBH->prepare("SELECT * FROM size WHERE name = ?");
    $STH->execute([$_GET['size']]);
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $row = $STH->fetch();
}catch (Exception $e){
    die ('Ошибка работы с бд.');
}


if (!empty($row)) {
    $newWidth = $row['width'];
    $newHeight = $row['height'];
    $name = $row['name'];
} else {
    die("Нет такого типа картинки");
}

if (file_exists(CACHE.'/'."$name"."_".$_GET['name'])) {
    die("Файл уже создан");
}

if ($width > $height) {
    $width = $newWidth;
    $height = 0;
} else {
    $width = 0;
    $height = $newHeight;
}

$image = new Thumbs(GALLERY.'/'.$_GET['name']);
$image->resize($width, $height);
$image->saveJpg(CACHE.'/'."$name"."_".$_GET['name']);

