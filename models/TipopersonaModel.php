<?php

require_once 'ModeloBase.php';

class TipopersonaModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /*------------Metodo para listar Tipopersona--------*/
    public function listarTipopersona()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM tipo_persona";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para Tipopersona--------*/
    public function consultarTipopersona()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM tipo_persona";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para registrar Tipopersona--------*/
    public function registrarTipopersona($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('tipo_persona', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Tipopersona --------*/
    public function obtenerTipopersona($id)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM tipo_persona WHERE id = " . $id . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }



    /*------------ Metodo para modificar un registro Tipopersona --------*/
    public function modificarTipopersona($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('tipo_persona', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Tipopersona --------*/

    public function validarEntradaDiaTipopersona($descripcion)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM tipo_persona WHERE descripcion = '$descripcion' ";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }






    /*


	public function eliminarCliente($id) {
		$db = new ModeloBase();
		try {
			$eliminar = $db->eliminar('cliente', $id);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	*/
}
