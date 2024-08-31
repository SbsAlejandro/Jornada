<?php

require_once './models/EspecieModel.php';
require_once './models/PresentacionModel.php';
require_once './models/JornadasModel.php';
require_once './config/validacion.php';


class EspecieController
{

    #estableciendo la vista del login
    public function inicioEspecie()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/especies/inicioEspecie.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }


    public function listarEspecies()
    {
        // Database connection info 
        $dbDetails = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db'   => 'jornada_diaria'
        );


        $table = 'Especies';

        // Table's primary key 
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables. 
        // The `db` parameter represents the column name in the database.  
        // The `dt` parameter represents the DataTables column identifier. 
        $columns = array(

            array('db' => 'id',         'dt' => 0),
            array('db' => 'especie',         'dt' => 1),
            array('db' => 'fecha_registro',         'dt' => 2),
            array(
                'db'        => 'estatus',
                'dt'        => 3,
                'formatter' => function ($d, $row) {
                    return ($d == 1) ? '<button class="btn btn-success btn-sm">Activo</button>' : '<button class="btn btn-danger btn-sm">Inactivo</button>';
                }
            ),
            array('db' => 'id', 'dt' => 4),
            array('db' => 'estatus', 'dt' => 5)
            //array( 'db' => 'fecha_registro','dt' => 9 ),

        );

        // Include SQL query processing class 
        require './config/ssp.class.php';

        // Output data as json format 
        echo json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function obtenerEspecies()
    {
        $modelespecies = new EspecieModel();

        return $especies = $modelespecies->listarEspecies();
    }
    public function registrarEspecies()
    {
        $modelespecies = new EspecieModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $especies                   = Validacion::limpiar_cadena($_POST['especies']);
        $estatus                    = Validacion::limpiar_cadena($_POST['estatus']);
        $fecha_registro              = date("Y-m-d");


        /* comprobar campos vacios */
        if ($_POST['especies'] == "" ||  $_POST['estatus'] == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Atención',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar una especie.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }


        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $_POST['especies'])) {

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

        //Validar que la especie no se repita
        $entrada_especies_hoy = $modelespecies->validarEntradaDiaEspecies($especies);

        foreach ($entrada_especies_hoy as $entrada_especies_hoy) {
            $id_entrada_especies = $entrada_especies_hoy['id'];
        }



        if (!empty($id_entrada_especies)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Atención',
                    'info'               =>  'La especie que intentas registrar ya existe en la base de datos.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }



        $datos = array(

            'especie'               => $especies,
            'fecha_registro'        => $fecha_registro,
            'estatus'               => $estatus,
        );

        $resultado = $modelespecies->registrarEspecies($datos);

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
    }

    public function registrarEspecieUpdate()
    {
        $modelespecies = new EspecieModel();
        $modelJornadas = new JornadasModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $id_especie_update                      = Validacion::limpiar_cadena($_POST['id_especie_update']);
        $id_presentacion_update                 = Validacion::limpiar_cadena($_POST['id_presentacion_update']);
        $precio_bs_update                       = Validacion::limpiar_cadena($_POST['precio_bs_update']);
        $disponibilidad_kl_update               = Validacion::limpiar_cadena($_POST['disponibilidad_kl_update']);
        $tasa_bcv_update                        = Validacion::limpiar_cadena($_POST['tasa_bcv_update']);
        $vendidos_kl_update                     = Validacion::limpiar_cadena($_POST['vendidos_kl_update']);
        $id_jornada_update                      = $_POST['id_jornada_update'];

        $resultado_precio_dolar = $precio_bs_update / $tasa_bcv_update;
        $precio_dolares         = number_format($resultado_precio_dolar, 2);

        //Validar que el Beneficiario no ingrese dos veces al sistema el mismo día
        $entrada_especie_hoy = $modelespecies->validarEntradaDiarEspecieUpdate($id_especie_update, $id_jornada_update);

        foreach ($entrada_especie_hoy as $entrada_especie_hoy) {
            $id_entrada_especie = $entrada_especie_hoy['id'];
        }

        if (!empty($id_entrada_especie)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Atención',
                    'info'               =>  'La especie ya ha sido registrada para esta jornada'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }





        /* comprobar campos vacios */
        if ($_POST['id_especie_update'] == "" || $_POST['id_presentacion_update'] == "" || $_POST['precio_bs_update'] == "" || $_POST['disponibilidad_kl_update'] == "" ||  $_POST['tasa_bcv_update'] == "" ||  $_POST['vendidos_kl_update'] == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Atención',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar una especie.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }


        $datos = array(
            'id_especie'               => $id_especie_update,
            'id_jornada'               => $id_jornada_update,
            'id_presentacion'          => $id_presentacion_update,
            'precio_bs'                => $precio_bs_update,
            'precio_dolares'           => $precio_dolares,
            'disponibilidad_kl'        => $disponibilidad_kl_update,
            'vendidos_kl'              => $vendidos_kl_update,
        );

        $resultado = $modelespecies->registrarEspeciesUpdate($datos);


        $especies_pescado = $modelJornadas->obtenerEspeciesPescadoUpdate($id_jornada_update);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'El usuario se registro en la base de datos.',
                    'especies_pescado'   => $especies_pescado
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
    }

    public function verEspecies()
    {
        $modelespecies = new EspecieModel();

        $id_especies = $_POST['id_especies'];

        $listar = $modelespecies->obtenerEspecies($id_especies);


        foreach ($listar as $listar) {

            $id_especies     = $listar['id'];
            $especie         = $listar['especie'];
            $fecha_registro            = $listar['fecha_registro'];
            $estatus         = $listar['estatus'];
        }

        $data = [
            'data' => [
                'success'               =>  true,
                'message'               => 'Registro encontrado',
                'info'                  =>  '',
                'id'                    => $id_especies,
                'especie'               => $especie,
                'fecha_registro'        => $fecha_registro,
                'estatus'               => $estatus,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }
    public function modificarEspecies()
    {

        $modelespecies = new EspecieModel();
        $id_especies = $_POST['id_especies'];
        /* --------- Funcion limpiar cadenas ---------*/

        $especie                = Validacion::limpiar_cadena($_POST['especie']);
        $estatus                 = Validacion::limpiar_cadena($_POST['estatus']);
        $fecha_registro = date("Y-m-d");

        //Validar que el especie no ingrese dos veces al sistema el mismo día
        $entrada_especies_hoy = $modelespecies->validarEntradaDiaEspecies($especie, $fecha_registro);

        foreach ($entrada_especies_hoy as $entrada_especies_hoy) {
            $id_entrada_especies = $entrada_especies_hoy['id'];
        }



        if (!empty($id_entrada_especies)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'La especie ya ha sido ingresado el día de hoy',
                    'info'               =>  'Fecha de hoy ' . $fecha_registro . ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        //caracteres especiales 
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $especie)) {

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

            'especie'        => $_POST['especie'],

            'estatus'        => $_POST['estatus'],
        );

        /* comprobar campos vacios */
        if ($especie == "" || $estatus == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar la especie.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }



        $resultado = $modelespecies->modificarEspecies($id_especies, $datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'Los datos de la especie han sido modificados'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar los datos de las especie',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
    /*----------Metodo para inactivar Visitante-------*/
    public function inactivarEspecies()
    {

        $modelespecies = new EspecieModel();
        $id_especies = $_POST['id_especies'];

        $estado = $modelespecies->obtenerEspecies($id_especies);

        foreach ($estado as $estado) {
            $estado_Especies = $estado['estatus'];
        }

        if ($estado_Especies == 1) {
            $datos = array(
                'estatus'        => 0,
            );

            $resultado = $modelespecies->modificarEspecies($id_especies, $datos);
        } else {
            $datos = array(
                'estatus'        => 1,
            );

            $resultado = $modelespecies->modificarEspecies($id_especies, $datos);
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
                    'message'            => 'Ocurrió un error al modificar el estado la especie',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }

    public function registrarEspecieTemporal()
    {
        session_start();


        $modelEspecies          = new EspecieModel();
        $modelPresentaciones    = new PresentacionModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $id_usuario             = $_SESSION['user_id'];
        $id_especie             = Validacion::limpiar_cadena($_POST['id_especie']);
        $id_presentacion        = Validacion::limpiar_cadena($_POST['id_presentacion']);
        $precio_bs              = Validacion::limpiar_cadena($_POST['precio_bs']);
        $disponibilidad_kl      = Validacion::limpiar_cadena($_POST['disponibilidad_kl']);
        $tasa_bcv               = $_POST['tasa_bcv'];

        $resultado_precio_dolar = $precio_bs / $tasa_bcv;
        $precio_dolares         = number_format($resultado_precio_dolar, 2);

        // CONSULTAR LOS DATOS DE LA ESPECIE Y DE LA PRESENTACION PARA ENVIARLO AL FRONT
        $obtener_especie            = $modelEspecies->obtenerEspecies($id_especie);
        $obtener_presentacion       = $modelPresentaciones->obtenerPresentacion($id_presentacion);

        foreach ($obtener_especie as $obtener_especie) {
            $id_especie_obtenida    = $obtener_especie['id'];
            $especie_obtenida       = $obtener_especie['especie'];
        }

        foreach ($obtener_presentacion as $obtener_presentacion) {
            $id_presentacion_obtenida       = $obtener_presentacion['id'];
            $descripcion_obtenida           = $obtener_presentacion['descripcion'];
        }

        $obtener_especie_presentacion_temporal = $modelEspecies->temporal_jornada_especie($id_especie, $id_presentacion);

        foreach ($obtener_especie_presentacion_temporal as $obtener_especie_presentacion_temporal) {
            $id_temporal_jornada_especie = $obtener_especie_presentacion_temporal['id'];
        }

        if (!empty($id_temporal_jornada_especie)) {
            $data = [
                'data' => [
                    'error'         => true,
                    'message'       => 'Atención',
                    'info'         => 'La especie y la presentación que intentas agregar ya existen en la base de datos'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }


        /* comprobar campos vacios */
        if ($id_especie == "") {
            $data = [
                'data' => [
                    'error'         => true,
                    'message'       => 'Atención',
                    'info'         => 'Verifica que los campos esten llenos a la hora de agregar una especie'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }


        $datos = array(
            'id_especie'        => $id_especie,
            'id_presentacion'   => $id_presentacion,
            'precio_bs'         => $precio_bs,
            'precio_dolares'    => $precio_dolares,
            'disponibilidad_kl' => $disponibilidad_kl,
            'id_usuario'        => $id_usuario,
        );


        $resultado = $modelEspecies->registrarEspeciePresentacionTemporal($datos);

        $id_especie_tbl_intermedia = $resultado['ultimo_id'];

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            =>  'Guardado exitosamente',
                    'info'               =>  'Especie agregada exitosamente',
                    'id_especie'         =>  $id_especie_tbl_intermedia,
                    'especie'            =>  $especie_obtenida,
                    'precio_bs'          =>  $precio_bs,
                    'precio_dolares'     =>  $precio_dolares,
                    'disponibilidad_kl'  =>  $disponibilidad_kl,
                    'presentacion'       =>  $descripcion_obtenida
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

    /* Eliminar Especie Temporal*/
    public function eliminarEspecieTemporal()
    {
        $modelEspecies = new EspecieModel();

        $id_especie = $_POST['id_especie'];


        $eliminar_item = $modelEspecies->eliminarEspecieTemporal($id_especie);

        $obtener_contador_especies_temporales = $modelEspecies->obtenerContadorEspeciesTemporales();

        foreach ($obtener_contador_especies_temporales as $obtener_contador_especies_temporales) {
            $contador_especies_temporales = $obtener_contador_especies_temporales['contador_especie_temporal'];
        }


        if ($eliminar_item) {
            $data = [
                'data' => [
                    'success'                =>  true,
                    'message'                => 'Especie removida exitosamente',
                    'info'                    =>  'Ahora puedes agregar una nueva especie.',
                    'contador'                 =>  $contador_especies_temporales
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Ocurrió un error al remover la especie',
                    'info'         => 'Ha ocurrido un error al remover la especie',
                    'contador'       => $contador_especies_temporales
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }

    /* Eliminar Especie Temporal*/
    public function eliminarEspecieUpdate()
    {
        $modelEspecies = new EspecieModel();
        $modelJornadas = new JornadasModel();

        $id_especie = $_POST['id_especie'];

        $eliminar_item = $modelEspecies->eliminarEspecieUpdate($id_especie);

        if ($eliminar_item) {
            $data = [
                'data' => [
                    'success'                =>  true,
                    'message'                => 'Especie removida exitosamente',
                    'info'                    =>  'Ahora puedes agregar una nueva especie.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Ocurrió un error al remover la especie',
                    'info'         => 'Ha ocurrido un error al remover la especie'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
}
