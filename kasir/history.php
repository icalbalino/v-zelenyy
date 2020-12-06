<?php
        require_once('../functions.php');
        $trxes = selectTrxes($_SESSION['user']['id']);
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

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/datatables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" -->
        <!-- rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css"> -->
    <script type="text/javascript"
        src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
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
                <a href="dashboard.php" class="list-group-item list-group-item-action bg-light"><i class="fas fa-home col-2"></i>
                    <span class="col">Dashboard</span></a>
                <a href="transaksi.php" class="list-group-item list-group-item-action bg-light"><i
                        class="fas fa-money-check col-2"></i> <span class="col">Transaksi</span></a>
                <a href="history.php" class="list-group-item list-group-item-action bg-light"><i class="fas fa-history col-2"></i>
                    <span class="col">History</span></a>
            </div>
        </div>

        <div class="col container-fluid content">
            <div class="p-5">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id Transaksi</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($trxes as $trx) {
                                echo '
                                    <tr>
                                        <td>'.$trx['id'].'</td>
                                        <td>'.$trx['tanggal'].'</td>
                                        <td>'.$trx['total'].'</td>
                                        <td><a class="btn btn-link" href="history.php?detail='.$trx['id'].'">Detail</a></td>
                                    </tr>
                                ';
                            }
                        ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
    <!-- <form id="modal-item-list" class="modal" tabindex="-1" role="dialog" action="transaksi.php" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body vh-50 mh-50">
                    <table class="table" id="item-table" data-page-length='5'>
                        <thead>
                            <th>Id</th>
                            <th>Nama</th>
                            <th>Harga Satuan</th>
                            <th>Kuantitas</th>
                        </thead>
                        <tbody>
                            <?php
                            $i=0;
                            foreach ($items as $item) {
                                // <td><input class="btn btn-link" type="submit" name="addToCart" value="'.$item['nama'].'"></td>

                                echo '
                                    <tr>
                                        <td><input type="checkbox" name="checked['.$item['id'].']">&nbsp;'.$item['id'].'</td>
                                        <td>'.$item["nama"].'</td>
                                        <td>'.$item['harga'].'</td>
                                        <td><input class="form-control" type="number" name="qty['.$item['id'].']" value=0 style="width: 128px"></td>
                                    </tr>
                                ';
                                $i++;
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-success" type="Submit" value="Tambahkan" name="addToCart">
                </div>
            </div>
        </div>
    </form> -->
    <script>
        $(document).ready(function () {
            $('#item-table').DataTable({
                "bInfo": false,
                "lengthChange": false,
            });
        });

        var carts = [{
            "name": "brokoli",
            "qty": 10,
            "price": 10000
        }]

        function addItemToCart(e, data) {
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

        function willDeleteCart() {
            Swal.fire({
                title: 'Hapus Keranjang',
                text: "Anda yakin ingin menhapus keranjang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "transaksi.php?delete"
                }
            })
        }
    </script>
</body>

</html>