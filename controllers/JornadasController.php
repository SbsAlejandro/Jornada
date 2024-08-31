<?php

require_once './models/JornadasModel.php';
require_once './models/EspecieModel.php';
require_once './models/PresentacionModel.php';
require_once './config/validacion.php';


class JornadasController
{

    #estableciendo la vista del login
    public function inicioJornadas()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/jornadas/inicioJornadas.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }

    public function verJornada()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/jornadas/verJornada.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }

    public function reporteFichaJornada()
    {

        require_once('./views/paginas/reporteFichaJornada.php');

    }

    public function reporteMatrizJornada()
    {

        require_once('./views/paginas/reporteMatrizJornada.php');

    }



    public function listarJornadas()
    {
        // Database connection info 
        $dbDetails = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db'   => 'jornada_diaria'
        );


        $table = <<<EOT
        (
            SELECT jornadas.id, jornadas.observaciones, beneficiarios.descripcion, jornadas.id_tipo_distribucion, jornadas.id_origen, jornadas.id_destino, jornadas.estatus FROM jornadas AS jornadas JOIN beneficiarios AS beneficiarios ON beneficiarios.id=jornadas.id_beneficiario ORDER BY id DESC
        ) temp
        EOT;

        // Table's primary key 
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables. 
        // The `db` parameter represents the column name in the database.  
        // The `dt` parameter represents the DataTables column identifier. 
        $columns = array(

            array('db' => 'id',         'dt' => 0),
            array('db' => 'observaciones',         'dt' => 1),
            array('db' => 'descripcion',         'dt' => 2),
            array('db' => 'id_tipo_distribucion',         'dt' => 3),
            array('db' => 'id_origen',         'dt' => 4),
            array('db' => 'id_destino',         'dt' => 5),
            array(
                'db'        => 'estatus',
                'dt'        => 6,
                'formatter' => function ($d, $row) {
                    return ($d == 1) ? '<button class="btn btn-success btn-sm">En proceso <div class="spinner-border spinner-border-sm text-light" role="status"> <span class="visually-hidden"></span></div></button>' : '<button class="btn btn-danger btn-sm">Inactivo</button>';
                }
            ),
            array('db' => 'id', 'dt' => 7),
            array('db' => 'estatus', 'dt' => 8)
        );

        // Include SQL query processing class 
        require './config/ssp.class.php';

        // Output data as json format 
        echo json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function listarMatriz()
    {
        // Database connection info 
        $dbDetails = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db'   => 'jornada_diaria'
        );


        $table = <<<EOT
        (
            SELECT cierre_jornada.id, jornadas.fecha, jornadas.id_tipo_distribucion, jornadas.id_origen, 
                    jornadas.id_doca, jornadas.id_destino, beneficiarios.descripcion AS beneficiarios, UPPER(parroquias.parroquia) AS parroquia,
                    especies.especie, presentacion.descripcion AS presentacion, cierre_jornada.kl_vendidos AS kilos_distribuidos, cierre_jornada.kl_vendidos, cierre_jornada.precio_unitario_bs, 
                    cierre_jornada.tasa_cambio_bcv, cierre_jornada.total_bs, cierre_jornada.equiv_usd, cierre_jornada.observacion, jornadas.estatus 
                    FROM cierre_jornada AS cierre_jornada
                    JOIN jornadas AS jornadas ON jornadas.id=cierre_jornada.id_jornada
                    JOIN especies AS especies ON cierre_jornada.id_especie=especies.id
                    JOIN presentacion AS presentacion ON cierre_jornada.id_presentacion=presentacion.id
                    JOIN beneficiarios AS beneficiarios ON jornadas.id_beneficiario=beneficiarios.id
                    JOIN parroquias AS parroquias ON jornadas.id_parroquia=parroquias.id_parroquia   
        ) temp
        EOT;

        // Table's primary key 
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables. 
        // The `db` parameter represents the column name in the database.  
        // The `dt` parameter represents the DataTables column identifier. 
        $columns = array(

            array('db' => 'fecha',         'dt' => 0),
            array('db' => 'id_tipo_distribucion',         'dt' => 1),
            array('db' => 'id_origen',         'dt' => 2),
            array('db' => 'id_doca',         'dt' => 3),
            array('db' => 'id_destino',         'dt' => 4),
            array('db' => 'beneficiarios',         'dt' => 5),
            array('db' => 'parroquia', 'dt' => 6),
            array('db' => 'especie', 'dt' => 7),
            array('db' => 'presentacion', 'dt' => 8),
            array('db' => 'kilos_distribuidos', 'dt' => 9),
            array('db' => 'kl_vendidos', 'dt' => 10),
            array('db' => 'precio_unitario_bs', 'dt' => 11),
            array('db' => 'tasa_cambio_bcv', 'dt' => 12),
            array('db' => 'total_bs', 'dt' => 13),
            array('db' => 'equiv_usd', 'dt' => 14),
            array('db' => 'observacion', 'dt' => 15),
            array('db' => 'estatus', 'dt' => 16),
        );

        // Include SQL query processing class 
        require './config/ssp.class.php';

        // Output data as json format 
        echo json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function obtenerjornadas()
    {
        $modeljornadas = new JornadasModel();

        return $jornadas = $modeljornadas->listarJornadas();
    }

    public function registrarJornada()
    {
        session_start();

        $modelJornadas          = new JornadasModel();
        $modelEspecies          = new EspecieModel();


        $id_usuario             = $_SESSION['user_id'];
        /* --------- Funcion limpiar cadenas ---------*/
        $id_ma                      = Validacion::limpiar_cadena($_POST['id_ma']);
        $id_sa                      = Validacion::limpiar_cadena($_POST['id_sa']);
        $id_vocero                  = Validacion::limpiar_cadena($_POST['id_vocero']);
        $id_doca                    = Validacion::limpiar_cadena($_POST['id_doca']);
        $nro_familias_atender       = Validacion::limpiar_cadena($_POST['nro_familias_atender']);
        $id_beneficiario            = Validacion::limpiar_cadena($_POST['id_beneficiario']);
        $kl_ofrecer                 = Validacion::limpiar_cadena($_POST['kl_ofrecer']);
        $nro_placa_caravana         = Validacion::limpiar_cadena($_POST['nro_placa_caravana']);
        $id_tipo_distribucion       = Validacion::limpiar_cadena($_POST['id_tipo_distribucion']);
        $id_origen                  = Validacion::limpiar_cadena($_POST['id_origen']);
        $id_destino                 = Validacion::limpiar_cadena($_POST['id_destino']);
        $fecha                      = $_POST['fecha'];
        $direccion                  = Validacion::limpiar_cadena($_POST['direccion']);
        $parroquia                  = Validacion::limpiar_cadena($_POST['parroquia']);
        $tasa_bcv                   = $_POST['tasa_bcv'];
        $observacion                = Validacion::limpiar_cadena($_POST['observacion']);
        $fecha_automatica           = date("Y-m-d");


        /* comprobar campos vacios */
        if ($id_ma == "" ||  $id_sa == "" ||  $id_vocero == "" ||  $id_doca == "" ||  $nro_familias_atender == "" ||  $id_beneficiario == "" ||  $kl_ofrecer == "" ||  $nro_placa_caravana == "" ||  $id_origen == "" ||  $id_destino == "" ||  $fecha == "" ||  $direccion == "" ||  $id_destino == "" ||  $parroquia == "" ||  $tasa_bcv == "") {
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


        if (Validacion::verificar_datos("[0-9]{1,10}", $nro_familias_atender)) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten numeros en el campo número de familias a atender'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        $datos = array(
            'fecha'                         => $fecha,
            'id_parroquia'                  => $parroquia,
            'direccion'                     => $direccion,
            'id_ma'                         => $id_ma,
            'id_sa'                         => $id_sa,
            'id_vocero'                     => $id_vocero,
            'id_doca'                       => $id_doca,
            'nro_familias_atender'          => $nro_familias_atender,
            'id_beneficiario'               => $id_beneficiario,
            'kl_ofrecer'                    => $kl_ofrecer,
            'nro_placa_caravana'            => $nro_placa_caravana,
            'observaciones'                 => $observacion,
            'id_tipo_distribucion'          => $id_tipo_distribucion,
            'id_origen'                     => $id_origen,
            'id_destino'                    => $id_destino,
            'estatus'                       => 1,
        );

        $resultado = $modelJornadas->registrarJornada($datos);

        $id_jornada = $resultado['ultimo_id'];

        /* Registrar Especies */

        $obtener_especies_temporales = $modelEspecies->obtenerEspeciesTemporales($id_usuario);

        foreach ($obtener_especies_temporales as $obtener_especies_temporales) {

            $get_id_especie         = $obtener_especies_temporales['id_especie'];
            $get_id_presentacion    = $obtener_especies_temporales['id_presentacion'];
            $get_precio_bs          = $obtener_especies_temporales['precio_bs'];
            $get_precio_dolares     = $obtener_especies_temporales['precio_dolares'];
            $get_disponibilidad_kl  = $obtener_especies_temporales['disponibilidad_kl'];

            $datos_especies = array(
                'id_especie'                        => $get_id_especie,
                'id_jornada'                        => $id_jornada,
                'id_presentacion'                   => $get_id_presentacion,
                'precio_bs'                         => $get_precio_bs,
                'precio_dolares'                    => $get_precio_dolares,
                'disponibilidad_kl'                 => $get_disponibilidad_kl,
                'vendidos_kl'                       => 0,
            );

            $registrar_especies = $modelEspecies->registrarEspeciesIntermedia($datos_especies);
        }

        $reiniciarTblEspeciesTemporales = $modelEspecies->eliminarEspecieTemporal($id_usuario);

        //Guardar historio valor tasa BCV
        $datos_tasa_bcv = array(
            'id_jornada'                  => $id_jornada,
            'tasa_bcv'                    => $tasa_bcv,
            'fecha_registro'              => "$fecha",
        );

        $registrar_tasa_bcv = $modelJornadas->registrarTasaBCV($datos_tasa_bcv);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'Jornada creada exitosamente'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {


            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al registrar la jornada',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
    public function verFicha()
    {
        $modelJornadas = new JornadasModel();

        $id_jornada = $_POST['id'];

        $listar = $modelJornadas->verFicha($id_jornada);


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

    public function listarActualizarcionJornada()
    {
        $id_jornada     = $_POST['id_jornada'];

        $modelJornadas  = new JornadasModel();

        /* Obtener datos de la jornada */
        $listar_jornada = $modelJornadas->listarActualizarcionJornada($id_jornada);

        foreach ($listar_jornada as $listar_jornada) {
            $id                         = $listar_jornada['id'];
            $id_ma                      = $listar_jornada['id_ma'];
            $id_sa                      = $listar_jornada['id_sa'];
            $id_vocero                  = $listar_jornada['id_vocero'];
            $id_doca                    = $listar_jornada['id_doca'];
            $nro_familias_atender       = $listar_jornada['nro_familias_atender'];
            $id_beneficiario            = $listar_jornada['id_beneficiario'];
            $kl_ofrecer                 = $listar_jornada['kl_ofrecer'];
            $nro_placa_caravana         = $listar_jornada['nro_placa_caravana'];
            $id_tipo_distribucion       = $listar_jornada['id_tipo_distribucion'];
            $id_origen                  = $listar_jornada['id_origen'];
            $id_destino                 = $listar_jornada['id_destino'];
            $fecha                      = $listar_jornada['fecha'];
            $direccion                  = $listar_jornada['direccion'];
            $id_parroquia               = $listar_jornada['id_parroquia'];
            $observaciones               = $listar_jornada['observaciones'];
        }

        /* Obtener tasa BCV */
        $obtener_tasa_bcv = $modelJornadas->obtenerTasaBCV($id_jornada);

        foreach ($obtener_tasa_bcv as $obtener_tasa_bcv) {
            $tasa_bcv = $obtener_tasa_bcv['tasa_bcv'];
        }

        /* Obtener especies de pescado con sus datos */

        $especies_pescado = $modelJornadas->obtenerEspeciesPescadoUpdate($id_jornada);

        $data = [
            'data' => [
                'success'                   =>  true,
                'message'                   => 'Registro encontrado',
                'info'                      =>  '',
                'id_jornada'                => $id,
                'id_ma'                     => $id_ma,
                'id_sa'                     => $id_sa,
                'id_vocero'                 => $id_vocero,
                'id_doca'                   => $id_doca,
                'nro_familias_atender'      => $nro_familias_atender,
                'id_beneficiario'           => $id_beneficiario,
                'kl_ofrecer'                => $kl_ofrecer,
                'nro_placa_caravana'        => $nro_placa_caravana,
                'id_tipo_distribucion'      => $id_tipo_distribucion,
                'id_origen'                 => $id_origen,
                'id_destino'                => $id_destino,
                'fecha'                     => $fecha,
                'direccion'                 => $direccion,
                'id_parroquia'              => $id_parroquia,
                'tasa_bcv'                  => $tasa_bcv,
                'especies_pescado'          => $especies_pescado,
                'observaciones'          => $observaciones,
                
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }



  
    public function modificarJornadas()
    {

        $modelJornadas          = new JornadasModel();


       /*  var_dump($_POST); die();
 */
        $id_jornada = $_POST['id_jornada'];

        /* --------- Funcion limpiar cadenas ---------*/
        $id_ma                      = Validacion::limpiar_cadena($_POST['id_ma']);
        $id_sa                      = Validacion::limpiar_cadena($_POST['id_sa']);
        $id_vocero                  = Validacion::limpiar_cadena($_POST['id_vocero']);
        $id_doca                    = Validacion::limpiar_cadena($_POST['id_doca']);
        $nro_familias_atender       = Validacion::limpiar_cadena($_POST['nro_familias_atender']);
        $id_beneficiario            = Validacion::limpiar_cadena($_POST['id_beneficiario']);
        $kl_ofrecer                 = Validacion::limpiar_cadena($_POST['kl_ofrecer']);
        $nro_placa_caravana         = Validacion::limpiar_cadena($_POST['nro_placa_caravana']);
        $id_tipo_distribucion       = Validacion::limpiar_cadena($_POST['id_tipo_distribucion']);
        $id_origen                  = Validacion::limpiar_cadena($_POST['id_origen']);
        $id_destino                 = Validacion::limpiar_cadena($_POST['id_destino']);
        $fecha                      = $_POST['fecha'];
        $direccion                  = Validacion::limpiar_cadena($_POST['direccion']);
        $parroquia                  = Validacion::limpiar_cadena($_POST['parroquia']);
        $tasa_bcv                   = $_POST['tasa_bcv'];
        $observacion                = Validacion::limpiar_cadena($_POST['observacion']);
        $fecha_automatica           = date("Y-m-d");



        //caracteres especiales 
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $direccion)) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en la direccion.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        $datos = array(
            'fecha'                         => $fecha,
            'id_parroquia'                  => $parroquia,
            'direccion'                     => $direccion,
            'id_ma'                         => $id_ma,
            'id_sa'                         => $id_sa,
            'id_vocero'                     => $id_vocero,
            'id_doca'                       => $id_doca,
            'nro_familias_atender'          => $nro_familias_atender,
            'id_beneficiario'               => $id_beneficiario,
            'kl_ofrecer'                    => $kl_ofrecer,
            'nro_placa_caravana'            => $nro_placa_caravana,
            'observaciones'                 => $observacion,
            'id_tipo_distribucion'          => $id_tipo_distribucion,
            'id_origen'                     => $id_origen,
            'id_destino'                    => $id_destino,
            'estatus'                       => 1,
        
        );

        /* comprobar campos vacios */
        if ($id_ma == "" ||  $id_sa == "" ||  $id_vocero == "" ||  $id_doca == "" ||  $nro_familias_atender == "" ||  $id_beneficiario == "" ||  $kl_ofrecer == "" ||  $nro_placa_caravana == "" ||  $id_origen == "" ||  $id_destino == "" ||  $fecha == "" ||  $direccion == "" ||  $id_destino == "" ||  $parroquia == "" ||  $tasa_bcv == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de modificar la jornada.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }



        $resultado = $modelJornadas->modificarJornadas($id_jornada, $datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'Los datos de la jornada han sido modificados'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar los datos de la jornada',
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

        $modelEspecies = new EspecieModel();
        $modelPresentaciones = new PresentacionModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $id_especie = Validacion::limpiar_cadena($_POST['id_especie']);
        $id_presentacion = Validacion::limpiar_cadena($_POST['id_presentacion']);

        // CONSULTAR LOS DATOS DE LA ESPECIE Y DE LA PRESENTACION PARA ENVIARLO AL FRONT
        $obtener_especie = $modelEspecies->obtenerEspecies($id_especie);
        $obtener_presentacion = $modelPresentaciones->obtenerPresentacion($id_presentacion);

        foreach ($obtener_especie as $obtener_especie) {
            $id_especie_obtenida    = $obtener_especie['id'];
            $especie_obtenida       = $obtener_especie['especie'];
        }

        foreach ($obtener_presentacion as $obtener_presentacion) {
            $id_presentacion_obtenida = $obtener_presentacion['id'];
            $descripcion_obtenida = $obtener_presentacion['id'];
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
            'id_especie' => $id_especie,
            'id_presentacion' => $id_presentacion,
        );


        $resultado = $modelEspecies->registrarEspeciePresentacionTemporal($datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            =>  'Guardado exitosamente',
                    'info'               =>  'Especie agregada exitosamente',
                    'id_especie'         => $id_especie_obtenida,
                    'especie' =>  $especie_obtenida,
                    'presentacion' =>  $descripcion_obtenida
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


    public function llenarSelectEstado()
    {
        $modelJornadas = new JornadasModel();

        $elegido = $_POST['elegido'];

        $data = $modelJornadas->llenarSelectMunicipio($elegido);

        $data = [
            'data' => [
                'success'            =>  true,
                'message'            => 'Registro encontrado',
                'info'               =>  '',
                'data'                 =>  $data,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }

    public function llenarSelectParroquia()
    {
        $modelJornadas = new JornadasModel();

        $elegido = $_POST['municipio'];

        $data = $modelJornadas->llenarSelectParroquia($elegido);

        $data = [
            'data' => [
                'success'            =>  true,
                'message'            => 'Registro encontrado',
                'info'               =>  '',
                'data'                 =>  $data,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }

    public function ObtenerDatosModificarEspecieJornada()
    {
        $modelEspecies = new EspecieModel();

        $id_especie = $_POST['id_especie'];

        $datos_especie = $modelEspecies->obtenerEspecieModificar($id_especie);


        foreach ($datos_especie as $datos_especie) {
            $id_especie             = $datos_especie['id_especie'];
            $id_presentacion        = $datos_especie['id_presentacion'];
            $precio_bs              = $datos_especie['precio_bs'];
            $disponibilidad_kl      = $datos_especie['disponibilidad_kl'];
            $vendidos_kl            = $datos_especie['vendidos_kl'];

        }

        $data = [
            'data' => [
                'success'               =>  true,
                'message'               => 'Registro encontrado',
                'info'                  =>  '',
                'id_especie'            => $id_especie,
                'id_presentacion'       => $id_presentacion,
                'precio_bs'             => $precio_bs,
                'disponibilidad_kl'     => $disponibilidad_kl,
                'vendidos_kl'           => $vendidos_kl,
              
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }


    public function modificarEspecieJornada()
    {

        $modelEspecies = new EspecieModel();
        $modelJornadas = new JornadasModel();

        $id_especie_update          = $_POST['id_especie_update'];
        $id_modificar_especie       = $_POST['id_modificar_especie'];
        $id_presentacion_update     = $_POST['id_presentacion_update'];
        $precio_bs_update           = $_POST['precio_bs_update'];
        $disponibilidad_kl_update   = $_POST['disponibilidad_kl_update'];
        $vendidos_kl_update         = $_POST['vendidos_kl_update'];
        $id_jornada                 = $_POST['id_jornada_update'];


        $datos = array(
            'id_especie'            => $id_especie_update,
            'id_jornada'            => $id_jornada,
            'id_presentacion'       => $id_presentacion_update,
            'precio_bs'             => $precio_bs_update,
            'disponibilidad_kl'     => $disponibilidad_kl_update,
            'vendidos_kl'           => $vendidos_kl_update,
        );

        /* comprobar campos vacios */
        if ($id_especie_update == "" || $id_especie_update == "" || $id_presentacion_update == "" || $precio_bs_update == "" || $disponibilidad_kl_update == "" || $vendidos_kl_update == "") {
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

        $resultado = $modelEspecies->modificarEspecieJornada($id_modificar_especie, $datos);

        $especies_pescado = $modelJornadas->obtenerEspeciesPescadoUpdate($id_jornada);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'Los datos de la especie han sido modificados',
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
                    'message'            => 'Ocurrió un error al modificar los datos de las especie',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }

    public function finalizarjornada()
	{

		session_start();

		$modelJornadas 					= new JornadasModel();

		$id_jornada 					= $_POST['id_jornada'];
        $observacion                    = "Una observacion";
		//$id_usuario 					= $_SESSION['user_id'];
		//$usuario 						= $_SESSION['usuario'];

        $datos_estatus_jornada = array(
            'estatus'                => 2,
        );

        $resultado = $modelJornadas->modificarJornadas($id_jornada, $datos_estatus_jornada);


        $datos_especie_jornada = $modelJornadas->getDATOSESPECIESCIERRE($id_jornada);

        $obtener_tasa_bcv_1 = $modelJornadas->obtenerTasaBCV($id_jornada);

        foreach ($obtener_tasa_bcv_1 as $obtener_tasa_bcv_1) {
            $tasa_bcv_1 = $obtener_tasa_bcv_1['tasa_bcv'];
        }

        $total_bs = 0;
        $equiv_usd = 0;

        foreach ($datos_especie_jornada as $datos_especie_jornada) 
        {
            $id_especie             = $datos_especie_jornada['id_especie'];
            $id_presentacion        = $datos_especie_jornada['id_presentacion'];
            $precio_bs              = $datos_especie_jornada['precio_bs'];
            $precio_dolares         = $datos_especie_jornada['precio_dolares'];
            $disponibilidad_kl      = $datos_especie_jornada['disponibilidad_kl'];
            $vendidos_kl            = $datos_especie_jornada['vendidos_kl'];
            $total_bs               = $vendidos_kl * $precio_bs;
            $equiv_usd              = $total_bs / $tasa_bcv_1;

            $obtener_tasa_bcv = $modelJornadas->obtenerTasaBCV($id_jornada);

            foreach ($obtener_tasa_bcv as $obtener_tasa_bcv) {
                $tasa_bcv = $obtener_tasa_bcv['tasa_bcv'];
            }

            $resultado_precio_dolar = $precio_bs / $tasa_bcv;
            $precio_dolares         = number_format($resultado_precio_dolar, 2);

            $datos = array(
                'id_jornada'                => intval($id_jornada),
                'id_especie'                => $id_especie,
                'id_presentacion'           => $id_presentacion,
                'kl_vendidos'               => intval($vendidos_kl),
                'precio_unitario_bs'        => intval($precio_bs),
                'tasa_cambio_bcv'           => $precio_dolares,
                'total_bs'                  => $total_bs,
                'equiv_usd'                 => $equiv_usd,
                'observacion'               => $observacion,
            );
    
            $resultado = $modelJornadas->registrarCierreJornada($datos);

        }

        


       

        

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            =>  'Jornada finalizada exitosamente',
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
                    'message'            => 'Ocurrió un error al finalizar la jornada',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
		
	}

}
