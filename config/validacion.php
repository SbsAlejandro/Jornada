<?php


class Validacion {

    /* --------- Funcion limpiar cadenas ---------*/
    public static function limpiar_cadena($cadena){
        $cadena=str_ireplace("<script>", "", "$cadena");
        $cadena=str_ireplace("</script>", "", "$cadena");
        $cadena=str_ireplace("<script src", "", "$cadena");
        $cadena=str_ireplace("<script type=", "", "$cadena");
        $cadena=str_ireplace("SELECT * FROM", "", "$cadena");
        $cadena=str_ireplace("DELETE FROM", "", "$cadena");
        $cadena=str_ireplace("INSERT INTO", "", "$cadena");
        $cadena=str_ireplace("DROP TABLE", "", "$cadena");
        $cadena=str_ireplace("DROP DATABASE", "", "$cadena");
        $cadena=str_ireplace("TRUNCATE TABLE", "", "$cadena");
        $cadena=str_ireplace("SELECT * FROM", "", "$cadena");
        $cadena=str_ireplace("SHOW DATABASES", "", "$cadena");
        $cadena=str_ireplace("<?php", "", "$cadena");
        $cadena=str_ireplace("?>", "", "$cadena");
        $cadena=str_ireplace("--", "", "$cadena");
        $cadena=str_ireplace(">", "", "$cadena");
        $cadena=str_ireplace(">", "", "$cadena");
        $cadena=str_ireplace("[", "", "$cadena");
        $cadena=str_ireplace("]", "", "$cadena");
        $cadena=str_ireplace("^", "", "$cadena");
        $cadena=str_ireplace("==", "", "$cadena");
        $cadena=str_ireplace(";", "", "$cadena");
        $cadena=str_ireplace("::", "", "$cadena");
        $cadena=stripslashes($cadena);
        $cadena=trim($cadena);

        return $cadena;
        
    }
	
    /* --------- Funcion verificar datos ---------*/
    public static function verificar_datos($filtro, $cadena){
        if(preg_match("/^".$filtro."$/", $cadena)){
            return false;
        }else{
            return true;
        }
    }

}


?>