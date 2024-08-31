<?php

require_once './models/VisitanteModel.php';
require_once './models/PersonalModel.php';
require_once './config/validacion.php';


class VisitanteController
{

	#estableciendo las vistas
	public function inicioVisitantes()
	{

		/*HEADER */
		require_once('./views/includes/cabecera.php');

		require_once('./views/paginas/visitantes/inicioVisitantes.php');

		/* FOOTER */
		require_once('./views/includes/pie.php');
	}



	public function listarVisitantes()
	{

		// Database connection info 
		$dbDetails = array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'db'   => 'bd_visitante'
		);

		// DB table to use 
		$table = <<<EOT
	(
		SELECT ves_per.id, personas.cedula, personas.nombre, personas.apellido, ves_per.empresa, visitas.asunto, visitas.piso, ves_per.fecha,ves_per.hora,ves_per.meridiano,ves_per.foto,departamentos.nombre AS departamentos,visitas.estatus FROM ves_per AS ves_per JOIN visitas AS visitas ON ves_per.id_visitantes=visitas.id JOIN personas AS personas ON ves_per.id_personas=personas.id JOIN departamentos AS departamentos ON departamentos.id=visitas.departamento ORDER BY id DESC
	) temp
	EOT;



		// Table's primary key 
		$primaryKey = 'id';

		// Array of database columns which should be read and sent back to DataTables. 
		// The `db` parameter represents the column name in the database.  
		// The `dt` parameter represents the DataTables column identifier. 
		$columns = array(

			array('db' => 'id', 		'dt' => 0),
			array('db' => 'cedula',     	'dt' => 1),
			array('db' => 'nombre',     	'dt' => 2),
			array('db' => 'apellido',     	'dt' => 3),
			array('db' => 'piso',     	'dt' => 4),
			array('db' => 'fecha',     	'dt' => 5),
			array('db' => 'hora',     	'dt' => 6),
			array('db' => 'meridiano',     	'dt' => 7),
			array('db' => 'departamentos',     	'dt' => 8),
			array(
				'db'        => 'estatus',
				'dt'        => 9,
				'formatter' => function ($d, $row) {
					return ($d == 1) ? '<button class="btn btn-success btn-sm">Activo</button>' : '<button class="btn btn-danger btn-sm">Salida</button>';
				}
			),
			array('db' => 'id', 'dt' => 10),
			array('db' => 'estatus', 'dt' => 11)


		);

		// Include SQL query processing class 
		require './config/ssp.class.php';

