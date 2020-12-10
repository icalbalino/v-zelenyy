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

    function selectTrxes($kasir_id = ""){
        global $con;

        $hasil = array();
        $sql = "SELECT * FROM item";
        if($kasir_id!=""){
            $sql = "SELECT trx.id, trx.kasir_id, user.nama, trx.tanggal, sum(subtotal) as 'total' FROM trx join `detail_trx` join `user` where user.id = trx.kasir_id and detail_trx.trx_id = trx.id and trx.kasir_id = :kasir_id group by trx.id";
        }else{
            $sql = "SELECT trx.id, trx.kasir_id, user.nama, trx.tanggal, sum(subtotal) as 'total' FROM trx join `detail_trx` join `user` where user.id = trx.kasir_id and detail_trx.trx_id = trx.id group by trx.id";
        }
        try {
            $stmt = $con->prepare($sql);
            if ($kasir_id != "") $stmt->bindValue(':kasir_id', $kasir_id, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $rs = $stmt->fetchAll();
                
                if ($rs != null) {
                    $i = 0;
                    // $hasil = $rs;
                    foreach ($rs as $item) {
                        $item['detail_trx'] = selectDetailTrxes($item['id']);
                        $hasil[$i] = $item;
                        $i++;
                    }
                }
            }else{
                echo "err";
            }
        } catch(Exception $e) {
            echo 'Error select_data : '.$e->getMessage();
        }

        return $hasil;
    }


    function selectDetailTrxes($trxId = ""){
        global $con;

        $hasil = array();
        $sql = "SELECT * FROM detail_trx join item where detail_trx.item_id = item.id and detail_trx.trx_id = :trx_id";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':trx_id', $trxId, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $rs = $stmt->fetchAll();
                
                if ($rs != null) {
                    $i = 0;
                    $hasil = $rs;
                }
            }else{
                echo "err";
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