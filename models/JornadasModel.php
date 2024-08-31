<?php

require_once 'ModeloBase.php';

class JornadasModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function listarJornadas()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM jornadas";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }
    /*------------Metodo para Especies --------*/
    public function consultarEspecies()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM especies";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }
    /*------------ Metodo para registrar Jornada--------*/
    public function registrarJornada($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('jornadas', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para registrar Jornada--------*/
    public function registrarTasaBCV($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('historico_tasa_bcv', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar la ficha de la jornada --------*/
    public function verFicha($id)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM especies WHERE id = " . $id . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para obtener la tasa bcv--------*/
    public function obtenerTasaBCV($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM historico_tasa_bcv WHERE id_jornada = " . $id_jornada . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    public function obtenerEspeciesPescadoUpdate($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jor_espe.id, especies.especie,  presentacion.descripcion AS presentacion, jor_espe.precio_bs, jor_espe.precio_dolares, jor_espe.disponibilidad_kl, jor_espe.vendidos_kl
FROM jor_espe AS jor_espe
        JOIN especies AS especies ON jor_espe.id_especie=especies.id
        JOIN presentacion AS presentacion ON jor_espe.id_presentacion=presentacion.id
        WHERE jor_espe.id_jornada=$id_jornada";
        $resultado = $db->FectAll($query);
        return $resultado;
    }


    /*------------ Metodo para mostrar un registro jornada --------*/

    public function validarEntradaDiaJornada($jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM jornadas WHERE id_parroquia = '$jornada'";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Listar Estado --------*/
    public function listarEstados()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM estados";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }


    /* ------------ Método para llenar select de estados ------- */
    public function selectEstado()
    {
        $db = new ModeloBase();
        $query = "SELECT id_estado, estado FROM estados ORDER BY estado ASC";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /* ------------ Método para llenar select de municipios ------- */
    public function llenarSelectMunicipio($elegido)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM municipios WHERE id_estado = " . $elegido . "";
        $resultado = $db->FectAll($query);
        return $resultado;
    }
    /* ------------ Método para llenar select de parroquias ------- */
    public function llenarSelectParroquia($elegido)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM parroquias WHERE id_municipio =  " . $elegido . "";
        $resultado = $db->FectAll($query);
        return $resultado;
    }

    /* ------------ Método para listar las parroquias ------- */
    public function listarParroquias()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM parroquias";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener el id_ma de la jornada --------*/
    public function getIDMA($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jornadas.id_ma, personas.nombre_apellidos, personas.telefono, personas.id FROM jornadas AS jornadas
        JOIN personas AS personas ON jornadas.id_ma=personas.id
        WHERE jornadas.id=$id_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener el id_sa de la jornada --------*/
    public function getIDSA($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jornadas.id_sa, personas.nombre_apellidos, personas.telefono, personas.id FROM jornadas AS jornadas
        JOIN personas AS personas ON jornadas.id_sa=personas.id
        WHERE jornadas.id=$id_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener el id_vocero de la jornada --------*/
    public function getIDVOCERO($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jornadas.id_vocero, personas.nombre_apellidos, personas.telefono, personas.id FROM jornadas AS jornadas
        JOIN personas AS personas ON jornadas.id_vocero=personas.id
        WHERE jornadas.id=$id_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener el id_doca de la jornada --------*/
    public function getDOCA($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jornadas.id_doca, personas.nombre_apellidos, personas.telefono, personas.id FROM jornadas AS jornadas
        JOIN personas AS personas ON jornadas.id_doca=personas.id
        WHERE jornadas.id=$id_jornada";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener los datos de la jornada --------*/
    public function getDATOSJORNADA($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jornadas.fecha, parroquias.parroquia, jornadas.direccion, jornadas.nro_familias_atender, beneficiarios.descripcion, jornadas.kl_ofrecer 
            FROM jornadas AS jornadas
            JOIN parroquias AS parroquias ON jornadas.id_parroquia=parroquias.id_parroquia
            JOIN beneficiarios AS beneficiarios ON jornadas.id_beneficiario=beneficiarios.id
            WHERE jornadas.id=$id_jornada";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener las especies de la jornada --------*/
    public function getDATOSESPECIES($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT especies.especie,  presentacion.descripcion AS presentacion, jor_espe.precio_bs, jor_espe.precio_dolares, jor_espe.disponibilidad_kl, jor_espe.vendidos_kl FROM jor_espe AS jor_espe
        JOIN especies AS especies ON jor_espe.id_especie=especies.id
        JOIN presentacion AS presentacion ON jor_espe.id_presentacion=presentacion.id
        WHERE jor_espe.id_jornada=$id_jornada";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener las especies de la jornada para el cierre --------*/
    public function getDATOSESPECIESCIERRE($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT especies.id AS id_especie,  presentacion.id AS id_presentacion, jor_espe.precio_bs, jor_espe.precio_dolares, jor_espe.disponibilidad_kl, jor_espe.vendidos_kl FROM jor_espe AS jor_espe
        JOIN especies AS especies ON jor_espe.id_especie=especies.id
        JOIN presentacion AS presentacion ON jor_espe.id_presentacion=presentacion.id
        WHERE jor_espe.id_jornada=$id_jornada";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------Metodo para obtener los datos para la actualizacion de la jornada --------*/
    public function listarActualizarcionJornada($id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT jornadas.id, jornadas.id_ma, jornadas.id_sa, jornadas.id_vocero, jornadas.id_doca, jornadas.nro_familias_atender,
        jornadas.id_beneficiario, jornadas.kl_ofrecer, jornadas.nro_placa_caravana, jornadas.id_tipo_distribucion,
        jornadas.id_origen, jornadas.id_destino, jornadas.fecha, jornadas.direccion, jornadas.id_parroquia, jornadas.observaciones 
        FROM jornadas AS jornadas WHERE jornadas.id=$id_jornada";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para modificar un registro Jornada --------*/
    public function modificarJornadas($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('jornadas', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para registrar Jornada--------*/
    public function registrarCierreJornada($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('cierre_jornada', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------Metodo para obtener las especies de la jornada para el cierre --------*/
    public function reporteMatrizJornada()
    {
        $db = new ModeloBase();
        $query = "SELECT cierre_jornada.id, jornadas.fecha, jornadas.id_tipo_distribucion, jornadas.id_origen, 
                    jornadas.id_doca, jornadas.id_destino, beneficiarios.descripcion AS beneficiarios, UPPER(parroquias.parroquia) AS parroquia,
                    especies.especie, presentacion.descripcion AS presentacion, cierre_jornada.kl_vendidos AS kilos_distribuidos, cierre_jornada.kl_vendidos, cierre_jornada.precio_unitario_bs, 
                    cierre_jornada.tasa_cambio_bcv, cierre_jornada.total_bs, cierre_jornada.equiv_usd, cierre_jornada.observacion, jornadas.estatus 
                    FROM cierre_jornada AS cierre_jornada
                    JOIN jornadas AS jornadas ON jornadas.id=cierre_jornada.id_jornada
                    JOIN especies AS especies ON cierre_jornada.id_especie=especies.id
                    JOIN presentacion AS presentacion ON cierre_jornada.id_presentacion=presentacion.id
                    JOIN beneficiarios AS beneficiarios ON jornadas.id_beneficiario=beneficiarios.id
                    JOIN parroquias AS parroquias ON jornadas.id_parroquia=parroquias.id_parroquia";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }


}
