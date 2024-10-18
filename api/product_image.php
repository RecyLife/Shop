<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(dirname(__FILE__) . "/utils/database.php");


$db = new Database;
$id = intval($db -> escapeStrings($_GET["pdocutID"]));
$quality = 50;
if(isset( $_GET["quality"] )) {
    $quality = intval($_GET["quality"]);
}
$imageID = intval($db -> escapeStrings($_GET["imageID"]));


$image = $db -> select("SELECT image_ FROM recytech_images WHERE product_ID = ? AND image_ID = ?", [$id, $imageID]);

if(count($image) < 1) {
    exit();
}
$gd = imagecreatefromstring($image[0]["image_"]);
ob_start();
imagejpeg($gd, null, $quality);
$imageResult = ob_get_clean();
echo $imageResult;