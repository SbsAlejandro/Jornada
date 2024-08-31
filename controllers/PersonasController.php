<?php

require_once './models/PersonasModel.php';
require_once './config/validacion.php';


class PersonasController
{

    #estableciendo la vista del login
    public function inicioPersonas()
    {

        /*HEADER */
        require_once('./views/includes/cabecera.php');

        require_once('./views/paginas/personas/inicioPersonas.php');

        /* FOOTER */
        require_once('./views/includes/pie.php');
    }

    /*----------Metodo para listar personas-------*/
    public function listarPersonas()
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
            SELECT personas.id, personas.nombre_apellidos, personas.telefono, personas.fecha_registro, tipo_persona.descripcion, personas.estatus FROM personas AS personas JOIN tipo_persona AS tipo_persona ON personas.id_tipo_persona=tipo_persona.id ORDER BY id DESC
        ) temp
        EOT;



        // Table's primary key 
        $primaryKey = 'id';

        // Array of database columns which should be read and sent back to DataTables. 
        // The `db` parameter represents the column name in the database.  
        // The `dt` parameter represents the DataTables column identifier. 
        $columns = array(

            array('db' => 'id',         'dt' => 0),
            array('db' => 'nombre_apellidos',         'dt' => 1),
            array('db' => 'telefono',         'dt' => 2),
            array('db' => 'fecha_registro',         'dt' => 3),
            array('db' => 'descripcion',         'dt' => 4),
            array(
                'db'        => 'estatus',
                'dt'        => 5,
                'formatter' => function ($d, $row) {
                    return ($d == 1) ? '<button class="btn btn-success btn-sm">Activo</button>' : '<button class="btn btn-danger btn-sm">Inactivo</button>';
                }
            ),
            array('db' => 'id',         'dt' => 6),
            array('db' => 'estatus', 'dt' => 7)

        );

        // Include SQL query processing class 
        require './config/ssp.class.php';

        // Output data as json format 
        echo json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }
    /*----------Metodo para ObtenePersonas-------*/
    public function obtenerPersonas()
    {
        $modelpersonas = new PersonasModel();

        return $presentacion = $modelpersonas->listarPersonas();
    }



    /*----------Metodo para RegistrarPersonas-------*/
    public function registrarPersonas()
    {

        $modelpersonas = new PersonasModel();

        /* --------- Funcion limpiar cadenas ---------*/

        $nombre_apellidos           = Validacion::limpiar_cadena($_POST['nombre_apellidos']);
        $telefono                   = Validacion::limpiar_cadena($_POST['telefono']);
        $fecha_registro             = date("Y-m-d");
        $id_tipo_persona            = Validacion::limpiar_cadena($_POST['id_tipo_persona']);
        $estatus                    = Validacion::limpiar_cadena($_POST['estatus']);

        //Validar que el visitante no ingrese dos veces al sistema el mismo día
        $entrada_personas_hoy = $modelpersonas->validarEntradaDiaPersonas($nombre_apellidos);

        foreach ($entrada_personas_hoy as $entrada_personas_hoy) {
            $id_entrada_personas = $entrada_personas_hoy['id'];
        }



        if (!empty($id_entrada_personas)) {
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
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre_apellidos)) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en la persona'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
        $datos = array(

            'nombre_apellidos'          => $nombre_apellidos,
            'telefono'                  => $telefono,
            'fecha_registro'            => $fecha_registro,
            'id_tipo_persona'           => $id_tipo_persona,
            'estatus'                   => $estatus,

        );
        if (Validacion::verificar_datos("[0-9]{1,15}", $_POST['telefono'])) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten numeros en el campo del telefono  de la persona'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
        /* comprobar campos vacios */
        if ($nombre_apellidos == ""  || $telefono == ""  || $estatus == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar a la persona'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        $resultado = $modelpersonas->registrarPersonas($datos);

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
    /*----------Metodo para VerPersonas-------*/

    public function verPersonas()
    {
        $modelpersonas = new PersonasModel();

        $id_personas = $_POST['id_personas'];

        $listar = $modelpersonas->obtenerpersonas($id_personas);


        foreach ($listar as $listar) {

            $id_personas     = $listar['id'];
            $nombre_apellidos         = $listar['nombre_apellidos'];
            $telefono         = $listar['telefono'];
            $fecha_registro         = $listar['fecha_registro'];
            $id_tipo_persona         = $listar['id_tipo_persona'];

            $estatus         = $listar['estatus'];
        }

        $data = [
            'data' => [
                'success'               =>  true,
                'message'               => 'Registro encontrado',
                'info'                  =>  '',
                'id'                    => $id_personas,
                'nombre_apellidos'               => $nombre_apellidos,
                'telefono'               => $telefono,
                'fecha_registro'        => $fecha_registro,
                'id_tipo_persona'        => $id_tipo_persona,
                'estatus'               => $estatus,
            ],
            'code' => 0,
        ];

        echo json_encode($data);

        exit();
    }

    /*----------Metodo para  ModificarPersonas-------*/

    public function modificarPersonas()
    {

        $modelpersonas = new PersonasModel();
        $id_personas = $_POST['id_personas'];
        /* --------- Funcion limpiar cadenas ---------*/

        $nombre_apellidos = Validacion::limpiar_cadena($_POST['nombre_apellidos']);
        $telefono                = Validacion::limpiar_cadena($_POST['telefono']);
        $fecha_registro = date("Y-m-d");
        $estatus                 = Validacion::limpiar_cadena($_POST['estatus']);


        //Validar que la persona que no ingrese dos veces al sistema el mismo día
        $entrada_personas_hoy = $modelpersonas->validarEntradaDiaPersonas($nombre_apellidos);

        foreach ($entrada_personas_hoy as $entrada_personas_hoy) {
            $id_entrada_personas = $entrada_personas_hoy['id'];
        }



        if (!empty($id_entrada_personas)) {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'La persona ya ha sido ingresado el día de hoy',
                    'info'               =>   ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }

        //caracteres especiales 
        if (Validacion::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre_apellidos)) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten caracteres alfabéticos con una longitud de 40 caracteres en la persona'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
        if (Validacion::verificar_datos("[0-9]{1,10}", $_POST['telefono'])) {

            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Solo se permiten numeros en el campo de el telefono la persona.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
        $datos = array(

            'nombre_apellidos'        => $_POST['nombre_apellidos'],
            'telefono'        => $_POST['telefono'],
            'estatus'        => $_POST['estatus'],
        );

        /* comprobar campos vacios */
        if ($nombre_apellidos == "" || $nombre_apellidos == "" || $estatus == "") {
            $data = [
                'data' => [
                    'error'        => true,
                    'message'      => 'Datos inválidos',
                    'info'         => 'Verifica que todos los campos estén llenos a la hora de registrar la persona.'
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }



        $resultado = $modelpersonas->modificarPersonas($id_personas, $datos);

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'Los datos de la persona han sido modificados'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar los datos de la persona',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }

    /*----------Metodo para inactivar Personas-------*/

    public function inactivarPersonas()
    {

        $modelpersonas = new PersonasModel();
        $id_personas = $_POST['id_personas'];

        $estado = $modelpersonas->obtenerPersonas($id_personas);

        foreach ($estado as $estado) {
            $estado_personas = $estado['estatus'];
        }

        if ($estado_personas == 1) {
            $datos = array(
                'estatus'        => 0,
            );

            $resultado = $modelpersonas->modificarPersonas($id_personas, $datos);
        } else {
            $datos = array(
                'estatus'        => 1,
            );

            $resultado = $modelpersonas->modificarPersonas($id_personas, $datos);
        }

        if ($resultado) {
            $data = [
                'data' => [
                    'success'            =>  true,
                    'message'            => 'Guardado exitosamente',
                    'info'               =>  'El estado la persona ha sido modificado'
                ],
                'code' => 1,
            ];

            echo json_encode($data);
            exit();
        } else {
            $data = [
                'data' => [
                    'success'            =>  false,
                    'message'            => 'Ocurrió un error al modificar el estado la persona',
                    'info'               =>  ''
                ],
                'code' => 0,
            ];

            echo json_encode($data);
            exit();
        }
    }
}
