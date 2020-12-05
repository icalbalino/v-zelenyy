<?php
    session_start();
    require_once('config.php');
   function select_items() {
    global $con;

    $hasil = array();
    // $sql = "SELECT * FROM tbl_data WHERE NIM = :user";
    $sql = "SELECT * FROM item";

    try {
        $stmt = $con->prepare($sql);
        // if ($user != "") $stmt->bindValue(':user', $user, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $stmt->fetchAll();
            
            if ($rs != null) {
                $i = 0;
                $hasil = $rs;
                // foreach ($rs as $val) {
                //     $hasil[$i] = $val;                    
                //     $hasil[$i]['id'] = $val['id'];
                //     $hasil[$i]['nama'] = $val['nama'];
                //     $hasil[$i]['stock'] = $val['IPK'];
                //     $hasil[$i]['harga'] = $val['Asal'];
                //     $i++;
                // }
            }
        }
    } catch(Exception $e) {
        echo 'Error select_data : '.$e->getMessage();
    }

    return $hasil;
    }
    function addItemToCart($item){
        $items = $_SESSION['items'];
        array_push($items, $item);
        $_SESSION['items'];
        header("refresh: 1");
    }
