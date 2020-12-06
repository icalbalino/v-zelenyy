<?php
    session_start();
    require_once('config.php');
   function select_items($id="") {
        global $con;

        $hasil = array();
        $sql = "SELECT * FROM item";
        if($id!=""){
            $sql = "SELECT * FROM item where id = :id";
        }
        try {
            $stmt = $con->prepare($sql);
            if ($id != "") $stmt->bindValue(':id', $id, PDO::PARAM_STR);

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

    function login($username, $password){
        global $con;

        $hasil = array();
        $sql = "SELECT * FROM user where username = :username AND `password` = :pass";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':pass', $password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $rs = $stmt->fetchAll();
                
                if ($rs != null) {
                    if(count($rs)>0){
                        $user = $rs[0];
                        $_SESSION['user'] = $user;
                        if($user['role'] == 'kasir'){
                            header("Location: kasir/dashboard.php");
                        }else{
                            header("Location: admin/dashboard.php");
                        }
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        } catch(Exception $e) {
            echo 'Error select_data : '.$e->getMessage();
        }
        return $hasil;    
    }

function insertTrx(){
    global $con;
    $user = $_SESSION['user'];
    $now = date('Y-m-d');
    $insert=$con->prepare("insert into trx (kasir_id,tanggal) values (:kasir_id,:tanggal)");
    $insert->BindParam(':kasir_id',$user['id']);
    $insert->BindParam(':tanggal',$now);
    $insert->execute();
    if($insert->rowCount()==0){
        return false;
    }
    else{
        $trxId = $con->lastInsertId();
        $carts = $_SESSION['carts'];
        foreach ($carts as $cart) {
            insertDetailTrx($trxId, $cart['id'], ($cart['qty']*$cart['harga']), $cart['qty']);
        }
        $_SESSION['carts'] = [];
        return true;
    }
}

function insertDetailTrx($trx_id,$item_id,$subtotal,$qty){
    global $con;
    $user = $_SESSION['user'];
    $insert=$con->prepare("insert into detail_trx (trx_id,item_id,subtotal,qty) values (:trx_id,:item_id,:subtotal,:qty)");
    $insert->BindParam(':trx_id',$trx_id);
    $insert->BindParam(':item_id',$item_id);
    $insert->BindParam(':subtotal',$subtotal);
    $insert->BindParam(':qty',$qty);
    $insert->execute();
    if($insert->rowCount()==0){
        return false;
    }
    else{
        return true;
    }
}