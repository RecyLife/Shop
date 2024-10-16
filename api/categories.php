<?php
header('Content-Type: application/json');

include_once(dirname(__FILE__) . "/utils/database.php");

$db = new Database;

$categories = $db -> select("SELECT * from recytech_categories");

echo json_encode(array_values($categories));