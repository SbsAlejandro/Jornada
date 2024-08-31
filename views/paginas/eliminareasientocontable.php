<?php

    require_once 'controllers/EncabezadoasientocontableController.php';
    $objeto = new EncabezadoasientocontableController();

    require_once 'controllers/DetallecontableController.php';
    $objeto2 = new DetallecontableController();
    
    if (isset($_GET['id'])) {
	    $idCliente = $_GET['id'];

    	$response = $objeto->eliminarAsientocontable($idCliente, $datos="");

        if($response)
        {
            $response= $objeto2->eliminarDetallecontable($idCliente, $datos="");

            if($response)
            {
                header('Location: index.php?page=inicioeasientocontable&mensaje=Asiento Eliminado&alert=success');
            }
        }

        
    }
?>
