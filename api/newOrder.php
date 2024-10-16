<?php

$ids = explode(",", $_POST["ids"]);
$quantities = explode(",", $_POST["quantities"]);

if(count($ids) != count($quantities)) {
    echo json_encode(array("error"=> "count(ids) != count(quantities)"));
    exit();
}
if(count($ids) < 1) {
    echo json_encode(array("error"=> "count(ids) < 1"));
    exit();
}
$db = new Database;


$name = $db-> escapeStrings($_POST["name"]);
$email = $db-> escapeStrings($_POST["email"]);
$phone = $db-> escapeStrings($_POST["phone"]);
$address = $db-> escapeStrings($_POST["address"]);
$postal_code = $db-> escapeStrings($_POST["postal_code"]);
$city = $db-> escapeStrings($_POST["city"]);

$db -> query("
    INSERT INTO recytech_orders (name, email, phone, address, postal_code, city)
    VALUES ('$name', '$email', '$phone', '$address', '$postal_code', '$city')");

$orderId = $db->getLastInsertedID();

for ($i=0; $i < count($ids); $i++) { 
    $db -> query("
        INSERT INTO recytech_order_products (order_ID, product_ID, quantity)
        VALUES ('$orderId', '$ids[$i]', '$quantities[$i]')");
}