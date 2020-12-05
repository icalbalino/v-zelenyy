<?php
        require_once('../functions.php');
        $items = select_items();
        $carts = [];
        if(isset($_SESSION['carts'])){
            $carts = $_SESSION['carts'];
            echo "carts";
        }
        if(isset($_POST['addToCart'])){   
            $item = select_items($_POST['id'])[0];
            array_push($carts, $item);
            $_SESSION['carts'] = $carts;
        }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/3b79ccf7db.js" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/datatables.min.js"></script>
    
    <link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-Zelenny</title>

</head>

<body>
    <div class="d-flex vh-100">
        <div class="vh-100 side-menu-container d-flex flex-column justify-content space-between" id='side-menu'>
            <div class="menu-title">Logo disini</div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light"><i class="fas fa-home col-2"></i>
                    <span class="col">Dashboard</span></a>
                <a href="#" class="list-group-item list-group-item-action bg-light"><i
                        class="fas fa-money-check col-2"></i> <span class="col">Transaksi</span></a>
                <a href="#" class="list-group-item list-group-item-action bg-light"><i class="fas fa-history col-2"></i>
                    <span class="col">History</span></a>
            </div>
        </div>

        <div class="col container-fluid content">
            <div class="d-flex justify-content-center align-items-center vh-100 mh-100">
                <!-- <button class="btn btn-success">Transaksi Baru</button> -->
                <div class="card" style="width: 32em; max-height: 25vh; overflow-y: scroll">
                    <div class="list-group list-group-flush" id="cart">
                        <div class="list-group-item d-flex align-items-center">
                            <div class="col">Brokoli</div>
                            <div class="col">10</div>
                            <div class="col">Rp 10.000</div>
                            <button style="background: none; border: none"><i
                                    class="fas fa-trash text-danger"></i></button>
                        </div>
                        <?php 
                            foreach($carts as $cart){
                              echo '<div class="list-group-item d-flex align-items-center">
                                        <div class="col">'.$cart["nama"].'</div>
                                        <div class="col">'.$cart["stok"].'</div>
                                        <div class="col">'.$cart["harga"].'</div>
                                        <button style="background: none; border: none"><i
                                                class="fas fa-trash text-danger"></i></button>
                                    </div>';
                            }
                        ?>
                    </div>
                    <div class="card-body" style="margin-bottom: 24px">
                        <button class="btn btn-success float-right" data-toggle="modal" data-target="#modal-item-list">Tambah Item</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-item-list" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body vh-50 mh-50">
                    <table class="table" id="item-table" data-page-length='5'>
                        <thead>
                            <th>Id</th>
                            <th>Nama</th>
                            <th>Stock</th>
                            <th>Harga Satuan</th>
                        </thead>
                    <tbody>
                        <?php
                            foreach ($items as $item) {
                                echo '
                                    <tr>
                                        <form action="transaksi.php" method="POST">
                                            <td><input hidden name="id" value="'.$item['id'].'">'.$item['id'].'</td>
                                            <td><input class="btn btn-link" type="submit" name="addToCart" value="'.$item['nama'].'"></td>
                                            <td>'.$item['stok'].'</td>
                                            <td>'.$item['harga'].'</td>
                                        </form>
                                    </tr>
                                ';
                            }
                        ?>
                    </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Masukkan keranjang</button>
                </div>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function() {
            $('#item-table').DataTable();
        });

        var carts = [{
            "name": "brokoli",
            "qty": 10,
            "price": 10000
        }]

        function addItemToCart(e, data){
            console.log("add item", data)
            array_push(carts, data)
            renderList()
        }

        function renderList() {
            var html = "";
            for (var i = 0; i < carts.length; i++) {
                console.log(i)
                var cart = carts[i]
                // console.log(carts)
                html += '<div class="list-group-item d-flex align-items-center">' +
                    '<div class="col">' + cart.name + '</div>' +
                    '<div class="col">' + cart.qty + '</div>' +
                    '<div class="col">Rp' + (cart.qty * cart.price) + '</div>' +
                    '<button style="background: none; border: none"><i class="fas fa-trash text-danger"></i></button>' +
                    '</div>'
            }
            console.log(html)
            $('#cart').append(html)
        }
    </script>
</body>

</html>