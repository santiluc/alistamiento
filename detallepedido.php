<?php 

$productos = [];
$contador = 0;
$cliente = "";
$numorden = "";
$ord = $_GET['numord'];
$ordencompra = $_GET['id']; 
require_once('modelo/Producto.php'); 
require_once('modelo/Medida.php'); 
if ( ! session_id() ) @ session_start();
if (!isset($_SESSION['productos'])){

  $usuario ="capptus";
  $pass ="Encuentro1";
  $servidor ="SERVER-PC";
  $basedatos ="lucembarques";
  $info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
  $conexion = sqlsrv_connect($servidor, $info); 

  if(!$conexion){
     die( print_r( sqlsrv_errors(), true));
  }
  $query2 = "select um_org, um_dest, um_conv,um_pt_id
  from um_mstr
  where um_pt_id in (
  SELECT sod_pt_id 
  FROM sod_det
  Where sod_so_id = '".$ordencompra."') order by um_pt_id";
  $registros2 = sqlsrv_query($conexion, $query2);
  $medidas = [];
  while($row2 = sqlsrv_fetch_object($registros2)){
    $tmp = "";
    $pos = -1;
    for ($i=0; $i < count($medidas); $i++) { 
      $lee = $medidas[$i];
      if($lee->codigo == $row2->um_pt_id){
        $tmp = $lee;
        $pos = $i;
        $i =  count($medidas);
      }
    }

    $art = $row2->um_pt_id;
    $cant = $row2->um_conv;
    $org = $row2->um_org;

    if($tmp == ""){
      if($org == "CJ"){
         $tmp = new Medida($art, $cant, 0);
      }else{
         $tmp = new Medida($art, 0, $cant);
      }
     
    }else{
      if($org == "CJ"){
        $tmp->cajas = $cant;
      }else{
         $tmp->display = $cant;
      }
      
    }
    if($pos == -1){
      array_push($medidas,$tmp);
    }else{
      $medidas[$pos] = $tmp;
    }
    
  }

  $query2 = "SELECT *
  FROM (SELECT sod_pt_id, sod_qty_ord, pt_desc, mar_desc, sod_status,so_cm_id      
  FROM so_hist, sod_det, pt_mstr, mar_mstr
  Where so_status = 'AL'
  AND sod_so_id = so_id 
  AND sod_pt_id = pt_id
  AND pt_mar_id = mar_id
  AND so_id = '".$ordencompra."') AS TM
  LEFT JOIN alm_mstr ON alm_mstr.alm_pt_id = TM.sod_pt_id";
  $registros2 = sqlsrv_query($conexion, $query2);
  $cliente = "";
  while($row2 = sqlsrv_fetch_object($registros2)){
    $cliente = $row2->so_cm_id;
    $medidatmp = new Medida($row2->sod_pt_id,0,0);
    for ($i=0; $i < count($medidas); $i++) { 
      $lee = $medidas[$i];
      if($lee->codigo == $row2->sod_pt_id){
        $medidatmp = $lee;
        $i =  count($medidas);
      }
    }

    //$numorden = $row2->so_id;
    $tmp = new Producto($row2->alm_ini,$row2->alm_fin,$row2->sod_pt_id,$row2->pt_desc,$row2->sod_qty_ord,$medidatmp);
    array_push($productos,$tmp);
  }
  
  $_SESSION['productos'] = $productos ;
  $_SESSION['contador'] = $contador; 
  $_SESSION['cliente'] = $cliente; 
  $_SESSION['numord'] = $ord;
 
}else{
  $productos = $_SESSION['productos'];
  $contador = $_SESSION['contador'];
  $cliente =  $_SESSION['cliente'];
  $ord = $_SESSION['numord'];
}

?>

<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/light-bootstrap-dashboard.css" rel="stylesheet"/>
<link href="css/facapolo.css" rel="stylesheet" />
<link rel="shortcut icon" href="img/ap.ico">
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>APOLO - Alistamiento</title>
<body background="img/alistarpedidos.jpg" style="background-size: 100%;">
<script type="text/javascript" src="bootbox.min.js"></script>

