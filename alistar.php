<?php
session_start();
require_once('db.php'); 
$_SESSION['contador'] = $_SESSION['contador'] + 1;
$ord = $_GET['ord'];
$ordencompra = $_GET['id'];
$articulo = $_GET['pt'];
$cnt = $_GET['cnt'];
$info = array('Database'=>$basedatos, 'UID'=>$usuario, 'PWD'=>$pass); 
$conexion = sqlsrv_connect($servidor, $info);  

if(!$conexion){

 die( print_r( sqlsrv_errors(), true));

 }
 
 	$query1 = "UPDATE sod_det set sod_status = 'x', sod_qty_pick = ".$cnt." WHERE sod_so_id = '".$ordencompra."' AND sod_pt_id = '".$articulo."'";
	sqlsrv_query($conexion, $query1);
	header("location: detallepedido.php?id=".$ordencompra."&numord=".$ord);
?>
