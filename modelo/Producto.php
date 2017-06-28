<?php
require_once('modelo/Medida.php'); 
class Producto
{
    public $ubicacionini;
    public $ubicacionfin;
    public $codigo;
    public $descripcion;
    public $cantidad;
    public $medida;
    
 
    public function __construct($ubicacionini, $ubicacionfin, $codigo, $descripcion, $cantidad, $medida)
    {
        $this->ubicacionini = $ubicacionini;
        $this->ubicacionfin = $ubicacionfin;
        $this->codigo = $codigo;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->medida = $medida;
        
    }

    // get user's first name
    public function getMedida()
    {
        return $this->medida;
    }
}

?>