<?php

require_once("config.php");


// Use reference values (for call_user_func_array method)
function _refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}


class Database {
    var $connection;
    
    // Constructor
    function Database() {
        global $cfg_database;
        
        // Build the connection
        $db = new mysqli($cfg_database["server"], $cfg_database["login"],
                         $cfg_database["password"], $cfg_database["database"]);
        if($db->connect_error) {
            die("Database connection failed");
        }
        $this->connection = $db;
    }
    
    // Generic method to perform sql requests
    function _request($sql) {
        return $this->connection->query($sql);
    }
    
    // Perform a simple request that returns something
    function get($sql) {
        $request = $this->_request($sql);
        if(!$request)
            die($connection->error);
        
        $fields = $request->fetch_fields();
        while($row = $request->fetch_array(MYSQLI_ASSOC)) {
            $result[] = $row;
        }
        $data = null;
        switch($request->num_rows) {
            case 0:
                $data = array();
                break;
            default:
                $data = $result;
        }
        
        $request->free();
        return array(
            "fields" => $fields,
            "data" => $data
        );
    }
    
    // Perform a simple request
    function set($sql) {
        echo $sql;
        $request = $this->_request($sql);
        if($request) {
            return null;
        }
        return $connection->error;
    }
    
    // Perform a secured request
    function query($sql, $types, $values) {    
        if($stmt = $this->connection->prepare($sql)) {
            // Build the request
            $params = array($types);
            foreach($values as $key => $value) {
                array_push($params, $value);
            }
            call_user_func_array(array($stmt, "bind_param"), _refValues($params));

            // Execution and handle errors
            if(!($stmt->execute())) {
                die("Error during execute : (" . $stmt->errno . ") " . $stmt->error);
            }
            $stmt->close();
        }
        return 1;
    }
    
    // Export as CSV format
    function export($table) {
        $result = $this->get("SELECT * FROM $table;");
        $out = fopen("php://output", 'w');
        
        // Column titles
        $fields = "";
        foreach($result["fields"] as $field)
            $fields .= $field->name . ",";
        fwrite($out, trim($fields, ","));
        fwrite($out, "<br/>");
        
        // Data
        foreach($result["data"] as $row)
            fputcsv($out, $row);
        
        fclose($out);
    }
    
    // Close the transaction when done
    function close() {
        $this->connection->close();
    }
}

?>