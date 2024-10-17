<?php
header('Content-Type: application/json');

include_once(dirname(__FILE__) . "/utils/database.php");

$db = new Database;

$places = $db -> select("SELECT * from recytech_places");

echo json_encode(array_values($places));