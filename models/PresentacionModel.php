<?php

require_once 'ModeloBase.php';

class PresentacionModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /*------------Metodo para listar Presentacion--------*/
    public function listarPresentacion()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM presentacion";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para Presentacion Presentacion--------*/
    public function consultarPresentacion()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM presentacion";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para registrar Presentacion--------*/
    public function registrarPresentacion($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('presentacion', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Presentacion --------*/
    public function obtenerPresentacion($id)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM presentacion WHERE id = " . $id . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }



    /*------------ Metodo para modificar un registro --------*/
    public function modificarPresentacion($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('presentacion', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Presentacion --------*/

    public function validarEntradaDiaPresentacion($descripcion)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM presentacion WHERE descripcion = '$descripcion' ";
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
