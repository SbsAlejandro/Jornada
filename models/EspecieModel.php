<?php

require_once 'ModeloBase.php';

class EspecieModel extends ModeloBase
{

    public function __construct()
    {
        parent::__construct();
    }

    /*------------Metodo para listar Especies--------*/
    public function listarEspecies()
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM especies";
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
    /*------------ Metodo para registrar Especies--------*/
    public function registrarEspecies($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('especies', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    /*------------ Metodo para registrar Udapte--------*/
    public function registrarEspeciesUpdate($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('jor_espe', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }




    /*------------ Metodo para registrar Especies en la tabla intermedia --------*/
    public function registrarEspeciesIntermedia($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('jor_espe', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /*------------ Metodo para mostrar un registro Especies --------*/
    public function obtenerEspecies($id)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM especies WHERE id = " . $id . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para obtener las especies de la tabla temporal --------*/
    public function obtenerEspeciesTemporales($id_usuario)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM temporal_jornada_especie WHERE id_usuario = " . $id_usuario . "";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Validar la existencia de items y presentacion repetidos en la tabla temporal_jornada_especie --------*/
    public function temporal_jornada_especie($id_especie, $id_presentacion)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM temporal_jornada_especie WHERE id_especie=$id_especie AND id_presentacion=$id_presentacion";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Registrar especie y presntacion en la tabla temporal_jornada_especie --------*/
    public function registrarEspeciePresentacionTemporal($datos)
    {
        $db = new ModeloBase();
        try {
            $insertar = $db->insertar('temporal_jornada_especie', $datos);
            return $insertar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }



    /*------------ Metodo para modificar un registro Especie --------*/
    public function modificarEspecies($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('especies', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function obtenerContadorEspeciesTemporales()
    {
        $db = new ModeloBase();
        $query = "SELECT COUNT(*) AS contador_especie_temporal FROM temporal_jornada_especie";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para mostrar un registro Especies --------*/

    public function validarEntradaDiaEspecies($especies)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM especies WHERE especie = '$especies'";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }


    public function eliminarEspecieTemporal($id_usuario)
    {
        $db = new ModeloBase();
        $query = "DELETE FROM temporal_jornada_especie
		WHERE id_usuario=$id_usuario";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    public function eliminarEspecieUpdate($id_especie)
    {
        $db = new ModeloBase();
        $query = "DELETE FROM jor_espe
		WHERE id=$id_especie";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para mostrar un registro Especies --------*/

    public function validarEntradaDiarEspecieUpdate($id_especie, $id_jornada)
    {
        $db = new ModeloBase();
        $query = "SELECT * FROM jor_espe WHERE id = $id_especie AND id_jornada=$id_jornada;";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para obtener la especie que va a ser modificada --------*/
    public function obtenerEspecieModificar($id_especie)
    {
        $db = new ModeloBase();
        $query = "SELECT especies.id AS id_especie, presentacion.id AS id_presentacion, 
        jor_espe.precio_bs, jor_espe.disponibilidad_kl, jor_espe.vendidos_kl FROM jor_espe
        JOIN especies AS especies
        ON jor_espe.id_especie=especies.id
        JOIN presentacion AS presentacion
        ON jor_espe.id_presentacion=presentacion.id
        WHERE jor_espe.id=$id_especie";
        $resultado = $db->obtenerTodos($query);
        return $resultado;
    }

    /*------------ Metodo para modificar la especie de la jornada --------*/
    public function modificarEspecieJornada($id, $datos)
    {
        $db = new ModeloBase();
        try {
            $editar = $db->editar('jor_espe', $id, $datos);
            return $editar;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
