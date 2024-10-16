<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include_once(dirname(__FILE__) . "/utils/database.php");

$db = new Database;

if(!isset(($_GET["id"]))) {
    echo json_encode(array("error" => "ID is not specified"));
    exit();
}

$id = $db -> escapeStrings($_GET["id"]);

$withImages = false;
if(isset($_GET["images"])){
    $withImages = $_GET["images"] == "1";
}

$product = $db -> select("
SELECT 
    recytech_products.ID as ID, 
    recytech_products.title as title,
    recytech_products.quantity as quantity,
    recytech_products.price as price,
    recytech_categories.title as category,
    recytech_products.category_ID
from recytech_products
INNER JOIN recytech_categories 
    ON recytech_products.category_ID = recytech_categories.ID
WHERE recytech_products.ID = ?", [$id]);


if(count($product) < 1) {
    echo json_encode(array());
    exit();
}

$specifications = $db -> select("SELECT * from recytech_specifications WHERE product_ID = ?", [$id]);
$product[0]["specifications"] = array_values($specifications);

if($withImages) {
    $imagesResult = array();
    $images = $db -> select("SELECT * from recytech_images WHERE product_ID = ?", [$id]);

    for ($i=0; $i < count($images); $i++) { 
        array_push($imagesResult, base64_encode($images[$i]["image_"]));
    }

    $product[0]["images"] = array_values($imagesResult);
}


echo json_encode($product[0]);