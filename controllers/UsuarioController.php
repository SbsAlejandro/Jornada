<?php

require_once './models/UsuarioModel.php';
require_once './config/validacion.php';

class UsuarioController
{

	#estableciendo la vista del login
	public function inicioUsuario()
	{

		require_once('./views/paginas/usuarios/inicioUsuario.php');
	}

	#estableciendo las vistas del modulo usuario
	public function ModuloUsuario()
	{

		/*HEADER */
		require_once('./views/includes/cabecera.php');

		require_once('./views/paginas/usuarios/usuarios.php');

		/* FOOTER */
		require_once('./views/includes/pie.php');
	}


	public function loginUsuario()
	{

		$usuario 		= $_POST['usuario'];
		$contrasena 	= $_POST['contrasena'];


		$modelUsuario = new UsuarioModel();
		$resultado = $modelUsuario->verificarUsuario($usuario, $contrasena);


		foreach ($resultado as $resultado) {
			$id_bd 				= $resultado['id'];
			$usuario_bd 		= $resultado['usuario'];
			$foto_bd 			= $resultado['foto'];
			$contrasena_bd 		= $resultado['contrasena'];
			$rol_bd 			= $resultado['rol'];
		}

		if (!empty($id_bd)) {

			session_start();

			$_SESSION['user_id'] 		= $id_bd;
			$_SESSION['usuario'] 		= $usuario_bd;
			$_SESSION['foto'] 			= $foto_bd;
			$_SESSION['rol_usuario'] 	= $rol_bd;


			$_SESSION['user_id'] = $id_bd;

			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Usuario encontrado',
					'info'               =>  ''
				],
				'code' => 1,
			];

