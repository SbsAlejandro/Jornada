<?php

require_once './models/RolesModel.php';
require_once './config/validacion.php';


class RolesController
{

	#estableciendo la vista del login
	public function inicioRoles()
	{

		/*HEADER */
		require_once('./views/includes/cabecera.php');

		require_once('./views/paginas/roles/inicioRoles.php');

		/* FOOTER */
		require_once('./views/includes/pie.php');
	}


	public function listarRoles()
	{
		// Database connection info 
		$dbDetails = array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'db'   => 'jornada_diaria'
		);


		$table = 'roles';

		// Table's primary key 
		$primaryKey = 'id';

		// Array of database columns which should be read and sent back to DataTables. 
		// The `db` parameter represents the column name in the database.  
		// The `dt` parameter represents the DataTables column identifier. 
		$columns = array(

			array('db' => 'id', 		'dt' => 0),
			array('db' => 'rol',     	'dt' => 1),
			array(
				'db'        => 'estatus',
				'dt'        => 2,
				'formatter' => function ($d, $row) {
					return ($d == 1) ? '<button class="btn btn-success btn-sm">Activo</button>' : '<button class="btn btn-danger btn-sm">Inactivo</button>';
				}
			),
			array('db' => 'id', 'dt' => 3),
			array('db' => 'estatus', 'dt' => 4)
			//array( 'db' => 'fecha_registro','dt' => 9 ),

		);

		// Include SQL query processing class 
		require './config/ssp.class.php';

		// Output data as json format 
		echo json_encode(
			SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
		);
	}


	public function registrarRoles()
	{

		$modelroles = new RolesModel();

		/* --------- Funcion limpiar cadenas ---------*/

		$rol 				= Validacion::limpiar_cadena($_POST['rol']);

		$estatus 				= Validacion::limpiar_cadena($_POST['estatus']);
		$fecha_actual = date("Y-m-d");

		//Validar que el visitante no ingrese dos veces al sistema el mismo día
		$entrada_roles_hoy = $modelroles->validarEntradaDia($rol, $fecha_actual);

		foreach ($entrada_roles_hoy as $entrada_roles_hoy) {
			$id_entrada_roles = $entrada_roles_hoy['id'];
		}



		if (!empty($id_entrada_roles)) {
			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'El rol ya ha sido ingresado el día de hoy',
					'info'               =>  'Fecha de hoy ' . $fecha_actual . ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}


		//caracteres especiales 
		if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $rol)) {

			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Datos inválidos',
					'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el rol.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
		$datos = array(

			'rol'		=> $_POST['rol'],

			'estatus'		=> $_POST['estatus'],
			'fecha'			=> $fecha_actual,
		);

		/* comprobar campos vacios */
		if ($rol == ""  || $estatus == "") {
			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Datos inválidos',
					'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un rol.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}

		$resultado = $modelroles->registrarRoles($datos);

		if ($resultado) {
			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Guardado exitosamente',
					'info'               =>  'El rol ha sido guardado en la base de datos'
				],
				'code' => 1,
			];

			echo json_encode($data);
			exit();
		} else {
			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'Ocurrió un error al guardar el rol',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}


	public function verRoles()
	{
		$modelRoles = new RolesModel();

		$id_roles = $_POST['id_roles'];

		$listar = $modelRoles->obtenerRoles($id_roles);


		foreach ($listar as $listar) {

			$id_roles 	= $listar['id'];
			$rol 		= $listar['rol'];
			$fecha 				= $listar['fecha'];
			$estatus 		= $listar['estatus'];
		}

		$data = [
			'data' => [
				'success'            =>  true,
				'message'            => 'Registro encontrado',
				'info'               =>  '',
				'id'				 => $id_roles,
				'rol'			 => $rol,
				'fecha'		     	 => $fecha,
				'estatus'		     => $estatus,
			],
			'code' => 0,
		];

		echo json_encode($data);

		exit();
	}

	public function listaRoles()
	{
		$modelRoles = new RolesModel();

		return $modelRoles->listarRoles();
	}



	public function modificarRoles()
	{

		$modelRoles = new RolesModel();
		$id_roles = $_POST['id_roles'];
		/* --------- Funcion limpiar cadenas ---------*/

		$rol 				= Validacion::limpiar_cadena($_POST['rol']);
		$estatus 				= Validacion::limpiar_cadena($_POST['estatus']);
		$fecha_actual = date("Y-m-d");

		//Validar que el visitante no ingrese dos veces al sistema el mismo día
		$entrada_roles_hoy = $modelRoles->validarEntradaDia($rol, $fecha_actual);

		foreach ($entrada_roles_hoy as $entrada_roles_hoy) {
			$id_entrada_roles = $entrada_roles_hoy['id'];
		}



		if (!empty($id_entrada_roles)) {
			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'El rol ya ha sido ingresado el día de hoy',
					'info'               =>  'Fecha de hoy ' . $fecha_actual . ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}

		//caracteres especiales 
		if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $rol)) {

			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Datos inválidos',
					'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el rol.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}

		$datos = array(

			'rol'		=> $_POST['rol'],

			'estatus'		=> $_POST['estatus'],
		);

		/* comprobar campos vacios */
		if ($rol == "" || $estatus == "") {
			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Datos inválidos',
					'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un rol.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}



		$resultado = $modelRoles->modificarRoles($id_roles, $datos);

		if ($resultado) {
			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Guardado exitosamente',
					'info'               =>  'Los datos de los roles han sido modificados'
				],
				'code' => 1,
			];

			echo json_encode($data);
			exit();
		} else {
			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'Ocurrió un error al modificar los datos de los roles',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	/*----------Metodo para inactivar Visitante-------*/
	public function inactivarRoles()
	{

		$modelroles = new RolesModel();
		$id_roles = $_POST['id_roles'];

		$estado = $modelroles->obtenerRoles($id_roles);

		foreach ($estado as $estado) {
			$estado_Roles = $estado['estatus'];
		}

		if ($estado_Roles == 1) {
			$datos = array(
				'estatus'		=> 0,
			);

			$resultado = $modelroles->modificarRoles($id_roles, $datos);
		} else {
			$datos = array(
				'estatus'		=> 1,
			);

			$resultado = $modelroles->modificarRoles($id_roles, $datos);
		}

		if ($resultado) {
			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Guardado exitosamente',
					'info'               =>  'El estado del rol ha sido modificado'
				],
				'code' => 1,
			];

			echo json_encode($data);
			exit();
		} else {
			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'Ocurrió un error al modificar el estado del rol',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}
}
