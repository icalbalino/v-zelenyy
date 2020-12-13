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

    function select_kasirs($id="") {
        global $con;

        $hasil = array();
        $sql = "SELECT * FROM user where role='kasir'";
        if($id!=""){
            $sql = "SELECT * FROM item where role='kasir' and id = :id";
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

    function auth($role, $redirect="../login.php"){
        if(!isset($_SESSION['user'])){
            Header("Location: ".$redirect);
        }
        $user = $_SESSION['user'];
        if($user['role'] != $role){
            Header("Location: ".$redirect);
        }
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
            $sql = "SELECT trx.id, trx.kasir_id, user.nama, trx.tanggal, sum(subtotal) as 'total' FROM trx join `detail_trx` join `user` where user.id = trx.kasir_id and detail_trx.trx_id = trx.id and trx.kasir_id = :kasir_id group by trx.id order by tanggal DESC";
        }else{
            $sql = "SELECT trx.id, trx.kasir_id, user.nama, trx.tanggal, sum(subtotal) as 'total' FROM trx join `detail_trx` join `user` where user.id = trx.kasir_id and detail_trx.trx_id = trx.id and user.role = 'kasir' group by trx.id order by tanggal DESC";
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
    $now = date('Y-m-d H:i:s', strtotime('6 hour'));
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
        $item = select_items($item_id)[0];
        updateItem($item['id'], $item['nama'], $item['stok']-$qty, $item['harga']);
        return true;
    }
}

function insertItem($nama,$stok,$harga){
    global $con;
    $insert=$con->prepare("insert into item (nama,stok,harga) values (:nama,:stok,:harga)");
    $insert->BindParam(':nama',$nama);
    $insert->BindParam(':stok',$stok);
    $insert->BindParam(':harga',$harga);
    $insert->execute();
    if($insert->rowCount()==0){
        return false;
    }
    else{
        return true;
    }
}

function updateItem($id, $nama, $stok, $harga){
    global $con;
    $update=$con->prepare("update item set nama=:nama, stok=:stok, harga=:harga where id=:id");
    $update->BindParam(':id',$id);
    $update->BindParam(':nama',$nama);
    $update->BindParam(':stok',$stok);
    $update->BindParam(':harga',$harga);
    $update->execute();
    if($update->rowCount()==0){
        return false;
    }
    else{
        return true;
    }
}

function insertKasir($nama,$username,$password){
    global $con;
    $insert=$con->prepare("insert into user (nama,username,password,role) values (:nama,:username,:password, 'kasir')");
    $insert->BindParam(':nama',$nama);
    $insert->BindParam(':username',$username);
    $insert->BindParam(':password',$password);
    $insert->execute();
    if($insert->rowCount()==0){
        return false;
    }
    else{
        return true;
    }
}

function updateKasir($id, $nama, $username, $password){
    global $con;
    $update=$con->prepare("update user set nama=:nama, username=:username, password=:password where id=:id");
    $update->BindParam(':id',$id);
    $update->BindParam(':username',$username);
    $update->BindParam(':nama',$nama);
    $update->BindParam(':password',$password);
    $update->execute();
    if($update->rowCount()==0){
        return false;
    }
    else{
        return true;
    }
}

function countTrx($kasir_id=""){
    global $con;

    $hasil = array();
    if($kasir_id != null){
        $sql = "select count(*) as count from trx where date_format(tanggal, '%Y-%m-%d') = curdate()";
    }else{
        $sql = "select count(*) as count from trx where date_format(tanggal, '%Y-%m-%d') = curdate() and trx.kasir_id=:kasir";
    }
    try {
        $stmt = $con->prepare($sql);
        if($kasir_id!= null)
            $stmt->bindValue(':kasir', $kasir_id, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $stmt->fetchAll();
            
            if ($rs != null) {
                return $rs[0]['count'];
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

function sumSubtotal($kasir_id=""){
    
    global $con;

    $hasil = array();
    if($kasir_id==""){
        $sql = "select sum(s.subtotal) as subtotal from
(SELECT sum(subtotal) as subtotal FROM `trx` join detail_trx where trx.id = detail_trx.trx_id and date_format(trx.tanggal, '%Y-%m-%d') = curdate() group by trx.id) as s";
    }else{
        $sql = "select sum(s.subtotal) as subtotal from
(SELECT sum(subtotal) as subtotal FROM `trx` join detail_trx where trx.id = detail_trx.trx_id and trx.kasir_id = :kasir and date_format(trx.tanggal, '%Y-%m-%d') = curdate() group by trx.id) as s";
    }
    
    try {
        $stmt = $con->prepare($sql);
        if($kasir_id!=""){
            $stmt->bindValue(':kasir', $kasir_id, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $stmt->fetchAll();
            if ($rs != null) {
                return $rs[0]['subtotal'];
                $i = 0;
                $hasil = $rs;
            }
        }else{
            echo "err";
        }
    } catch(Exception $e) {
        echo 'Error select_data : '.$e->getMessage();
    }

    return 0;
}

function sumItem($kasir_id=""){
    
global $con;

    $hasil = array();
    if($kasir_id==""){
        $sql = "select sum(s.qty) qty from
(SELECT sum(dt.qty) qty FROM `trx` t join detail_trx dt where dt.trx_id = t.id and date_format(t.tanggal, '%Y-%m-%d') = curdate() group by t.id) s";
    }else{
        $sql = "select sum(s.qty) qty from
(SELECT sum(dt.qty) qty FROM `trx` t join detail_trx dt where dt.trx_id = t.id and date_format(t.tanggal, '%Y-%m-%d') = curdate() and t.kasir_id = :kasir group by t.id) s";
    }
    
    try {
        $stmt = $con->prepare($sql);
        if($kasir_id!=""){
            $stmt->bindValue(':kasir', $kasir_id, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $stmt->fetchAll();
            if ($rs != null) {
                return $rs[0]['qty'];
                $i = 0;
                $hasil = $rs;
            }
        }else{
            echo "err";
        }
    } catch(Exception $e) {
        echo 'Error select_data : '.$e->getMessage();
    }

    return 0;
}