			echo json_encode($data);
			exit();
		} else {

			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'Nombre de usuario o contraseña incorrectos',
					'info'               =>  'En caso de no estar registrado deberás comunicarte con el administrador para el registro respectivo.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	public function logoutUsuario()
	{

		session_start();

		session_unset();

		session_destroy();

		header('Location: index.php?page=inicioUsuario');
	}


	public function listarUsuarios()
	{

		// Database connection info 
		$dbDetails = array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'db'   => 'jornada_diaria'
		);

		// DB table to use 
		$table = 'usuario';


		// Table's primary key 
		$primaryKey = 'id';

		// Array of database columns which should be read and sent back to DataTables. 
		// The `db` parameter represents the column name in the database.  
		// The `dt` parameter represents the DataTables column identifier. 
		$columns = array(

			array('db' => 'id', 		'dt' => 0),
			array('db' => 'cedula',  		'dt' => 1),
			array('db' => 'usuario',      	'dt' => 2),
			array('db' => 'nombre',     	'dt' => 3),
			array('db' => 'apellido',    	'dt' => 4),
			array('db' => 'correo',    	'dt' => 5),
			array(
				'db'        => 'foto',
				'dt'        => 6,
				'formatter' => function ($d, $row) {
					return '<img width="50" src="./foto_usuario/' . $d . '">';
				}
			),
			// array( 'db' => 'fecha',     	'dt' => 9 ), 

			array(
				'db'        => 'estatus',
				'dt'        => 7,
				'formatter' => function ($d, $row) {
					return ($d == 1) ? '<button class="btn btn-success btn-sm">Activo</button>' : '<button class="btn btn-danger btn-sm">Inactivo</button>';
				}
			),
			array('db' => 'id', 'dt' => 8),
			array('db' => 'estatus', 'dt' => 9)

			//array( 'db' => 'fecha_registro','dt' => 9 ),

		);

		// Include SQL query processing class 
		require './config/ssp.class.php';

		// Output data as json format 
		echo json_encode(
			SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
		);
	}


	public function registrarUsuario()
	{

		/* --------- Funcion limpiar cadenas ---------*/
		$cedula 				= Validacion::limpiar_cadena($_POST['cedula']);
		$nombre 				= Validacion::limpiar_cadena($_POST['nombre']);
		$apellido 				= Validacion::limpiar_cadena($_POST['apellido']);
		$usuario 				= Validacion::limpiar_cadena($_POST['usuario']);
		$estatus 				= Validacion::limpiar_cadena($_POST['estatus']);
		$fecha_actual = date("Y-m-d");



		$validator = array('success' => false, 'messages' => array());

		if (!empty($_FILES["archivo"]["name"])) {

			$modelUsuarios = new UsuarioModel();




			$fileName = basename($_FILES["archivo"]["name"]);
			$targetFilePath = './foto_usuario/' . $fileName;


			//var_dump($targetFilePath); die();
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

			$allowTypes = array('jpg', 'png', 'jpeg');
			if (in_array($fileType, $allowTypes)) {
				if (copy($_FILES["archivo"]["tmp_name"], $targetFilePath)) {

					$uploadedFile = $fileName;
					$fecha_actual = date("Y-m-d");




					/* comprobar campos vacios */
					if ($_POST['cedula'] == "" || $_POST['nombre'] == "" || $_POST['apellido'] == "" || $_POST['correo'] == "" || $_POST['contrasena'] == "" || $_POST['rol'] == "" || $_POST['usuario'] == "" || $_POST['estatus'] == "") {
						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Atención',
								'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}





					if (Validacion::verificar_datos("[0-9]{1,10}", $_POST['cedula'])) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten numeros en el campo cédula del usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $_POST['nombre'])) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $_POST['apellido'])) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el apellido del usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					//Validar que el visitante no ingrese dos veces al sistema el mismo día
					$entrada_usuario_hoy = $modelUsuarios->validarEntradaDiaUsuarios($cedula, $nombre, $usuario, $fecha_actual);

					foreach ($entrada_usuario_hoy as $entrada_usuario_hoy) {
						$id_entrada_usuario = $entrada_usuario_hoy['id'];
					}



					if (!empty($id_entrada_usuario)) {
						$data = [
							'data' => [
								'success'            =>  false,
								'message'            => 'El usuario ya ha sido ingresado el día de hoy',
								'info'               =>  'Fecha de hoy ' . $fecha_actual . ''
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}



					$datos = array(

						'cedula'    	=> $_POST['cedula'],
						'usuario'		=> $_POST['usuario'],
						'nombre'		=> $_POST['nombre'],
						'apellido'		=> $_POST['apellido'],
						'correo'		=> $_POST['correo'],
						'foto'			=> $fileName,
						'fecha'			=> $fecha_actual,
						'contrasena'	=> $_POST['contrasena'],
						'rol'			=> $_POST['rol'],
						'estatus'		=> $_POST['estatus'],
					);

					$resultado = $modelUsuarios->registrarUsuario($datos);

					if ($resultado) {
						$data = [
							'data' => [
								'success'            =>  true,
								'message'            => 'Guardado exitosamente',
								'info'               =>  'El usuario se registro en la base de datos.'
							],
							'code' => 1,
						];

						echo json_encode($data);
						exit();
					} else {
						$data = [
							'data' => [
								'success'            =>  false,
								'message'            => 'Error al guardar los datos',
								'info'               =>  ''
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}
				} else {
					$data = [
						'data' => [
							'success'            =>  false,
							'message'            => 'No se copio la imagen',
							'info'               =>  ''
						],
						'code' => 0,
					];

					echo json_encode($data);
					exit();
				}
			} else {
				//$validator['messages'] = 'SOLO SE PERMITE FORMATOS JPG, PNG Y JPEG.';

				$data = [
					'data' => [
						'success'            =>  false,
						'message'            => 'Solo se permiten formatos jpg, png y jpeg.',
						'info'               =>  ''
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}
		} else {
			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Atención',
					'info'         => 'Debes subir una foto.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}


	public function verUsuario()
	{
		$modelUsuario = new UsuarioModel();

		$id_usuario = $_POST['id_usuario'];

		$listar = $modelUsuario->obtenerUsuario($id_usuario);


		foreach ($listar as $listar) {

			$id_usuario 	= $listar['id'];
			$cedula 		= $listar['cedula'];
			$usuario 		= $listar['usuario'];
			$nombre 		= $listar['nombre'];
			$apellido 		= $listar['apellido'];
			$correo 		= $listar['correo'];
			$estatus 		= $listar['estatus'];
			$rol 			= $listar['rol'];
			$foto 			= $listar['foto'];
			$fecha 			= $listar['fecha'];
		}

		$data = [
			'data' => [
				'success'            =>  true,
				'message'            => 'Registro encontrado',
				'info'               =>  '',
				'id'				 => $id_usuario,
				'cedula'    		 => $cedula,
				'usuario'			 => $usuario,
				'nombre'			 => $nombre,
				'apellido'			 => $apellido,
				'correo'			 => $correo,
				'estatus'		     => $estatus,
				'rol'		     	 => $rol,
				'foto'		     	 => $foto,
				'fecha'		     	 => $fecha,
			],
			'code' => 0,
		];

		echo json_encode($data);

		exit();
	}

	public function modificarUsuario()
	{

		$validator = array('success' => false, 'messages' => array());

		if (!empty($_FILES["archivo"]["name"])) {

			$modelUsuarios = new UsuarioModel();


			$fileName = basename($_FILES["archivo"]["name"]);
			$targetFilePath = './foto_usuario/' . $fileName;


			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

			$allowTypes = array('jpg', 'png', 'jpeg');
			if (in_array($fileType, $allowTypes)) {
				if (copy($_FILES["archivo"]["tmp_name"], $targetFilePath)) {

					$uploadedFile = $fileName;
					$fecha_actual = date("d-m-Y");
					/* comprobar campos vacios */

					if ($_POST['cedula_update'] == "" || $_POST['nombre_update'] == "" || $_POST['apellido_update'] == "" || $_POST['correo_update'] == "" || $_POST['contrasena_update'] == "" || $_POST['usuario_update'] == "" || $_POST['estatus_update'] == "") {
						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Atención',
								'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[0-9]{1,10}", $_POST['cedula_update'])) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten numeros en el campo cédula del usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $_POST['nombre_update'])) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $_POST['apellido_update'])) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el apellido del usuario.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					////



					$id_usuario = $_POST['id_usuario_update'];

					$extraer_datos_usuario = $modelUsuarios->obtenerUsuario($id_usuario);

					foreach ($extraer_datos_usuario as $extraer_datos_usuario) {
						$foto_tbl_usuario 		= $extraer_datos_usuario['foto'];
					}

					//var_dump($foto_tbl_usuario); die();

					$route_photo = './foto_usuario/' . $foto_tbl_usuario;

					$imagen = $route_photo;

					if (file_exists($imagen)) {
						if (unlink($imagen)) {
							//echo "La imagen se ha eliminado correctamente.";
						} else {
							//echo "No se pudo eliminar la imagen.";
						}
					} else {
						//echo "La imagen no existe.";
					}

					$datos = array(
						'cedula'    	=> $_POST['cedula_update'],
						'usuario'		=> $_POST['usuario_update'],
						'nombre'		=> $_POST['nombre_update'],
						'apellido'		=> $_POST['apellido_update'],
						'correo'		=> $_POST['correo_update'],
						'foto'			=> $fileName,
						'contrasena'	=> $_POST['contrasena_update'],
						'rol'			=> $_POST['rol_update'],
						'estatus'		=> $_POST['estatus_update'],
					);

					$resultado = $modelUsuarios->modificarUsuario($id_usuario, $datos);

					if ($resultado) {
						$data = [
							'data' => [
								'success'            =>  true,
								'message'            => 'Guardado exitosamente',
								'info'               =>  'Los datos del usuario han sido modificados'
							],
							'code' => 1,
						];

						echo json_encode($data);
						exit();
					} else {
						$data = [
							'data' => [
								'success'            =>  false,
								'message'            => 'Ocurrió un error al modificar los datos del usuario',
								'info'               =>  ''
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}
				} else {
					$data = [
						'data' => [
							'success'            =>  false,
							'message'            => 'No se copio la imagen',
							'info'               =>  ''
						],
						'code' => 0,
					];

					echo json_encode($data);
					exit();
				}
			} else {
				//$validator['messages'] = 'SOLO SE PERMITE FORMATOS JPG, PNG Y JPEG.';

				$data = [
					'data' => [
						'success'            =>  false,
						'message'            => 'Solo se permiten formatos jpg, png y jpeg.',
						'info'               =>  ''
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}
		} else {

			$modelUsuarios = new UsuarioModel();
			$id_usuario = $_POST['id_usuario_update'];

			$datos = array(
				'cedula'    	=> $_POST['cedula_update'],
				'usuario'		=> $_POST['usuario_update'],
				'nombre'		=> $_POST['nombre_update'],
				'apellido'		=> $_POST['apellido_update'],
				'correo'		=> $_POST['correo_update'],
				'contrasena'	=> $_POST['contrasena_update'],
				'rol'			=> $_POST['rol_update'],
				'estatus'		=> $_POST['estatus_update'],
			);

			$resultado = $modelUsuarios->modificarUsuario($id_usuario, $datos);

			if ($resultado) {
				$data = [
					'data' => [
						'success'            =>  true,
						'message'            => 'Guardado exitosamente',
						'info'               =>  'Los datos del usuario han sido modificados'
					],
					'code' => 1,
				];

				echo json_encode($data);
				exit();
			} else {
				$data = [
					'data' => [
						'success'            =>  false,
						'message'            => 'Ocurrió un error al modificar los datos del usuario',
						'info'               =>  ''
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}
		}
	}
	/*----------Metodo para inactivar Usuario-------*/

	public function inactivarUsuario()
	{

		$modelUsuarios = new UsuarioModel();
		$id_usuario = $_POST['id_usuario'];

		$estado = $modelUsuarios->obtenerUsuario($id_usuario);

		foreach ($estado as $estado) {
			$estado_usuario = $estado['estatus'];
		}

		if ($estado_usuario == 1) {
			$datos = array(
				'estatus'		=> 0,
			);

			$resultado = $modelUsuarios->modificarUsuario($id_usuario, $datos);
		} else {
			$datos = array(
				'estatus'		=> 1,
			);

			$resultado = $modelUsuarios->modificarUsuario($id_usuario, $datos);
		}

		if ($resultado) {
			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Guardado exitosamente',
					'info'               =>  'El estado del usuario ha sido modificado'
				],
				'code' => 1,
			];

			echo json_encode($data);
			exit();
		} else {
			$data = [
				'data' => [
					'success'            =>  false,
					'message'            => 'Ocurrió un error al modificar el estado del usuario',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}
}
