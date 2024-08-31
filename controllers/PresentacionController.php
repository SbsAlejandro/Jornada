<?php

require_once './models/PresentacionModel.php';
require_once './config/validacion.php';


class PresentacionController
{

    #estableciendo la vista del login
    public function inicioPresentacion()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/presentacion/inicioPresentacion.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }

    /*----------Metodo para listarPresentacion-------*/
    public function listarPresentacion()
    {
        // Database connection info 
        $dbDetails = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db'   => 'jornada_diaria'
        );


        $table = 'presentacion';

        // Table's primary key 
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables. 
        // The `db` parameter represents the column name in the database.  
        // The `dt` parameter represents the DataTables column identifier. 
        $columns = array(

            array('db' => 'id',         'dt' => 0),
            array('db' => 'descripcion',         'dt' => 1),
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
    /*----------Metodo para ObtenerPresentacion-------*/
    public function obtenerPresentacion()
    {
        $modelpresentacion = new PresentacionModel();

        return $presentacion = $modelpresentacion->listarPresentacion();
    }
    /*----------Metodo para RegistrarPresentacion-------*/
    public function registrarPresentacion()
    {

        $modelpresentacion = new PresentacionModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $descripcion                 = Validacion::limpiar_cadena($_POST['descripcion']);

        $estatus                 = Validacion::limpiar_cadena($_POST['estatus']);

        //Validar que el visitante no ingrese dos veces al sistema el mismo día
        $entrada_presentacion_hoy = $modelpresentacion->validarEntradaDiaPresentacion($descripcion);

        foreach ($entrada_presentacion_hoy as $entrada_presentacion_hoy) {
            $id_entrada_presentacion = $entrada_presentacion_hoy['id'];
        }



        if (!empty($id_entrada_presentacion)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => ' la descripción ya ha sido ingresado el día de hoy',
                    'info'               =>  ' '
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }


        //caracteres especiales 
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $descripcion)) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en la descripción.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
        $datos = array(

            'descripcion'        => $_POST['descripcion'],

            'estatus'        => $_POST['estatus'],

        );

        /* comprobar campos vacios */
        if ($descripcion == ""  || $estatus == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar la descripción.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        $resultado = $modelpresentacion->registrarPresentacion($datos);

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
                    'message'            => 'Ocurrió un error al guardar la descripción',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
    /*----------Metodo para VerPresentacion-------*/

    public function verPresentacion()
    {
        $modelpresentacion = new PresentacionModel();

        $id_presentacion = $_POST['id_presentacion'];

        $listar = $modelpresentacion->obtenerPresentacion($id_presentacion);

        foreach ($listar as $listar) {

            $id_presentacion     = $listar['id'];
            $descripcion         = $listar['descripcion'];

            $estatus         = $listar['estatus'];
        }

        $data = [
            'data' => [
                'success'               =>  true,
                'message'               => 'Registro encontrado',
                'info'                  =>  '',
                'id'                    => $id_presentacion,
                'descripcion'               => $descripcion,
                'estatus'               => $estatus,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }

    /*----------Metodo para  ModificarPresentacion-------*/

    public function modificarPresentacion()
    {

        $modelpresentacion = new PresentacionModel();
        $id_presentacion = $_POST['id_presentacion'];
        /* --------- Funcion limpiar cadenas ---------*/

        $descripcion                = Validacion::limpiar_cadena($_POST['descripcion']);
        $estatus                 = Validacion::limpiar_cadena($_POST['estatus']);


        //Validar que la presentacion no ingrese dos veces al sistema el mismo día
        $entrada_presentacion_hoy = $modelpresentacion->validarEntradaDiaPresentacion($descripcion);

        foreach ($entrada_presentacion_hoy as $entrada_presentacion_hoy) {
            $id_entrada_presentacion = $entrada_presentacion_hoy['id'];
        }



        if (!empty($id_entrada_presentacion)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'La descripción ya ha sido ingresado el día de hoy',
                    'info'               =>   ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        //caracteres especiales 
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $descripcion)) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en la descripción.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        $datos = array(

            'descripcion'        => $_POST['descripcion'],

            'estatus'        => $_POST['estatus'],
        );

        /* comprobar campos vacios */
        if ($descripcion == "" || $estatus == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar la descripción.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }



        $resultado = $modelpresentacion->modificarPresentacion($id_presentacion, $datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'Los datos de la descripción han sido modificados'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar los datos de la descripción',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }

    /*----------Metodo para inactivar Presentacion-------*/
    public function inactivarPresentacion()
    {

        $modelpresentacion = new PresentacionModel();
        $id_presentacion = $_POST['id_presentacion'];

        $estado = $modelpresentacion->obtenerPresentacion($id_presentacion);

        foreach ($estado as $estado) {
            $estado_presentacion = $estado['estatus'];
        }

        if ($estado_presentacion == 1) {
            $datos = array(
                'estatus'        => 0,
            );

            $resultado = $modelpresentacion->modificarPresentacion($id_presentacion, $datos);
        } else {
            $datos = array(
                'estatus'        => 1,
            );

            $resultado = $modelpresentacion->modificarPresentacion($id_presentacion, $datos);
        }

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'El estado la presentacion ha sido modificado'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar el estado la presentacion',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
}
