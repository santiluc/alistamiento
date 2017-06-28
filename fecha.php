<?php
	$dia=date("l");

	if ($dia=="Monday") $dia="LUNES";
	if ($dia=="Tuesday") $dia="MARTES";
	if ($dia=="Wednesday") $dia="MIERCOLES";
	if ($dia=="Thursday") $dia="JUEVES";
	if ($dia=="Friday") $dia="VIERNES";
	if ($dia=="Saturday") $dia="SABADO";
	if ($dia=="Sunday") $dia="DOMINGO";

	$mes=date("F");

	if ($mes=="January") $mes="ENERO";
	if ($mes=="February") $mes="FEBRERO";
	if ($mes=="March") $mes="MARZO";
	if ($mes=="April") $mes="ABRIL";
	if ($mes=="May") $mes="MAYO";
	if ($mes=="June") $mes="JUNIO";
	if ($mes=="July") $mes="JULIO";
	if ($mes=="August") $mes="AGOSTO";
	if ($mes=="September") $mes="SEPTIEMBRE";
	if ($mes=="October") $mes="OCTUBRE";
	if ($mes=="November") $mes="NOVIEMBRE";
	if ($mes=="December") $mes="DICIEMBRE";

	$anio=date("Y");
	$dia2=date("d");
	$hora=date("g:i:s");
	ini_set('date.timezone','America/Bogota'); 
	$fecha = date("g:i A");

	echo "$dia, $dia2 DE $mes DE $anio Ultima Actualizacion: $fecha";

?>