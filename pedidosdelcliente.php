<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/light-bootstrap-dashboard.css" rel="stylesheet"/>
<link href="css/facapolo.css" rel="stylesheet" />
<title>APOLO - Alistamiento</title>
<link rel="shortcut icon" href="img/ap.ico">
<body background="img/alistarpedidos.jpg" style="background-size: 100%;">

<?php
session_start();
require_once('db.php'); 

  unset($_SESSION['productos']);
  unset($_SESSION['contador']);
  unset($_SESSION['cliente']);

$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  
$nomcliente = $_GET['nomcli'];
$cliente = $_GET['id']; 
if(!$conexion){
die( print_r( sqlsrv_errors(), true));
 }

$query1 = "UPDATE so_hist set so_us_id = '".$_SESSION["usid"]."', so_status ='AL' WHERE so_cm_id = '".$cliente."' AND so_status = 'TX'";
sqlsrv_query($conexion, $query1);
$query2 = "SELECT so_id,so_ped, so_type, COUNT(sod_pt_id) - COUNT(sod_status) AS faltantes 
FROM so_hist, sod_det 
WHERE so_status = 'AL'
AND so_cm_id = '".$cliente."' AND sod_so_id = so_id
group by so_id,so_ped,so_type";
$registros2 = sqlsrv_query($conexion, $query2);
?>

            
<header>
 	<img class="img-responsive img-center" src="img/apolo1.jpg">
</header>
            
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title"> <?php echo "$nomcliente"; ?></h4>
                        <p class="category"><?php include ("fecha.php") ?></p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                              <th># ORDEN DE COMPRA</th>
                              <th><center>PRODUCTOS PENDIENTES</center></th>
                            </thead>
                            <tbody>
                            <?php  
                              	while($row2 = sqlsrv_fetch_object($registros2)){
                                $estilo = "pendiente";
                 				if ($row2->faltantes == '0') {
                   				$estilo = "alistado";
                 				}
                				echo "<tr class='".$estilo."'>";
                				echo "<td><a href='detallepedido.php?id=".$row2->so_id."&numord=".$row2->so_ped."'>".$row2->so_ped."</a></td>" ;
                                echo "<td><center>".$row2->faltantes."</center></td>";
                                echo "<td><center>".$row2->so_type."</center></td>";
                                echo "</tr>";  
                             	}  
                              	?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php  
          echo "<a href='pedidosdeldia.php' class= 'btn btn-info'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>Atras</a>";
          ?> 
          <a href='logicaLogout.php' class= 'btn btn-info'> Cerrar Sesion 
            <span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>
          </a>
    </div>
</section>