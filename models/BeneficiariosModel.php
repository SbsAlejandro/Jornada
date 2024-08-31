<?php

require_once 'ModeloBase.php';

class BeneficiariosModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /*------------Metodo para listar Beneficiarios--------*/
    public function listarBeneficiarios()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM beneficiarios";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para  Beneficiarios--------*/
    public function consultarBeneficiarios()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM beneficiarios";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para registrar Beneficiarios--------*/

    public function registrarBeneficiarios($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('beneficiarios', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Especies --------*/
    public function obtenerBeneficiarios($id)
    {
        $db = new ModeloBase();
        $query = "SELECT beneficiarios.id, parroquias.parroquia, 
        beneficiarios.descripcion, beneficiarios.estatus 
        FROM beneficiarios AS beneficiarios
        JOIN parroquias AS parroquias
        ON beneficiarios.id_parroquia=parroquias.id_parroquia
        WHERE id = " . $id . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }



    /*------------ Metodo para modificar un registro --------*/
    public function modificarBeneficiarios($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('beneficiarios', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Presentacion --------*/

    public function validarEntradaDiaBeneficiarios($descripcion)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM beneficiarios WHERE  descripcion = '$descripcion' ";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }
    /*------------ Listar Estado --------*/




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
