<?php
	require_once('../functions.php');
	auth("admin");
	if(isset($_POST['tambah'])){
		$tambah = insertKasir($_POST['nama'],$_POST['username'],$_POST['password']);
		Header("Location: kasir.php");
	}
	if(isset($_POST['update'])){
		$update = updateKasir($_POST['id'], $_POST['nama'],$_POST['username'],$_POST['password']);
		if($update){
			Header("Location: kasir.php");
		}else{
			echo "error";
		}
	}
    $items = select_items();
    $kasirs = select_kasirs();
?>
<!DOCTYPE html>
<html>
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
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.22/datatables.min.js"> -->
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" -->
        <!-- rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css"> -->
    <!-- <script type="text/javascript"
        src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-Zelenny</title>
</head>
<body>
	<div class="d-flex vh-100">
		<div class="vh-100 side-menu-container d-flex flex-column justify-content space-between" id='side-menu'>
            <div class="menu-title"><img src="../img/zelenyy3.jpg" alt=""></div>
            <div class="list-group list-group-flush">
                <a href="dashboard.php" class="list-group-item list-group-item-action bg-light"><i class="fas fa-home col-2"></i>
                    <span class="col">Dashboard</span></a>
                <a href="stokbarang.php" class="list-group-item list-group-item-action "><i class="fas fa-cube col-2"></i> <span class="col">Stok Barang</span></a>
                <a href="historyAdmin.php" class="list-group-item list-group-item-action bg-light"><i class="fas fa-history col-2"></i>
                    <span class="col">History</span></a>
                <a href="kasir.php" class="list-group-item list-group-item-action bg-light list-group-item-success" ><i class="fas fa-calculator col-2"></i><span class="col">Kasir</span></a>
                 <a class="btn btn-success text-light" href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="col container-fluid content">
    	<div class="p-5" style="max-height: 80vh; overflow-y: scroll">
    		<table class="table table-striped">
    			<thead>
    				<tr>
    					<th>Id Kasir</th>
    					<th>Nama</th>
    					<th>Username</th>
    					<th>Password</th>
                        <th></th>
    				</tr>
    			</thead>
    			<tbody>
    				<?php
    				$i=0;
    				foreach ($kasirs as $kasir) {
    					echo'
    					<tr>
    					<td>'.$kasir['id'].'</td>
    					<td>'.$kasir['nama'].'</td>
                        <td>'.$kasir['username'].'</td>
                        <td>'.$kasir['password'].'</td>
    					<td col-2>
    					    <div class="container">
    						<button type="button" class="btn btn-success text-light" data-toggle="modal" data-target="#myModal-'.$kasir['id'].'">UPDATE</button>
    							<div>
    								<div class="modal p-3" id="myModal-'.$kasir['id'].'">
									<form class="modal-dialog modal-xl" method="post">
										<input class="form-control" type="text" hidden name="id" value="'.$kasir['id'].'">
    									<div class="modal-content">
    										<div class="model-header">
    											<h4 class="modal-title">UPDATE KASIR</h4>
    												<button type="button" class="close" data-dismiss="modal">&times;</button>
    										</div>
    							
    										<div class="modal-body">
    											<table class="table table-bordered">
    										<tr>
    											<th class="table-info" width="15%" nowrap>Nama</th>
    											<td><input class="form-control" type="text" name="nama" value="'.$kasir['nama'].'" required></td>
    										</tr>
    										<tr>
												<th class="table-info">Username</th>
												<td><input class="form-control" type="text"  name="username" value="'.$kasir['username'].'" required></td>
											</tr>
											<tr>
												<th class="table-info">Password</th>
												<td><input class="form-control" type="text" name="password" value="'.$kasir['password'].'"  required></td>
											</tr>
    									</table>
    							</div>
    							<div class="modal-footer">
    								<input type="submit" class="btn btn-success" name="update" value="UPDATE">
    							</div>
    						</div>
    					</form>
    				</div>
    			</div>
    		</div>
    					</td>
    					</tr>
    					';
    					$i++;
    				}
    				?>
    			</tbody>
					</tr>
    		</table>
    		<div class="container">
    			<div>
    				<div class="modal" id="myModal">
    					<form class="modal-dialog modal-xl" method="post">
    						<div class="modal-content">
    							<div class="model-header">
    								<h4 class="modal-title">TAMBAH KASIR</h4>
    								<button type="button" class="close" data-dismiss="modal">&times;</button>
    							</div>
    							
    							<div class="modal-body">
    									<table class="table table-bordered">
    										<tr>
    											<th class="table-info" width="15%" nowrap>Nama</th>
    											<td><input class="form-control" type="text" name="nama" required></td>
    										</tr>
    										<tr>
												<th class="table-info">Username</th>
												<td><input class="form-control" type="text" name="username" required></td>
											</tr>
											<tr>
												<th class="table-info">Password</th>
												<td><input class="form-control" type="text" name="password" required></td>
											</tr>
    									</table>
    							</div>
    							<div class="modal-footer">
    								<input type="submit" class="btn btn-info" name="tambah" value="TAMBAH">
    							</div>
    						</div>
    					</form>
    				</div>

    			</div>
    		</div>
    	</div>
		<button type="button" class="btn btn-info text-light mt-3" data-toggle="modal" data-target="#myModal">TAMBAH</button>
    </div>
</body>
</html>