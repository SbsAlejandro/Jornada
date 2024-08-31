<?php

require_once './models/BeneficiariosModel.php';
require_once './config/validacion.php';


class BeneficiariosController
{

    #estableciendo la vista del login
    public function inicioBeneficiarios()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/beneficiarios/inicioBeneficiarios.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }

    /*----------Metodo para listarBeneficiarios-------*/
    public function listarBeneficiarios()
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
            SELECT beneficiarios.id, UPPER(parroquias.parroquia) AS parroquia, beneficiarios.descripcion, beneficiarios.estatus 
            FROM beneficiarios AS beneficiarios
            JOIN parroquias AS parroquias 
            ON beneficiarios.id_parroquia=parroquias.id_parroquia ORDER BY id DESC
        ) temp
        EOT;

        // Table's primary key 
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables. 
        // The `db` parameter represents the column name in the database.  
        // The `dt` parameter represents the DataTables column identifier. 
        $columns = array(

            array('db' => 'id', 'dt' => 0),
            array('db' => 'parroquia', 'dt' => 1),
            array('db' => 'descripcion', 'dt' => 2),
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
    /*----------Metodo para ObtenerBeneficiarios-------*/
    public function obtenerBeneficiarios()
    {
        $modelbeneficiarios = new BeneficiariosModel();

        return $beneficiarios = $modelbeneficiarios->listarBeneficiarios();
    }
    /*----------Metodo para registrarBeneficiarios-------*/

    public function registrarBeneficiarios()
    {

        $modelbeneficiarios = new BeneficiariosModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $id_parroquia           = Validacion::limpiar_cadena($_POST['id_parroquia']);
        $descripcion                   = Validacion::limpiar_cadena($_POST['descripcion']);
        $estatus                    = Validacion::limpiar_cadena($_POST['estatus']);

        //Validar que el Beneficiario no ingrese dos veces al sistema el mismo día
        $entrada_beneficiarios_hoy = $modelbeneficiarios->validarEntradaDiaBeneficiarios($descripcion);

        foreach ($entrada_beneficiarios_hoy as $entrada_beneficiarios_hoy) {
            $id_entrada_beneficiarios = $entrada_beneficiarios_hoy['id'];
        }



        if (!empty($id_entrada_beneficiarios)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => ' El beneficiario ya ha sido ingresado el día de hoy.',
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

            'id_parroquia'              => $id_parroquia,
            'descripcion'               => $descripcion,
            'estatus'                   => $estatus,

        );

        /* comprobar campos vacios */
        if ($id_parroquia == ""  || $descripcion == ""  || $estatus == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar un beneficiario.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        $resultado = $modelbeneficiarios->registrarBeneficiarios($datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'el beneficiario ya ha sido guardado en la base de datos.'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al guardar el beneficiario.',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }

    /*----------Metodo para VerBeneficiarios-------*/

    public function verBeneficiarios()
    {
        $modelbeneficiarios = new BeneficiariosModel();

        $id_beneficiarios = $_POST['id_beneficiarios'];

        $listar = $modelbeneficiarios->obtenerBeneficiarios($id_beneficiarios);


        foreach ($listar as $listar) {

            $id_beneficiarios     = $listar['id'];
            $descripcion         = $listar['descripcion'];
            $parroquia         = $listar['parroquia'];
            $estatus         = $listar['estatus'];
        }

        $data = [
            'data' => [
                'success'               =>  true,
                'message'               => 'Registro encontrado',
                'info'                  =>  '',
                'id'                    => $id_beneficiarios,
                /*           'id_parroquia'             => $id_parroquia, */
                'parroquia'             => $parroquia,
                'descripcion'           => $descripcion,
                'estatus'               => $estatus,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }

    /*----------Metodo para  ModificarBeneficiarios-------*/


    public function modificarBeneficiarios()
    {

        $modelbeneficiarios = new BeneficiariosModel();
        $id_beneficiarios = $_POST['id_beneficiarios'];
        /* --------- Funcion limpiar cadenas ---------*/
        $id_parroquia                   = Validacion::limpiar_cadena($_POST['parroquia_update']);
        $descripcion                    = Validacion::limpiar_cadena($_POST['descripcion_update']);
        $estatus                        = Validacion::limpiar_cadena($_POST['estatus_update']);


        if (!empty($id_entrada_beneficiarios)) {
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
            'id_parroquia'        => $_POST['parroquia_update'],
            'descripcion'        => $_POST['descripcion_update'],
            'estatus'        => $_POST['estatus_update'],
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



        $resultado = $modelbeneficiarios->modificarBeneficiarios($id_beneficiarios, $datos);

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





    /*----------Metodo para inactivar Beneficiarios-------*/
    public function inactivarBeneficiarios()
    {

        $modelbeneficiarios = new BeneficiariosModel();
        $id_beneficiarios = $_POST['id_beneficiarios'];

        $estado = $modelbeneficiarios->obtenerBeneficiarios($id_beneficiarios);

        foreach ($estado as $estado) {
            $estado_beneficiarios = $estado['estatus'];
        }

        if ($estado_beneficiarios == 1) {
            $datos = array(
                'estatus'        => 0,
            );

            $resultado = $modelbeneficiarios->modificarBeneficiarios($id_beneficiarios, $datos);
        } else {
            $datos = array(
                'estatus'        => 1,
            );

            $resultado = $modelbeneficiarios->modificarBeneficiarios($id_beneficiarios, $datos);
        }

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'El estado del beneficiario ha sido modificado'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar el estado del beneficiario',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
}
