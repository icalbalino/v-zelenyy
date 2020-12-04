<?php
    $host = "206.189.94.183";
    $username = "zeleny";
    $pass = "zeleny!@#";
    $db = "v_zelenyy";


    $con;
        try {
            $con = new PDO("mysql:host=$host;dbname=$db;charset=utf8;", $username, $pass);

            if ($con) {
                $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                var_dump("Konseksi oke boss");
                // die("Failed connect db");
            }
        } catch (Exception $e) {
            var_dump("Konseksi gagal boss");
            // die("Failed connect db : " .$e->getMessage());
        }
?>