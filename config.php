<?php
    $host = "206.189.94.183";
    $username = "kapit20";
    $pass = "1sampai8";
    $db = "v_zelenyy";


    $con;
        try {
            $con = new PDO("mysql:host=$host;dbname=$db;charset=utf8;", $username, $pass);

            if ($con) {
                $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                die("Failed connect db");
            }
        } catch (Exception $e) {
            die("Failed connect db : " .$e->getMessage());
        }
?>