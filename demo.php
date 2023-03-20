<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
    />

</head>
<body>
<script>
    Fancybox.bind('[data-fancybox]', {

    });
</script>

<?php
require_once __DIR__.'/settings.php';

function check_mobile_device()
{
    $mobile_agent_array = array(
        'ipad', 'iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod',
        'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone',
        'blackberry', 'playstation portable', 'tablet browser'
    );
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    foreach ($mobile_agent_array as $value) {
        if (strpos($agent, $value) !== false) {
            return true;
        }
    }
    return false;
}


if (check_mobile_device()) {
    $device = 'mobile';
} else {
    $device = 'pc';
}


try {
    $DBH = new PDO("mysql:host=".HOSTNAME.";dbname=gallery_db", USERNAME, PASSWORD);
    $STH = $DBH->prepare("SELECT * FROM size WHERE not_show != ?");
    $STH->execute([$device]);
    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $row = $STH->fetchAll();

} catch (Exception $e) {
    die ('Ошибка работы с бд.');
}


$sizeArray = array_column($row, 'name');

$fileArray = array_diff(scandir(GALLERY.'/'), array('.', '..'));

$imageCount = 0;
foreach ($fileArray as $file) {

    if ($imageCount == QUANTITY_IMG) {
        break;
    }
    echo "<a data-fancybox=gallery$imageCount href=".GALLERY."/$file>
    <img src=".GALLERY."/$file  height=150 alt= />
    </a>
    <div style=display:none>";


    foreach ($sizeArray as $size) {

        file_get_contents(HTTP.'generator.php?name='.$file.'&size='.$size);

        echo "<a data-fancybox=gallery$imageCount data-caption=$size href=".CACHE."/$size"."_"."$file>
        <img src=".CACHE."/$size"."_"."$file />
    </a>";

    }

    echo "</div></body></html>";
    $imageCount++;
}