<script> // ESTO ES PARA QUE AL PRESIONAR EL BOTON NO SUBA SI NO SE QUEDE EN EL LUGAR 
window.onload=function(){
var pos=window.name || 0;
window.scrollTo(0,pos);
}
window.onunload=function(){
window.name=self.pageYOffset || (document.documentElement.scrollTop+document.body.scrollTop);
}

function myFunction(orden,  producto, cnt, ord) {
        var cantidad = prompt("Porfavor ingresar la cantidad", cnt );
        
        if (cantidad != null) {
          window.location.href = "alistar.php?id=" + orden + "&pt=" + producto + "&cnt=" + cantidad + "&ord=" + ord;
        }
    }
</script>

<div class="container">
  <section class="main-row">
    <article class="col-md-2" style="margin-top: 20px;">
      <?php 
      echo "<a href='pedidosdelcliente.php?id=".$cliente."&nomcli=".$cliente."' class= 'btn btn-info btn-lg'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span> Atras </a>";
      ?>
      <!--<button type="button" class="btn btn-info" onclick="window.open('../validacion/pedidosporvalidar.php')">
      <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Validacion<br>de Pedidos
      </button>-->
    </article>

    <article class="col-md-3" style="color: white; margin-top: 15px;">
      <h5 class="title"><?php echo "Orden de Compra: <p style='font-size:25px;'>$ord</p>"; ?></h5> <!-- $ordencompra no es el mismo dato-->
    </article>

    <article class="col-md-4" style="color: white; margin-top: 15px;">
      <h5 class="title"><?php include ("modelo/fecha1.php"); ?></h5> 
    </article>

    <article class="col-md-3" style="margin-top: 20px;">
      <a href='logicaLogout.php' class= 'btn btn-info btn-lg'> Cerrar Sesion 
        <span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>
      </a>
    </article>
  </section>  
</div>

