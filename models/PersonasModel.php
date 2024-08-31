<?php

require_once 'ModeloBase.php';

class PersonasModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /*------------Metodo para listar Personas--------*/
    public function listarPersonas()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM personas";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para Personas  --------*/
    public function consultarPersonas()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM personas";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para registrar Personas--------*/
    public function registrarPersonas($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('personas', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Presentacion --------*/
    public function obtenerPersonas($id)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM personas WHERE id = " . $id . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para obtener el emma parroquial --------*/
    public function obtenerTipoPersona($tipo_persona)
    {
        $db = new ModeloBase();
        $query = "SELECT personas.id, personas.nombre_apellidos, 
                         personas.telefono, personas.fecha_registro, 
                         tipo_persona.descripcion, personas.estatus 
                FROM personas AS personas JOIN tipo_persona AS tipo_persona ON personas.id_tipo_persona=tipo_persona.id
                WHERE tipo_persona.descripcion='$tipo_persona'";

        $resultado = $db->obtenerTodos($query);

        return $resultado;
    }



    /*------------ Metodo para modificar un registro Personas --------*/
    public function modificarPersonas($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('personas', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Presentacion --------*/

    public function validarEntradaDiaPersonas($nombre_apellidos)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM personas WHERE nombre_apellidos = '$nombre_apellidos'  ";
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
