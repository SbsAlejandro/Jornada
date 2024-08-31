<?php

require_once 'ModeloBase.php';

class dashboardModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /*------------Metodo para listar Especies--------*/
    public function totalEquivUSD()
    {
        $db = new ModeloBase();
        $query = "SELECT FORMAT(SUM(equiv_usd), 2) AS totalEquivUSD FROM cierre_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para listar Especies--------*/
    public function totalKlVendidos()
    {
        $db = new ModeloBase();
        $query = "SELECT SUM(kl_vendidos) AS kl_vendidos FROM cierre_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para listar Especies--------*/
    public function totalBs()
    {
        $db = new ModeloBase();
        $query = "SELECT SUM(total_bs) AS total_bs FROM cierre_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }
}