<div class="container">
  <section class="main-row">
      <?php 
        if($contador < count($productos)){
      ?>
      <article class="col-md-4">
        <div class="card" style="height: 270px; opacity: 0.7; border-radius: 10px;">
          <h4 style="margin-left: 5px; font-weight: bold; color: grey;">Ubicacion</h4>
            <?php 
              $productoactual = $productos[$contador];
              echo "<h3><center style='font-size: 60px;'>".$productoactual->ubicacionini."</center></h3>"; 
            
              echo "<hr style='background-color: black; height: 1px; width: 100%;'>";
            
              echo "<h3><center style='font-size: 60px;'>".$productoactual->ubicacionfin."</center></h3>";
            ?>
        </div>  
      </article>
    
      <article class="col-md-4">
        <div class="card" style="height: 270px; opacity: 0.7; border-radius: 10px;">
          <h4 style="margin-left: 5px; font-weight: bold; color: grey;">Cantidad</h4>
            <?php 
              
              $numcajas = 0;
              $numdisplay = 0;
              $numunidades = 0;
              
              $productoactual = $productos[$contador];
              $unimed = $productoactual->medida;

              if($unimed->cajas > 0){  
                $divcj =  $productoactual->cantidad / $unimed->cajas;
                $divide = explode(".",$divcj);

                $numcajas = $divide[0];
                if(count($divide) == 1){
                  $numdisplay = 0;
                  $numunidades = 0;
                }else{
                  if($unimed->display > 0){
                    $divdp = ($unimed->cajas * ($divcj - $divide[0])) / $unimed->display;
                    $dividedp = explode(".",$divdp);
                    $numdisplay = $dividedp[0];  
                      if(count($dividedp) == 1){
                        $numunidades = 0;
                      }else{
                        $numunidades = ($divdp - $dividedp[0]) * $unimed->display;
                      }
                  }else{
                    $numdisplay = 0;
                    $numunidades = ($unimed->cajas * ($divcj - $divide[0]));
                  } 
                }
              }else{
                  $numcajas = 0;
                  $numdisplay = 0;
                  $numunidades = $productoactual->cantidad;
              }
              
              echo "<center><font style='font-size: 100px;'>".$productoactual->cantidad."</font><font size=5> Und </font></center>";
              #echo "<center style='font-size: 90px; margin-top: -10px;'>".$productoactual->cantidad."</center><center style='font-size: 30px; margin-top: -30px;'>Unidades</center>";
              ?>

              <table class="table">
                <thead>
                  <th><center>Cajas</center></th>
                  <th><center>Display</center></th>
                  <th><center>Unidades</center></th>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo "<center>".$numcajas."</center>";?></td>
                    <td><?php echo "<center>".$numdisplay."</center>";?></td>
                    <td><?php echo "<center>".$numunidades."</center>";?></td>
                  </tr>
                </tbody>
              </table>
        </div>   
      </article>

      <article class="col-md-4">
        <div class="card" style="height: 220px; opacity: 0.7; border-radius: 10px;">
          <h4 style="margin-left: 5px; font-weight: bold; color: grey;">Producto</h4>
            <?php 
              $productoactual = $productos[$contador];
              echo "<center style='font-size: 35px'>".$productoactual->descripcion."</center><center>".$productoactual->codigo."</center>";
            ?>
            
        </div> 
        <?php echo "<center style='margin-top: -20px;'><button class= 'btn btn-info btn-block' onClick = 'myFunction(".$ordencompra.",".$productoactual->codigo. "," .$productoactual->cantidad.",".$ord.")'> Separar <span class='glyphicon glyphicon-shopping-cart' aria-hidden='true'></span>
            </a>" ?> 
      </article>
      <?php 
        }else{
          echo "Pedido Finalizado";
          unset($_SESSION['productos']);
          unset($_SESSION['contador']);
          unset($_SESSION['cliente']);
          unset($_SESSION['numord']);
        }
      ?>
  </section>  
</div>


<!--href='alistar.php?id=".$ordencompra."&pt=".$row2->sod_pt_id."' 
 <section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <div class="card">
        <div class="header">
            <h4 class="title"><?php #echo "$numorden";  ?></h4> 
              <p class="category"><?php #include ("fecha.php") ?></p>
        </div>
          <div class="content table-responsive table-full-width">
           <table class="table table-hover table-striped">
              <thead>
                <th>Articulo</th>
                <th><center>Cantidad</center></th>
                <th><center>Ubicacion</center></th>
                <th><center>Marca</center></th>
                <th><center>EAN Articulo</center></th>
                <th></th>
              </thead>
              
              <tbody>
              <?php  
                /*header('Content-Type: text/html; charset=ISO-8859-1');
                $cliente = "";
                while($row2 = sqlsrv_fetch_object($registros2)){
                  $estilo = "pendiente";
                  $cliente = $row2->so_cm_id;
                    if ($row2->sod_status == 'x' || $row2->sod_status == 'V' ) {
                      $estilo = "alistado";
                    }
                  echo "<tr class='".$estilo."'>";                                       
                  
                  echo "<td>".$row2->pt_desc."</td>" ;
                  echo "<td><center>".$row2->sod_qty_ord."</center></td> " ;
                  echo "<td><center>".$row2->alm_ini." - ".$row2->alm_fin."</center></td> " ; 
                  echo "<td><center>".$row2->mar_desc."</center></td> " ;  
                  echo "<td><center>".$row2->sod_pt_id."</center></td> " ; 
                  echo "<td><a href='alistar.php?id=".$ordencompra."&pt=".$row2->sod_pt_id."' class= 'btn btn-warning'> Separar
                        <span class='glyphicon glyphicon-shopping-cart' aria-hidden='true'></span> 
                        </a></td>";                                       
                  echo "</tr>";  
                } 
              */?>
              </tbody>
            </table>
          </div>                                            
        </div>
      </div>
    </div>
</section>-->
