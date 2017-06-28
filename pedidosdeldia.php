<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/light-bootstrap-dashboard.css" rel="stylesheet"/>
<link href="css/facapolo.css" rel="stylesheet" />
<title>APOLO - Alistamiento</title>
<link rel="shortcut icon" href="img/ap.ico">
<body background="img/alistarpedidos.jpg" style="background-size: 100%;">

<?php
session_start();
$usid = $_SESSION["usid"];
$usuario ="capptus";
$pass ="Encuentro1";
$servidor ="SERVER-PC";
$basedatos ="lucembarques";
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  

//$cliente = $_GET['id']; 
if(!$conexion){

 die( print_r( sqlsrv_errors(), true));
 }

$query2 = "SELECT distinct cm_name, cm_id2      
  FROM so_hist, cm_mstr  
   WHERE ( so_status = 'TX' OR so_status = 'AL')
	AND so_cm_id = cm_id2
    AND (so_us_id is null or so_us_id = '".$usid."') ";

	$registros2 = sqlsrv_query($conexion, $query2);

?>

<!-- "<href='pedidosdelcliente.php?id=".$row2->cm_id2."'>".$row2->cm_name."</a><br>" ; -->

<!--TABLA PARA MOSTRAR LOS CLIENTES QUE TIENEN FACTURACION-->
          
<header>
  <img class="img-responsive img-center" src="img/apolo1.jpg">
</header>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Proximas Entregas</h4>
                        <p class="category"><?php include ("fecha.php") ?></p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>ID DEL CLIENTE</th>
                                <th>ALMACEN</th>
                            </thead>
                            <tbody>
                                <?php  
                                while($row2 = sqlsrv_fetch_object($registros2)){
                                echo "<tr>";
                                echo "<td>".$row2->cm_id2."</td>";
                                        //echo "<td>".$row2->cm_name."</td>";                                        
                                echo "<td><a href='pedidosdelcliente.php?id=".$row2->cm_id2."&nomcli=".$row2->cm_name."'>".$row2->cm_name."</a> </td>" ;
                                echo "</tr>";  
                                }  
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            <a href="logicaLogout.php" class= 'btn btn-info'> Cerrar Sesion 
                <span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>
            </a>
    </div>
</section>

