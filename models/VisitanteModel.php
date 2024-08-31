<?php

require_once 'ModeloBase.php';

class VisitanteModel extends ModeloBase
{

	public function __construct()
	{
		parent::__construct();
	}

	/*------------ Obtener el estatus del visitante --------*/
	public function ObtenerEstatus($id)
	{
		$db = new ModeloBase();
		$query = "SELECT visitas.estatus, ves_per.id_visitantes FROM ves_per AS ves_per
		JOIN visitas AS visitas ON ves_per.id_personas=visitas.id
		WHERE ves_per.id=$id";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}


	/*------------Metodo para modificar los datos del visitante (tbl personas)--------*/
	public function modificarPersonaVisitante($id, $datos)
	{
		$db = new ModeloBase();
		try {

			$editar = $db->editar('personas', $id, $datos);

			return $editar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}


	/*------------Metodo para modificar los datos del visitante (tbl visitante)--------*/
	public function modificarVisitaVisitante($id, $datos)
	{
		$db = new ModeloBase();
		try {

			$editar = $db->editar('visitas', $id, $datos);

			return $editar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------Metodo para modificar los datos del visitante (tbl ves_per)--------*/
	public function modificarDatosVisita($id, $datos)
	{
		$db = new ModeloBase();
		try {

			$editar = $db->editar('ves_per', $id, $datos);

			return $editar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------Metodo para registrar los datos del visitante (tbl personas)--------*/

	public function registrarPersonaVisitante($datos)
	{
		$db = new ModeloBase();
		try {
			$insertar = $db->insertar('personas', $datos);

			return $insertar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------ Obtener el ultimo id insertado en la tbl (ves_per) --------*/
	public function obtenerID()
	{
		$db = new ModeloBase();
		$query = "SELECT id FROM ves_per ORDER BY id DESC LIMIT 1";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------ Obtener el id persona mediante la cedula --------*/
	public function obtenerCedulaPersona($id)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM ves_per WHERE id=$id";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------ Obtener el id visita --------*/
	public function obtenerIdVisita($id)
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM ves_per WHERE id=$id";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}


	/*------------Metodo para registrarla visita (tbl visitas)--------*/

	public function registrarVisita($datos)
	{
		$db = new ModeloBase();
		try {
			$insertar = $db->insertar('visitas', $datos);

			return $insertar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------Metodo para registrarla el visitante (tbl visitas)--------*/

	public function registrarVisitante($datos)
	{
		$db = new ModeloBase();
		try {
			$insertar = $db->insertar('ves_per', $datos);
			return $insertar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*------------Metodo para listar visitantes--------*/
	public function listarVisitantes()
	{
		$db = new ModeloBase();
		$query = "SELECT * FROM visitantes";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*------------ Consultar visitante por cedula --------*/
	public function consultarVisitanteCedula($cedula)
	{
		$db = new ModeloBase();
		$query = "SELECT ves_per.id, personas.cedula, 
		personas.nombre, personas.apellido, 
		ves_per.empresa, visitas.asunto, 
		visitas.piso, departamentos.nombre AS departamento, departamentos.id AS id_departamento, 
		ves_per.foto, visitas.estatus, ves_per.fecha, visitas.autorizador 
		FROM ves_per AS ves_per 
		JOIN visitas AS visitas ON ves_per.id_visitantes=visitas.id 
		JOIN personas AS personas ON ves_per.id_personas=personas.id
		JOIN departamentos AS departamentos ON visitas.departamento=departamentos.id
		WHERE personas.cedula = " . $cedula . " ORDER BY ves_per.id DESC";
		$resultado = $db->obtenerTodos($query);

		return $resultado;
	}


	/*------------ Metodo para mostrar un registro --------*/
	public function obtenerVisitante($id)
	{
		$db = new ModeloBase();
		$query = "SELECT ves_per.id, personas.cedula, 
		personas.nombre, personas.apellido, 
		ves_per.empresa, ves_per.telefono, ves_per.codigo_carnet, visitas.asunto, 
		visitas.piso, departamentos.nombre AS departamento, departamentos.id AS id_departamento, 
		ves_per.foto, visitas.estatus, ves_per.fecha, visitas.autorizador 
		FROM ves_per AS ves_per 
		JOIN visitas AS visitas ON ves_per.id_visitantes=visitas.id 
		JOIN personas AS personas ON ves_per.id_personas=personas.id
		JOIN departamentos AS departamentos ON visitas.departamento=departamentos.id
		WHERE ves_per.id = " . $id . " ORDER BY ves_per.id DESC";
		$resultado = $db->obtenerTodos($query);

		return $resultado;
	}

	/*------------ Metodo para mostrar un registro --------*/
	public function validarEntradaDia($cedula, $fecha_actual)
	{
		$db = new ModeloBase();
		$query = "SELECT ves_per.id, personas.cedula, personas.nombre, 
	personas.apellido, ves_per.empresa, visitas.asunto, visitas.piso, 
	ves_per.foto, visitas.estatus, ves_per.fecha FROM ves_per AS ves_per JOIN visitas AS visitas ON ves_per.id_visitantes=visitas.id JOIN personas AS personas ON ves_per.id_personas=personas.id 
	WHERE ves_per.fecha = '$fecha_actual' AND personas.cedula='$cedula'";

		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}



	/*------------ Metodo para modificar un registro --------*/
	public function modificarVisitante($id, $datos)
	{
		$db = new ModeloBase();
		try {

			$editar = $db->editar('visitantes', $id, $datos);

			return $editar;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/*--------------- Contador de visitantes del día actual -------------- */

	public function visitantesDeHoy()
	{
		$db = new ModeloBase();
		$query = "SELECT COUNT(*) AS total_visitantes_hoy
		FROM ves_per
		WHERE fecha = CURDATE()";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*--------------- Contador de los visitantes del día de ayer -------------- */

	public function visitantesDeAyer()
	{
		$db = new ModeloBase();
		$query = "SELECT COUNT(*) AS total_visitantes_ayer
		FROM ves_per
		WHERE fecha = CURDATE() - INTERVAL 1 DAY";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*--------------- Contador de los visitantes de los 7 dias -------------- */

	public function visitantesSieteDias()
	{
		$db = new ModeloBase();
		$query = "SELECT COUNT(*) AS total_visitantes_ultimos_7_dias
		FROM ves_per
		WHERE fecha >= CURDATE() - INTERVAL 7 DAY;";
		$resultado = $db->obtenerTodos($query);
		return $resultado;
	}

	/*--------------- Contador de los visitantes de los 7 dias -------------- */

	public function visitantesTotal()
	{
		$db = new ModeloBase();
		$query = "SELECT COUNT(*) AS total_visitantes
			FROM ves_per";
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
