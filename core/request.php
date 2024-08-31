<?php

require_once 'config.php';

$page = $_GET['page'];

if (!empty($page)) {
	#http://crud-mvc/index.php?page=insertar
	$data = array(
		'inicio' => array('model' => 'dashboardModel', 'view' => 'inicio', 'controller' => 'dashboardController'),
		'inicioProfile' => array('model' => 'dashboardModel', 'view' => 'inicioProfile', 'controller' => 'dashboardController'),

		'inicioUsuario' => array('model' => 'UsuarioModel', 'view' => 'inicioUsuario', 'controller' => 'UsuarioController'),
		'loginUsuario' => array('model' => 'UsuarioModel', 'view' => 'loginUsuario', 'controller' => 'UsuarioController'),
		'logoutUsuario' => array('model' => 'UsuarioModel', 'view' => 'logoutUsuario', 'controller' => 'UsuarioController'),
		/*Url Modulo Usuario*/
		'listarUsuarios' => array('model' => 'UsuarioModel', 'view' => 'listarUsuarios', 'controller' => 'UsuarioController'),
		'ModuloUsuario' => array('model' => 'UsuarioModel', 'view' => 'ModuloUsuario', 'controller' => 'UsuarioController'),
		'registrarUsuario' => array('model' => 'UsuarioModel', 'view' => 'registrarUsuario', 'controller' => 'UsuarioController'),
		'verUsuario' => array('model' => 'UsuarioModel', 'view' => 'verUsuario', 'controller' => 'UsuarioController'),
		'modificarUsuario' => array('model' => 'UsuarioModel', 'view' => 'modificarUsuario', 'controller' => 'UsuarioController'),
		'inactivarUsuario' => array('model' => 'UsuarioModel', 'view' => 'inactivarUsuario', 'controller' => 'UsuarioController'),
		'registrarUsuarioConFoto' => array('model' => 'UsuarioModel', 'view' => 'registrarUsuarioConFoto', 'controller' => 'UsuarioController'),

		/* Modulo Roles */
		'inicioRoles' => array('model' => 'RolesModel', 'view' => 'inicioRoles', 'controller' => 'RolesController'),
		'listarRoles' => array('model' => 'RolesModel', 'view' => 'listarRoles', 'controller' => 'RolesController'),
		'registrarRoles' => array('model' => 'RolesModel', 'view' => 'registrarRoles', 'controller' => 'RolesController'),
		'verRoles' => array('model' => 'RolesModel', 'view' => 'verRoles', 'controller' => 'RolesController'),
		'modificarRoles' => array('model' => 'RolesModel', 'view' => 'modificarRoles', 'controller' => 'RolesController'),
		'inactivarRoles' => array('model' => 'RolesModel', 'view' => 'inactivarRoles', 'controller' => 'RolesController'),

		/* Modulo de jornadas*/
		'inicioJornadas' => array('model' => 'JornadasModel', 'view' => 'inicioJornadas', 'controller' => 'JornadasController'),
		'listarJornadas' => array('model' => 'JornadasModel', 'view' => 'listarJornadas', 'controller' => 'JornadasController'),
		'listarActualizarcionJornada' => array('model' => 'JornadasModel', 'view' => 'listarActualizarcionJornada', 'controller' => 'JornadasController'),
		'verJornada' => array('model' => 'JornadasModel', 'view' => 'verJornada', 'controller' => 'JornadasController'),
		'registrarJornada' => array('model' => 'JornadasModel', 'view' => 'registrarJornada', 'controller' => 'JornadasController'),
		'modificarJornadas' => array('model' => 'JornadasModel', 'view' => 'modificarJornadas', 'controller' => 'JornadasController'),
		'ObtenerDatosModificarEspecieJornada' => array('model' => 'JornadasModel', 'view' => 'ObtenerDatosModificarEspecieJornada', 'controller' => 'JornadasController'),
		'modificarEspecieJornada' => array('model' => 'JornadasModel', 'view' => 'modificarEspecieJornada', 'controller' => 'JornadasController'),
		'llenarSelectEstado' => array('model' => 'JornadasModel', 'view' => 'llenarSelectEstado', 'controller' => 'JornadasController'),
		'llenarSelectParroquia' => array('model' => 'JornadasModel', 'view' => 'llenarSelectParroquia', 'controller' => 'JornadasController'),
		'finalizarjornada' => array('model' => 'JornadasModel', 'view' => 'finalizarjornada', 'controller' => 'JornadasController'),
		'listarMatriz' => array('model' => 'JornadasModel', 'view' => 'listarMatriz', 'controller' => 'JornadasController'),
		'reporteFichaJornada' => array('model' => 'JornadasModel', 'view' => 'reporteFichaJornada', 'controller' => 'JornadasController'),
		'reporteMatrizJornada' => array('model' => 'JornadasModel', 'view' => 'reporteMatrizJornada', 'controller' => 'JornadasController'),
		
		/* Modulo Especies */
		'inicioEspecie' => array('model' => 'EspecieModel', 'view' => 'inicioEspecie', 'controller' => 'EspecieController'),
		'listarEspecies' => array('model' => 'EspecieModel', 'view' => 'listarEspecies', 'controller' => 'EspecieController'),
		'registrarEspecies' => array('model' => 'EspecieModel', 'view' => 'registrarEspecies', 'controller' => 'EspecieController'),
		'registrarEspecieUpdate' => array('model' => 'EspecieModel', 'view' => 'registrarEspecieUpdate', 'controller' => 'EspecieController'),
		'verEspecies' => array('model' => 'EspecieModel', 'view' => 'verEspecies', 'controller' => 'EspecieController'),
		'modificarEspecies' => array('model' => 'EspecieModel', 'view' => 'modificarEspecies', 'controller' => 'EspecieController'),
		'eliminarEspecieUpdate' => array('model' => 'EspecieModel', 'view' => 'eliminarEspecieUpdate', 'controller' => 'EspecieController'),
		'inactivarEspecies' => array('model' => 'EspecieModel', 'view' => 'inactivarEspecies', 'controller' => 'EspecieController'),

		/* Modulo Presentacion */
		'inicioPresentacion' => array('model' => 'PresentacionModel', 'view' => 'inicioPresentacion', 'controller' => 'PresentacionController'),
		'listarPresentacion' => array('model' => 'PresentacionModel', 'view' => 'listarPresentacion', 'controller' => 'PresentacionController'),
		'registrarPresentacion' => array('model' => 'PresentacionModel', 'view' => 'registrarPresentacion', 'controller' => 'PresentacionController'),
		'verPresentacion' => array('model' => 'PresentacionModel', 'view' => 'verPresentacion', 'controller' => 'PresentacionController'),
		'modificarPresentacion' => array('model' => 'PresentacionModel', 'view' => 'modificarPresentacion', 'controller' => 'PresentacionController'),
		'inactivarPresentacion' => array('model' => 'PresentacionModel', 'view' => 'inactivarPresentacion', 'controller' => 'PresentacionController'),

		/* Modulo Tipos de personas */
		'inicioTipopersona' => array('model' => 'TipopersonaModel', 'view' => 'inicioTipopersona', 'controller' => 'TipopersonaController'),
		'listarTipopersona' => array('model' => 'TipopersonaModel', 'view' => 'listarTipopersona', 'controller' => 'TipopersonaController'),
		'registrarTipopersona' => array('model' => 'TipopersonaModel', 'view' => 'registrarTipopersona', 'controller' => 'TipopersonaController'),
		'verTipopersona' => array('model' => 'TipopersonaModel', 'view' => 'verTipopersona', 'controller' => 'TipopersonaController'),
		'modificarTipopersona' => array('model' => 'TipopersonaModel', 'view' => 'modificarTipopersona', 'controller' => 'TipopersonaController'),
		'inactivarTipopersona' => array('model' => 'TipopersonaModel', 'view' => 'inactivarTipopersona', 'controller' => 'TipopersonaController'),

		/* Modulo Tipos de Beneficiarios */
		'inicioBeneficiarios' => array('model' => 'BeneficiariosModel', 'view' => 'inicioBeneficiarios', 'controller' => 'BeneficiariosController'),
		'listarBeneficiarios' => array('model' => 'BeneficiariosModel', 'view' => 'listarBeneficiarios', 'controller' => 'BeneficiariosController'),
		'registrarBeneficiarios' => array('model' => 'BeneficiariosModel', 'view' => 'registrarBeneficiarios', 'controller' => 'BeneficiariosController'),
		'verBeneficiarios' => array('model' => 'BeneficiariosModel', 'view' => 'verBeneficiarios', 'controller' => 'BeneficiariosController'),
		'modificarBeneficiarios' => array('model' => 'BeneficiariosModel', 'view' => 'modificarBeneficiarios', 'controller' => 'BeneficiariosController'),
		'inactivarBeneficiarios' => array('model' => 'BeneficiariosModel', 'view' => 'inactivarBeneficiarios', 'controller' => 'BeneficiariosController'),
		/* Modulo Tipos de Personas*/
		'inicioPersonas' => array('model' => 'PersonasModel', 'view' => 'inicioPersonas', 'controller' => 'PersonasController'),
		'listarPersonas' => array('model' => 'PersonasModel', 'view' => 'listarPersonas', 'controller' => 'PersonasController'),
		'registrarPersonas' => array('model' => 'PersonasModel', 'view' => 'registrarPersonas', 'controller' => 'PersonasController'),
		'verPersonas' => array('model' => 'PersonasModel', 'view' => 'verPersonas', 'controller' => 'PersonasController'),
		'modificarPersonas' => array('model' => 'PersonasModel', 'view' => 'modificarPersonas', 'controller' => 'PersonasController'),
		'inactivarPersonas' => array('model' => 'PersonasModel', 'view' => 'inactivarPersonas', 'controller' => 'PersonasController'),

		/* Especies temporal */
		'registrarEspecieTemporal' => array('model' => 'EspecieModel', 'view' => 'registrarEspecieTemporal', 'controller' => 'EspecieController'),
		'eliminarEspecieTemporal' => array('model' => 'EspecieModel', 'view' => 'eliminarEspecieTemporal', 'controller' => 'EspecieController'),

	);

	foreach ($data as $key => $components) {
		if ($page == $key) {
			$model = $components['model'];
			$view = $components['view'];
			$controller = $components['controller'];
		}
	}

	if (isset($model)) {
		require_once 'controllers/' . $controller . '.php';
		$objeto = new $controller();
		$objeto->$view();
	}
} else {
	header('Location: index.php?page=inicioUsuario');
}
