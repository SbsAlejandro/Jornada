<?php

require_once 'ModeloBase.php';

class RolesModel extends ModeloBase
{

	public function __construct()
	{
		parent::__construct();
	}

	/*------------Metodo para listar Roles--------*/
	public function listarRoles()
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM roles";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------Metodo para Roles Roles--------*/
	public function consultarRol()
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM roles";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------ Metodo para registrar Roles--------*/
	public function registrarRoles($datos)
	{
		$db = new ModeloBase();
		try {
			$insertar = $db->insertar('roles', $datos);
			return $insertar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------ Metodo para mostrar un registro Roles --------*/
	public function obtenerRoles($id)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM roles WHERE id = " . $id . "";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}



	/*------------ Metodo para modificar un registro --------*/
	public function modificarRoles($id, $datos)
	{
		$db = new ModeloBase();
		try {
			$editar = $db->editar('roles', $id, $datos);
			return $editar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------ Metodo para mostrar un registro roles --------*/

	public function validarEntradaDia($rol, $fecha_actual)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM roles WHERE rol = '$rol' AND fecha = '$fecha_actual'";
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
