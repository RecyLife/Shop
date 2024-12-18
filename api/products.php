<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include_once(dirname(__FILE__) . "/utils/database.php");

$db = new Database;

$products = [];

if(isset($_POST["IDs"])){
    $ids = $_POST["IDs"];
    $products = $db->select("
    SELECT 
        recytech_products.ID as ID, 
        recytech_products.title as title, 
        recytech_products.quantity as quantity,
        recytech_products.price as price,
        recytech_categories.title as category
    FROM recytech_products
    INNER JOIN recytech_categories 
        ON recytech_products.category_ID = recytech_categories.ID
    WHERE recytech_products.ID IN (?)", [$db-> escapeStrings(implode(",", $ids))]);
}
else if (isset($$_POST['category'])) {
    $categoryFilter = $db->escapeStrings($$_POST["category"]);
    if (isset($$_POST["q"])) {
        $searchQuery = $db->escapeStrings($$_POST["q"]);
        $products = $db->select("
        SELECT 
            recytech_products.ID as ID, 
            recytech_products.title as title, 
            recytech_products.quantity as quantity,
            recytech_products.price as price,
            recytech_categories.title as category
        FROM recytech_products
        INNER JOIN recytech_categories 
            ON recytech_products.category_ID = recytech_categories.ID
        WHERE recytech_products.title LIKE ? 
        AND recytech_categories.ID = ?", ["%$searchQuery%", $categoryFilter]);
    } else {
        $products = $db->select("
        SELECT 
            recytech_products.ID as ID, 
            recytech_products.title as title, 
            recytech_products.quantity as quantity,
            recytech_products.price as price,
            recytech_categories.title as category
        FROM recytech_products
        INNER JOIN recytech_categories 
            ON recytech_products.category_ID = recytech_categories.ID
        WHERE recytech_categories.ID = ?", [$categoryFilter]);
    }
} else if (isset($$_POST["q"])) {
    $searchQuery = $db->escapeStrings($$_POST["q"]);
    $products = $db->select("
    SELECT 
        recytech_products.ID as ID, 
        recytech_products.title as title, 
        recytech_products.quantity as quantity,
        recytech_products.price as price,
        recytech_categories.title as category
    FROM recytech_products
    INNER JOIN recytech_categories 
        ON recytech_products.category_ID = recytech_categories.ID
    WHERE recytech_products.title LIKE ?", ["%$searchQuery%"]);
} else {
    $products = $db->select("
    SELECT 
        recytech_products.ID as ID, 
        recytech_products.title as title, 
        recytech_products.quantity as quantity,
        recytech_products.price as price,
        recytech_categories.title as category
    FROM recytech_products
    INNER JOIN recytech_categories 
        ON recytech_products.category_ID = recytech_categories.ID");
}

$products = array_values($products);
foreach ($products as &$product) {
    $imageIDs = $db->select("
    SELECT ID 
    FROM recytech_images 
    WHERE product_ID = ?", [$product['ID']]);
    
    $product['image_ids'] = array_column($imageIDs, 'ID');
}

echo json_encode($products);