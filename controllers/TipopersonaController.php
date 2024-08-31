<?php

require_once './models/TipopersonaModel.php';
require_once './config/validacion.php';


class TipopersonaController
{

    #estableciendo la vista del login
    public function inicioTipopersona()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/tipo_persona/inicioTipopersona.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }

    /*----------Metodo para listar tipo persona-------*/
    public function listarTipopersona()
    {
        // Database connection info 
        $dbDetails = array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db'   => 'jornada_diaria'
        );


        $table = 'tipo_persona';

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
    /*----------Metodo para obtenerTipopersona-------*/
    public function obtenerTipopersona()
    {
        $modelTipopersona = new TipopersonaModel();

        return $tipo_persona = $modelTipopersona->listarTipopersona();
    }
    /*----------Metodo para registrarTipopersona-------*/
    public function registrarTipopersona()
    {

        $modelTipopersona = new TipopersonaModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $descripcion                 = Validacion::limpiar_cadena($_POST['descripcion']);

        $estatus                 = Validacion::limpiar_cadena($_POST['estatus']);

        //Validar que la descripcion no ingrese dos veces al sistema el mismo día
        $entrada_tipo_persona_hoy = $modelTipopersona->validarEntradaDiaTipopersona($descripcion);

        foreach ($entrada_tipo_persona_hoy as $entrada_tipo_persona_hoy) {
            $id_entrada_tipo_persona = $entrada_tipo_persona_hoy['id'];
        }



        if (!empty($$id_entrada_tipo_persona)) {
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
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,150}", $descripcion)) {

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

        $resultado = $modelTipopersona->registrarTipopersona($datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'La descripción ha sido guardado en la base de datos'
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

    /*----------Metodo para VerTipopersona-------*/


    public function verTipopersona()
    {
        $modelTipopersona = new TipopersonaModel();

        $id_tipo_persona = $_POST['id_tipo_persona'];

        $listar = $modelTipopersona->obtenerTipopersona($id_tipo_persona);
        foreach ($listar as $listar) {

            $id_tipo_persona     = $listar['id'];
            $descripcion         = $listar['descripcion'];

            $estatus         = $listar['estatus'];
        }

        $data = [
            'data' => [
                'success'               =>  true,
                'message'               => 'Registro encontrado',
                'info'                  =>  '',
                'id'                    => $id_tipo_persona,
                'descripcion'               => $descripcion,
                'estatus'               => $estatus,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }





    /*----------Metodo para  ModificarTipopersona-------*/

    public function modificarTipopersona()
    {

        $modelTipopersona = new TipopersonaModel();
        $id_tipo_persona = $_POST['id_tipo_persona'];
        /* --------- Funcion limpiar cadenas ---------*/

        $descripcion                = Validacion::limpiar_cadena($_POST['descripcion']);
        $estatus                 = Validacion::limpiar_cadena($_POST['estatus']);


        //Validar que el tipo de persona no ingrese dos veces al sistema el mismo día
        $entrada_tipo_persona_hoy = $modelTipopersona->validarEntradaDiaTipopersona($descripcion);

        foreach ($entrada_tipo_persona_hoy as $entrada_tipo_persona_hoy) {
            $id_entrada_tipo_persona = $entrada_tipo_persona_hoy['id'];
        }



        if (!empty($id_entrada_tipo_persona)) {
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
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,150}", $descripcion)) {

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



        $resultado = $modelTipopersona->modificarTipopersona($id_tipo_persona, $datos);

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

    /*----------Metodo para inactivar Tipopersona-------*/
    public function inactivarTipopersona()
    {

        $modelTipopersona = new TipopersonaModel();
        $id_tipo_persona = $_POST['id_tipo_persona'];

        $estado = $modelTipopersona->obtenerTipopersona($id_tipo_persona);

        foreach ($estado as $estado) {
            $estado_Tipopersona = $estado['estatus'];
        }

        if ($estado_Tipopersona == 1) {
            $datos = array(
                'estatus'        => 0,
            );

            $resultado = $modelTipopersona->modificarTipopersona($id_tipo_persona, $datos);
        } else {
            $datos = array(
                'estatus'        => 1,
            );

            $resultado = $modelTipopersona->modificarTipopersona($id_tipo_persona, $datos);
        }

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'El estado la descripción ha sido modificado'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar el estado de la descripción',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
}
