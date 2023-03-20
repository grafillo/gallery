<?php
require_once __DIR__.'/settings.php';

try {
    $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD);
}
catch(Exception $e) {
    die("Ошибка: " . $e->getMessage());
}


$sql = 'CREATE DATABASE gallery_db';

try {
    mysqli_query($conn, $sql);
    echo "База данных успешно создана<br>";
}
catch(Exception $e){
   echo( 'Ошибка при создании базы данных: ' . $e->getMessage() . "<br>");
}


try {
    mysqli_select_db($conn, "gallery_db");
}
catch (Exception $e) {
    echo('Ошибка соединения с бд: '.  $e->getMessage(). "<br>");
}

$create_table = 'CREATE TABLE size (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    width INT NOT NULL,
    height INT NOT NULL,
    not_show VARCHAR(255))';

try {
    mysqli_query($conn, $create_table);
    echo "Таблица успешно создана<br>";
}
catch (Exception $e) {
    echo('Ошибка создания таблицы: '.  $e->getMessage(). "<br>");
}

$fill_table='INSERT INTO size VALUES (1,"big", 800, 600,"mobile"), 
(2, "med", 640 , 480,""), (3, "min", 320,240,""), (4, "mic", 150, 150,"pc")';

try {
    mysqli_query($conn, $fill_table);
    echo "Таблица успешно заполнена<br>";
}
catch (Exception $e) {
    echo('Ошибка заполнения таблицы: '.  $e->getMessage(). "<br>");
}



