<?php
    require_once("../functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/3b79ccf7db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/styleclone.css">
    <title>V-Zelenyy</title>
</head>

<body>
    <div class="d-flex" id="wrapper">

         <div class="vh-100 side-menu-container d-flex flex-column justify-content space-between" id='side-menu'>
            <div class="menu-title"><img src="../img/zelenyy3.jpg" alt=""></div>
            <div class="list-group list-group-flush">
                 <a href="dashboardclone.php" class="list-group-item list-group-item-action list-group-item-success"><i class="fas fa-home col-2"></i>
                    <span class="col">Dashboard</span></a>
                <a href="transaksiclone.php" class="list-group-item list-group-item-action bg-light"><i
                      class="fas fa-money-check col-2"></i> <span class="col">Transaksi</span></a>
                <a href="historyclone.php" class="list-group-item list-group-item-action bg-light"><i class="fas fa-history col-2"></i>
                     <span class="col">History</span></a>
                <a class="btn btn-success text-light" href="../logout.php">Logout</a>
            </div>        
        </div>

        <div class="col container-fluid content">
            <div class=" h3 alert alert-success mt-3" role="alert"> Halo, <?php echo $_SESSION['user']['nama']?> </div>
            <h4>Ringkasan hari ini</h4>

            <div class="card border-0 bg-light">
                <div class="card-body card-shadow">
                    <div class="row padd justify-content space-between">
                        <div class="col-sm-3 padd card-shadow border-rad d-flex flex-column align-items-center">
                            <div class="h4">Pembeli</div>
                            <div class="h6" style="color: #285A84">0[not found]</div>
                        </div>
                        <div class="col"></div>
                        <div class="col-sm-3 padd card-shadow border-rad d-flex flex-column align-items-center">
                            <div class="h4">Transaksi</div>
                            <div class="h6" style="color: #285A84">0[not found]</div>
                        </div>
                        <div class="col"></div>
                        <div class="col-sm-3 padd card-shadow border-rad d-flex flex-column align-items-center">
                            <div class="h4">Barang</div>
                            <div class="h6" style="color: #285A84">0[not found]</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('#side-menu-toggle').click(function(e){
                console.log('tes')
                e.preventDefault();
                $("#side-menu").width=10
            });
        </script>

    </div>    
</body>
</html> 