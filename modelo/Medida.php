<?php


class Medida
{
    public $codigo;
    public $cajas;
    public $display;
 
    public function __construct($codigo, $cajas, $display)
    {
        $this->codigo = $codigo;
        $this->cajas = $cajas;
        $this->display = $display;
        
    }

    // get user's first name
    public function getCajas()
    {
        return $this->cajas;
    }

    // set user's last name
    public function setCajas($cajas)
    {
        $this->cajas = $cajas;
    }

    // get user's first name
    public function getDisplay()
    {
        return $this->display;
    }

    // set user's last name
    public function setDisplay($display)
    {
        $this->display = $display;
    }
}

?>