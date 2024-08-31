<?php

require_once 'ModeloBase.php';

class PersonalModel extends ModeloBase
{

	public function __construct()
	{
		parent::__construct();
	}

	/*------------ Obtener personal no deseado por cédula --------*/
	public function obtenerPersonalCedula($cedula)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM personasno WHERE cedula = " . $cedula . "";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------Metodo para listar  Personal no deseado--------*/
	public function listarPersonal()
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM personasno";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------ Metodo para registrar Personal no deseado--------*/
	public function registrarPersonal($datos)
	{
		$db = new ModeloBase();
		try {
			$insertar = $db->insertar('personasno', $datos);
			return $insertar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------ Metodo para mostrar un registro  Personal no deseado --------*/
	public function obtenerPersonal($id)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM personasno WHERE id = " . $id . "";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}


	/*------------ Metodo para mostrar un registro --------*/
	public function validarEntradaDia($nombre, $apellido, $cedula, $fecha_actual)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM personasno WHERE nombre = '$nombre' AND apellido ='$apellido' AND cedula='$cedula' AND fecha = '$fecha_actual'";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}






	/*------------ Metodo para modificar un registro   Personal no deseado --------*/
	public function modificarPersonal($id, $datos)
	{
		$db = new ModeloBase();
		try {

			$editar = $db->editar('personasno', $id, $datos);

			return $editar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*






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
