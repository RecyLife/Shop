<?php

class Database
{
    private $conn;

    public function __construct()
    {
        include_once(dirname(__FILE__) . "/secrets.php");
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_HOST_PORT);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function select($sql_prompt, $params = [])
    {
        $stmt = $this->conn->prepare($sql_prompt);
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public function query($sql_prompt, $params = [])
    {
        if (!is_array($params)) {
            die("Params must be an array");
        }
    
        $stmt = $this->conn->prepare($sql_prompt);
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
    
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
    
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    
        $result = $stmt->affected_rows;
        $stmt->close();
        return $result;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }

    public function escapeStrings($str)
    {
        return $this->conn->real_escape_string($str);
    }
    
    public function getLastInsertedID() {
        return $this->conn->insert_id;
        
    }
}