		// Output data as json format 
		echo json_encode(
			SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
		);
	}

	public function registrarVisitante()
	{

		$personalModel = new PersonalModel();

		$validator = array('success' => false, 'messages' => array());

		//Validar visitante con personal no deseado

		$validator_personal = $personalModel->obtenerPersonalCedula($_POST['cedula']);

		foreach ($validator_personal as $validator_personal) {
			$id_personal_no_deseado = $validator_personal['id'];
		}

		if (!empty($id_personal_no_deseado)) {
			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Atención',
					'info'         => 'El visitante que intenta ingresar no esta autorizado para el ingreso al ente.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}


		if (!empty($_FILES["archivo"]["name"])) {

			$modelvisitante = new VisitanteModel();

			$fileName = basename($_FILES["archivo"]["name"]);
			$targetFilePath = './foto/' . $fileName;
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

			$allowTypes = array('jpg', 'png', 'jpeg');
			if (in_array($fileType, $allowTypes)) {
				if (copy($_FILES["archivo"]["tmp_name"], $targetFilePath)) {

					$uploadedFile 			= $fileName;
					$cedula 				= Validacion::limpiar_cadena($_POST['cedula']);
					$nombre 				= Validacion::limpiar_cadena($_POST['nombre']);
					$apellido       		= Validacion::limpiar_cadena($_POST['apellido']);
					$autorizador   			= Validacion::limpiar_cadena($_POST['autorizador']);
					$departamento			= Validacion::limpiar_cadena($_POST['departamento']);
					$piso 					= Validacion::limpiar_cadena($_POST['piso']);
					$estatus 				= Validacion::limpiar_cadena($_POST['estatus']);
					$asunto					= Validacion::limpiar_cadena($_POST['asunto']);
					$empresa				= Validacion::limpiar_cadena($_POST['empresa']);
					$telefono				= Validacion::limpiar_cadena($_POST['telefono']);
					$codigo_carnet			= Validacion::limpiar_cadena($_POST['codigo_carnet']);
					$fecha_actual 			= date("Y-m-d");
					$hora 					= date("H:i:s");
					$meridien			= date("A");

					//  var_dump($meridiano); die();

					/* comprobar campos vacios */
					if ($cedula == "" || $nombre == "" || $apellido == "" || $autorizador == "" || $departamento == "" || $piso == "" || $estatus == "" || $asunto == "" || $empresa == "" || $fileName == "" || $telefono == "" || $codigo_carnet == "") {
						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[0-9]{1,10}", $cedula)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten numeros en el campo cédula del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el apellido del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $empresa)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre de la empresa.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $autorizador)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del autorizador.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}


					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $asunto)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el campo asunto.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[0-9]{1,20}", $telefono)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten numeros en el campo telefono del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}


					//Validar que el visitante no ingrese dos veces al sistema el mismo día

					//	$entrada_visitante_hoy = $modelvisitante->validarEntradaDia($cedula, $fecha_actual);

					//	foreach ($entrada_visitante_hoy as $entrada_visitante_hoy) {
					//		$id_entrada_visitante = $entrada_visitante_hoy['id'];
					//	}



					//if (!empty($id_entrada_visitante)) {
					//	$data = [
					//		'data' => [
					//			'success'            =>  false,
					//			'message'            => 'El visitante ya ha sido ingresado el día de hoy',
					//			'info'               =>  'Fecha de hoy ' . $fecha_actual . ''
					//	],
					//'code' => 0,
					//						];

					//					echo json_encode($data);
					//				exit();
					//		}


					/* Registrar personas visitante */
					$datos_personas = array(
						'cedula'    	=> $cedula,
						'nombre'		=> $nombre,
						'apellido'		=> $apellido,
					);

					$registro_personas = $modelvisitante->registrarPersonaVisitante($datos_personas);

					$id_persona = $registro_personas['ultimo_id'];

					/* Registrar persona visitante */

					/* Registrar visita */
					$datos_visita = array(
						'autorizador'    	=> $autorizador,
						'departamento'		=> $departamento,
						'piso'				=> $piso,
						'estatus'			=> $estatus,
						'asunto'			=> $asunto,
					);

					$registro_visitas = $modelvisitante->registrarVisita($datos_visita);

					$id_visitante = $registro_visitas['ultimo_id'];
					/* Fin registrar visita */

					/* Regisrar visitante */
					$datos_visitante = array(
						'id_visitantes'    	=> $id_visitante,
						'id_personas'		=> $id_persona,
						'empresa'			=> $empresa,
						'telefono'			=> $telefono,
						'codigo_carnet'		=> $codigo_carnet,
						'foto'				=> $fileName,
						'fecha'				=> $fecha_actual,
						'hora'				=> $hora,
						'meridiano'				=> $meridien,

					);

					$registro_visitante = $modelvisitante->registrarVisitante($datos_visitante);
					/* Registrar visitante */

					if ($registro_personas && $registro_visitas && $registro_visitante) {
						$data = [
							'data' => [
								'success'            =>  true,
								'message'            => 'Guardado exitosamente',
								'info'               =>  'El visitante se registro en la base de datos.'
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
					'message'      => 'Error',
					'info'         => 'Debes subir una foto o tomar una.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	public function registrarVisitanteConFoto()
	{
		//Instanciar el model visitantes
		$modelvisitante = new VisitanteModel();

		//Recuperar el ultimo id para concatenar con cedula y crear el nombre de la foto
		$ultimo_id = $modelvisitante->obtenerID();
		foreach ($ultimo_id as $ultimo_id) {
			$get_ultimo_id = $ultimo_id['id'];
		}

		if (empty($get_ultimo_id)) {
			$get_ultimo_id = 0;
		}

		$get_ultimo_id = $get_ultimo_id + 1;

		//Recibir datos por post
		$cedula 				= Validacion::limpiar_cadena($_POST['cedula']);
		$nombre 				= Validacion::limpiar_cadena($_POST['nombre']);
		$apellido       		= Validacion::limpiar_cadena($_POST['apellido']);
		$autorizador   			= Validacion::limpiar_cadena($_POST['autorizador']);
		$departamento			= Validacion::limpiar_cadena($_POST['departamento']);
		$piso 					= Validacion::limpiar_cadena($_POST['piso']);
		$estatus 				= Validacion::limpiar_cadena($_POST['estatus']);
		$asunto					= Validacion::limpiar_cadena($_POST['asunto']);
		$empresa				= Validacion::limpiar_cadena($_POST['empresa']);
		$foto 					= base64_decode($_POST['foto']);
		$hora 					= date("H:i:s");
		$meridien 				= date("A");
		$validator = array('success' => false, 'messages' => array());



		$route_photo = "./foto/foto_" . $_POST['cedula'] . $get_ultimo_id . ".jpg";
		$name_photo = "foto_" . $_POST['cedula'] . $get_ultimo_id . ".jpg";
		$file = fopen($route_photo, "w");



		if ($file) {
			$fotos = fwrite($file, $foto);
			fclose($file);

			$fecha_actual = date("Y-m-d");

			/* Registrar personas visitante */
			$datos_personas = array(
				'cedula'    	=> $_POST['cedula'],
				'nombre'		=> $_POST['nombre'],
				'apellido'		=> $_POST['apellido'],
			);

			/* comprobar campos vacios */
			if ($cedula == "" || $nombre == "" || $apellido == "" || $autorizador == "" || $departamento == "" || $piso == "" || $estatus == "" || $asunto == "" || $empresa == "") {
				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Verifica que todos los campos estén llenos a la hora de crear un vistante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[0-9]{1,10}", $cedula)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten numeros en el campo cédula del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el apellido del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $empresa)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre de la empresa.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $autorizador)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del autorizador.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $asunto)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el campo asunto.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			//Validar que el visitante no ingrese dos veces al sistema el mismo día

			//	$entrada_visitante_hoy = $modelvisitante->validarEntradaDia($cedula, $fecha_actual);

			//			foreach ($entrada_visitante_hoy as $entrada_visitante_hoy) {
			//	$id_entrada_visitante = $entrada_visitante_hoy['id'];
			//}



			//if (!empty($id_entrada_visitante)) {
			//$data = [
			//'data' => [
			//'success'            =>  false,
			//'message'            => 'El visitante ya ha sido ingresado el día de hoy',
			//'info'               =>  'Fecha de hoy ' . $fecha_actual . ''
			//],
			//'code' => 0,
			//];

			//echo json_encode($data);
			//exit();
			//}

			$registro_personas = $modelvisitante->registrarPersonaVisitante($datos_personas);

			$id_persona = $registro_personas['ultimo_id'];

			/* Registrar persona visitante */

			/* Registrar visita */
			$datos_visita = array(
				'autorizador'    	=> $_POST['autorizador'],
				'departamento'		=> $_POST['departamento'],
				'piso'				=> $_POST['piso'],
				'estatus'			=> $_POST['estatus'],
				'asunto'			=> $_POST['asunto'],
			);

			$registro_visitas = $modelvisitante->registrarVisita($datos_visita);

			$id_visitante = $registro_visitas['ultimo_id'];
			/* Fin registrar visita */

			/* Regisrar visitante */
			$datos_visitante = array(
				'id_visitantes'    	=> $id_visitante,
				'id_personas'		=> $id_persona,
				'empresa'			=> $_POST['empresa'],
				'foto'				=> $name_photo,
				'fecha'				=> $fecha_actual,
				'hora'				=> $hora,
				'meridiano'			=> $meridien,


			);

			$registro_visitante = $modelvisitante->registrarVisitante($datos_visitante);
			/* Registrar 
			
			visitante */

			if ($registro_personas && $registro_visitas && $registro_visitante) {
				$data = [
					'data' => [
						'success'            =>  true,
						'message'            => 'Guardado exitosamente',
						'info'               =>  'El vistante se registro en la base de datos.'
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
					'message'            => 'Error al guardar la foto',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	public function verVisitante()
	{
		$modelvisitante = new VisitanteModel();

		$id_visitante = $_POST['id_visitante'];

		$listar = $modelvisitante->obtenerVisitante($id_visitante);

		foreach ($listar as $listar) {

			$id_visitante 		= $listar['id'];
			$cedula 			= $listar['cedula'];
			$nombre 			= $listar['nombre'];
			$apellido 			= $listar['apellido'];
			$asunto 			= $listar['asunto'];
			$autorizador    	= $listar['autorizador'];
			$departamento 		= $listar['departamento'];
			$empresa 			= $listar['empresa'];
			$telefono 			= $listar['telefono'];
			$codigo_carnet	 	= $listar['codigo_carnet'];
			$piso 				= $listar['piso'];
			$id_departamento 	= $listar['id_departamento'];
			$foto 				= $listar['foto'];
			$fecha 				= $listar['fecha'];
			$estatus 			= $listar['estatus'];
		}

		$data = [
			'data' => [
				'success'            =>  true,
				'message'            => 'Registro encontrado',
				'info'               =>  '',
				'id'				 => $id_visitante,
				'cedula'    		 => $cedula,
				'nombre'			 => $nombre,
				'apellido'			 => $apellido,
				'asunto'			 => $asunto,
				'autorizador'		 => $autorizador,
				'departamento'		 => $departamento,
				'empresa'			 => $empresa,
				'telefono'			 => $telefono,
				'codigo_carnet'		 => $codigo_carnet,
				'piso'			     => $piso,
				'id_departamento'	 => $id_departamento,
				'foto'			     => $foto,
				'fecha'			     => $fecha,
				'estatus'		     => $estatus,
			],
			'code' => 0,
		];

		echo json_encode($data);

		exit();
	}

	public function modificarVisitanteConFoto()
	{
		//Instanciar el model visitantes
		$modelvisitante = new VisitanteModel();

		//Recibir datos por post
		$cedula 				= Validacion::limpiar_cadena($_POST['cedula']);
		$nombre 				= Validacion::limpiar_cadena($_POST['nombre']);
		$apellido       		= Validacion::limpiar_cadena($_POST['apellido']);
		$autorizador   			= Validacion::limpiar_cadena($_POST['autorizador']);
		$departamento			= Validacion::limpiar_cadena($_POST['departamento']);
		$piso 					= Validacion::limpiar_cadena($_POST['piso']);
		$estatus 				= Validacion::limpiar_cadena($_POST['estatus']);
		$asunto					= Validacion::limpiar_cadena($_POST['asunto']);
		$empresa				= Validacion::limpiar_cadena($_POST['empresa']);
		$telefono				= Validacion::limpiar_cadena($_POST['telefono']);
		$codigo_carnet			= Validacion::limpiar_cadena($_POST['codigo_carnet']);
		$id_visitante_update	= $_POST['id_visitante_update'];
		$foto 					= base64_decode($_POST['foto']);

		$validator 				= array('success' => false, 'messages' => array());
		$route_photo 			= "./foto/foto_" . $_POST['cedula'] . $id_visitante_update . ".jpg";

		//Reemplazar imagen por nueva imagen
		$imagen = $route_photo;

		//var_dump($imagen); die();

		if (file_exists($imagen)) {
			if (unlink($imagen)) {
				//echo "La imagen se ha eliminado correctamente.";
			} else {
				//echo "No se pudo eliminar la imagen.";
			}
		} else {
			//echo "La imagen no existe.";
		}

		$name_photo 			= "foto_" . $_POST['cedula'] . $id_visitante_update . ".jpg";
		$file 					= fopen($route_photo, "w");



		if ($file) {
			$fotos = fwrite($file, $foto);
			fclose($file);

			//$fecha_actual = date("Y-m-d");

			/* Registrar personas visitante */
			$datos_personas = array(
				'cedula'    	=> $_POST['cedula'],
				'nombre'		=> $_POST['nombre'],
				'apellido'		=> $_POST['apellido'],
			);

			//var_dump($datos_personas); die();

			/* comprobar campos vacios */
			if ($cedula == "" || $nombre == "" || $apellido == "" || $autorizador == "" || $departamento == "" || $piso == "" || $estatus == "" || $asunto == "" || $empresa == "") {
				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Verifica que todos los campos estén llenos a la hora de crear un visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[0-9]{1,10}", $cedula)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten numeros en el campo cédula del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el apellido del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $empresa)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre de la empresa.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $autorizador)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del autorizador.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $asunto)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el campo asunto.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}
			if (Validacion::verificar_datos("[0-9]{1,20}", $telefono)) {

				$data = [
					'data' => [
						'error'        => true,
						'message'      => 'Datos inválidos',
						'info'         => 'Solo se permiten numeros en el campo telefono del visitante.'
					],
					'code' => 0,
				];

				echo json_encode($data);
				exit();
			}

			$extraer_datos_persona = $modelvisitante->obtenerCedulaPersona($id_visitante_update);

			foreach ($extraer_datos_persona as $extraer_datos_persona) {
				$id_persona = $extraer_datos_persona['id_personas'];
			}

			$registro_personas = $modelvisitante->modificarPersonaVisitante($id_persona, $datos_personas);


			/* Registrar persona visitante */

			/* Registrar visita */
			$datos_visita = array(
				'autorizador'    	=> $_POST['autorizador'],
				'departamento'		=> $_POST['departamento'],
				'piso'				=> $_POST['piso'],
				'estatus'			=> $_POST['estatus'],
				'asunto'			=> $_POST['asunto'],
			);

			$obtener_id_visita = $modelvisitante->obtenerIdVisita($id_visitante_update);

			foreach ($obtener_id_visita as $obtener_id_visita) {
				$id_visita = $obtener_id_visita['id_visitantes'];
			}


			$registro_visitas = $modelvisitante->modificarVisitaVisitante($id_visita, $datos_visita);


			/* Fin registrar visita */

			/* Regisrar visitante */
			$datos_visitante = array(
				'empresa'			=> $_POST['empresa'],
				'telefono'			=> $_POST['telefono'],
				'codigo_carnet'			=> $_POST['codigo_carnet'],
				'foto'				=> $name_photo,
			);

			$registro_visitante = $modelvisitante->modificarDatosVisita($id_visitante_update, $datos_visitante);

			/* Registrar visitante */

			if ($registro_personas && $registro_visitas && $registro_visitante) {
				$data = [
					'data' => [
						'success'            =>  true,
						'message'            => 'Guardado exitosamente',
						'info'               => 'Los datos del visitante fueron modificados.',
						'route_photo'		 => $route_photo
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
					'message'            => 'Error al guardar la foto',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}


	public function modificarVisitante()
	{

		$validator = array('success' => false, 'messages' => array());

		if (!empty($_FILES["archivo"]["name"])) {

			//Instancio el modelo
			$modelvisitante = new VisitanteModel();

			/* Nombre de la foto */
			$fileName = basename($_FILES["archivo"]["name"]);

			/* Ruta de la foto + el nombre */
			$targetFilePath = './foto/' . $fileName;


			/* Tipo de archivo */
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

			$allowTypes = array('jpg', 'png', 'jpeg');
			if (in_array($fileType, $allowTypes)) {
				if (copy($_FILES["archivo"]["tmp_name"], $targetFilePath)) {

					$uploadedFile 			= $fileName;
					$cedula 				= Validacion::limpiar_cadena($_POST['cedula_update']);
					$nombre 				= Validacion::limpiar_cadena($_POST['nombre_update']);
					$apellido       		= Validacion::limpiar_cadena($_POST['apellido_update']);
					$autorizador   			= Validacion::limpiar_cadena($_POST['autorizador_update']);
					$departamento			= Validacion::limpiar_cadena($_POST['departamento_update']);
					$piso 					= Validacion::limpiar_cadena($_POST['piso_update']);
					$estatus 				= Validacion::limpiar_cadena($_POST['estatus_update']);
					$asunto					= Validacion::limpiar_cadena($_POST['asunto_update']);
					$empresa				= Validacion::limpiar_cadena($_POST['empresa_update']);
					$telefono				= Validacion::limpiar_cadena($_POST['telefono_update']);
					$codigo_carnet			= Validacion::limpiar_cadena($_POST['codigo_carnet_update']);
					$id_visitante_update    = Validacion::limpiar_cadena($_POST['id_visitante_update']);

					/* Validar Campos */
					if ($cedula == "" || $nombre == "" || $apellido == "" || $autorizador == "" || $departamento == "" || $piso == "" || $estatus == "" || $asunto == "" || $empresa == "" || $fileName == "") {
						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[0-9]{1,10}", $cedula)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten numeros en el campo cédula del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el apellido del visitante.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $empresa)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre de la empresa.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $autorizador)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el nombre del autorizador.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}

					if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $asunto)) {

						$data = [
							'data' => [
								'error'        => true,
								'message'      => 'Datos inválidos',
								'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en el campo asunto.'
							],
							'code' => 0,
						];

						echo json_encode($data);
						exit();
					}
					/* Fin validar campos */


					/* Modificar personas visitante */
					$datos_personas = array(
						'cedula'    	=> $cedula,
						'nombre'		=> $nombre,
						'apellido'		=> $apellido,
					);


					$extraer_datos_persona = $modelvisitante->obtenerCedulaPersona($id_visitante_update);

					foreach ($extraer_datos_persona as $extraer_datos_persona) {
						$id_persona 			= $extraer_datos_persona['id_personas'];
						$foto_tbl_ves_per 		= $extraer_datos_persona['foto'];
					}

					$route_photo = './foto/' . $foto_tbl_ves_per;

					//var_dump($route_photo); die();
					//Reemplazar imagen por nueva imagen
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

					$registro_personas = $modelvisitante->modificarPersonaVisitante($id_persona, $datos_personas);
					/* Fin Moficar persona visitante */

					/* Registrar visita */
					$datos_visita = array(
						'autorizador'    	=> $_POST['autorizador_update'],
						'departamento'		=> $_POST['departamento_update'],
						'piso'				=> $_POST['piso_update'],
						'estatus'			=> $_POST['estatus_update'],
						'asunto'			=> $_POST['asunto_update'],
					);

					$obtener_id_visita = $modelvisitante->obtenerIdVisita($id_visitante_update);

					foreach ($obtener_id_visita as $obtener_id_visita) {
						$id_visita = $obtener_id_visita['id_visitantes'];
					}


					$registro_visitas = $modelvisitante->modificarVisitaVisitante($id_visita, $datos_visita);
					/* Fin registrar visita */

					/* Regisrar visitante */
					$datos_visitante = array(
						'empresa'			=> $_POST['empresa_update'],
						'telefono'			=> $_POST['telefono_update'],
						'codigo_carnet'			=> $_POST['codigo_carnet_update'],
						'foto'				=> $fileName,
					);

					$registro_visitante = $modelvisitante->modificarDatosVisita($id_visitante_update, $datos_visitante);

					/* Registrar visitante */

					if ($registro_personas && $registro_visitas && $registro_visitante) {
						$data = [
							'data' => [
								'success'            =>  true,
								'message'            => 'Guardado exitosamente',
								'info'               =>  'El visitante se registro en la base de datos.'
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
					'message'      => 'Error',
					'info'         => 'Debes subir una foto o tomar una.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	/* Consultar visitante por cédula */

	public function consultarVisitanteCedula()
	{
		$modelvisitante = new VisitanteModel();
		$personalModel = new PersonalModel();

		$cedula = $_POST['cedula_visitante'];
		$fecha_actual 			= date("Y-m-d");

		if ($cedula == "") {
			$data = [
				'data' => [
					'error'        => 'validacion',
					'message'      => 'Atención',
					'info'         => 'El campo cédula no debe estar vacío a la hora de consultar un visitante.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}

		if (Validacion::verificar_datos("[0-9]{1,10}", $cedula)) {

			$data = [
				'data' => [
					'error'        => 'validacion',
					'message'      => 'Atención',
					'info'         => 'Solo se permiten numeros en el campo cédula'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}

		//Validar visitante con personal no deseado

		$validator_personal = $personalModel->obtenerPersonalCedula($cedula);

		foreach ($validator_personal as $validator_personal) {
			$id_personal_no_deseado = $validator_personal['id'];
		}

		if (!empty($id_personal_no_deseado)) {
			$data = [
				'data' => [
					'error'        => 'validacion',
					'message'      => 'Atención',
					'info'         => 'El visitante que intenta ingresar no esta autorizado para el ingreso al ente.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}

		//Validar que el visitante no ingrese dos veces al sistema el mismo día

		//	$entrada_visitante_hoy = $modelvisitante->validarEntradaDia($cedula, $fecha_actual);

		//	foreach ($entrada_visitante_hoy as $entrada_visitante_hoy) {
		//		$id_entrada_visitante = $entrada_visitante_hoy['id'];
		//	}



		//	if (!empty($id_entrada_visitante)) {
		//		$data = [
		//			'data' => [
		//			'error'            =>  'validacion',
		//			'message'            => 'El visitante ya ha sido ingresado el día de hoy',
		//		'info'               =>  'Fecha de hoy ' . $fecha_actual . ''
		//	],
		//	'code' => 0,
		//	];

		//	echo json_encode($data);
		//		exit();
		//	}



		$listar = $modelvisitante->consultarVisitanteCedula($cedula);

		foreach ($listar as $listar) {

			$id_visitante 		= $listar['id'];
			$cedula 			= $listar['cedula'];
			$nombre 			= $listar['nombre'];
			$apellido 			= $listar['apellido'];
			$empresa 			= $listar['empresa'];
			$asunto 			= $listar['asunto'];
			$autorizador    	= $listar['autorizador'];
			$piso 				= $listar['piso'];
			$departamento 		= $listar['departamento'];
			$id_departamento 	= $listar['id_departamento'];
			$foto 				= $listar['foto'];
			$fecha 				= $listar['fecha'];
			$estatus 			= $listar['estatus'];
		}

		if (!empty($id_visitante)) {
			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Visitante encontrado',
					'info'               =>  '',
					'id'				 => $id_visitante,
					'cedula'    		 => $cedula,
					'nombre'			 => $nombre,
					'empresa'			 => $empresa,
					'piso'			     => $piso,
					'departamento'		 => $departamento,
					'id_departamento'	 => $id_departamento,
					'asunto'			 => $asunto,
					'autorizador'		 => $autorizador,
					'apellido'			 => $apellido,
					'foto'			     => $foto,
					'fecha'			     => $fecha,
					'estatus'		     => $estatus,
				],
				'code' => 0,
			];

			echo json_encode($data);

			exit();
		} else {

			$data = [
				'data' => [
					'error'        => true,
					'message'      => 'Atención',
					'info'         => 'El visitante no ha ingresado anteriormente llena el formulario de registro para el ingreso respectivo.'
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	/*----------Metodo para inactivar Visitante-------*/

	public function inactivarVisitante()
	{

		$modelvisitante = new VisitanteModel();

		$id_visitante = $_POST['id_visitante'];

		$estatus = $modelvisitante->ObtenerEstatus($id_visitante);

		foreach ($estatus as $estatus) {
			$estatus_Visitante = $estatus['estatus'];
			$id_visitantes = $estatus['id_visitantes'];
		}

		if ($estatus_Visitante == 1) {
			$datos = array(
				'estatus'		=> 0,
			);

			$resultado = $modelvisitante->modificarVisitaVisitante($id_visitantes, $datos);
		} else {
			$datos = array(
				'estatus'		=> 1,
			);

			$resultado = $modelvisitante->modificarVisitaVisitante($id_visitantes, $datos);
		}

		if ($resultado) {
			$data = [
				'data' => [
					'success'            =>  true,
					'message'            => 'Guardado exitosamente',
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
					'message'            => 'Ocurrió un error al darle salida al visitante.',
					'info'               =>  ''
				],
				'code' => 0,
			];

			echo json_encode($data);
			exit();
		}
	}

	/* Contador de visitantes del día actual*/

	public function visitantesDeHoy()
	{
		$modelvisitante = new VisitanteModel();

		$total_visitantes_hoy = $modelvisitante->visitantesDeHoy();

		return $total_visitantes_hoy;
	}

	/* Contador de los visitantes de ayer*/

	public function visitantesDeAyer()
	{
		$modelvisitante = new VisitanteModel();

		$total_visitantes_ayer = $modelvisitante->visitantesDeAyer();

		return $total_visitantes_ayer;
	}

	/* Contador de los visitantes de los ultimos 7 dias*/

	public function visitantesSieteDias()
	{
		$modelvisitante = new VisitanteModel();

		$total_visitantes_siete_dias = $modelvisitante->visitantesSieteDias();

		return $total_visitantes_siete_dias;
	}

	public function visitantesTotal()
	{
		$modelvisitante = new VisitanteModel();

		$total_visitantes = $modelvisitante->visitantesTotal();

		return $total_visitantes;
	}
}
