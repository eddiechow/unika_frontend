<?php

class Database {

    public static function getConnection(){
        mysqli_report(MYSQLI_REPORT_STRICT);
        $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $conn->set_charset("utf8");
        return $conn;        
    }
    
    public static function getDbErrorMsg(){
        return "Database error.";
    }
    
}
