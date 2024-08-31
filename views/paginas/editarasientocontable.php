<?php

    require_once 'controllers/EncabezadoasientocontableController.php';
    $objeto = new EncabezadoasientocontableController();
	$asientos = $objeto->obtenerAsientoscontables();

	require_once 'controllers/DetallecontableController.php';
    $objet4 = new DetallecontableController();

	require_once './controllers/TipocriptomonedacontableController.php';
	$objeto2 = new TipocriptomonedacontableController();
	$tipo_cripto_contable = $objeto2->obtenerCriptomonedascontables();

	require_once './controllers/TipooperacionController.php';
	$objeto3 = new TipooperacionController();
	$tipo_operacion = $objeto3->obtenerTipooperaciones();

    /*obtener el valor 'id' de la url para buscar en la bd
    y posteriormente llenar el formulario*/
    
    if (isset($_GET['id'])) {
	    $idEasientocontable = $_GET['id'];
    	$easientocontable = $objeto->obtenerAsientocontable($idEasientocontable);
    }

    if (isset($_POST['editar'])) {

		$id = $_POST['id'];
		$tipo_operacion 		= $_POST['tipo_operacion'];
		$tipo_criptomoneda	 	= $_POST['tipo_criptomoneda'];
		$descripcion 			= $_POST['descripcion_asiento_contable'];
		$fecha = date("d-m-Y");


		if (!empty($asientos))
		{
			foreach ($asientos as $r) 
			{

				$id_r = $r['id'];
				$tipo_criptomoneda_r	= $r['codigo_tipo_criptomoneda'];
				$tipo_operacion_r       = $r['codigo_tipo_operacion'];

				if($tipo_criptomoneda == $tipo_criptomoneda_r && $tipo_operacion_r == $tipo_operacion)
				{
					header('Location: index.php?page=inicioeasientocontable&mensaje=El Asiento contable ya existe&alert=danger');
					die();
				}
				else
				{
					

					$datos = array(
						'descripcion_asiento_cripto'   	=> $descripcion,
						'codigo_tipo_criptomoneda'    	=> $tipo_criptomoneda,
						'codigo_tipo_operacion'			=> $tipo_operacion,
					);
			
					$response = $objeto->editarAsientocontable($id, $datos);

					
			
					if($response)
					{
						$datos4 = array(
							'codigo_contable'               => 1111,
							'cuenta_movimiento'             => 101,
							'debito_credito'                => '+',
							'descripcion_asiento_cripto'    => $descripcion,
							'fecha'							=> "$fecha",
						);
					
						$response =  $objet4->insertarDetallecontable($datos4);
			
						if($response){
							header('Location: index.php?page=inicioeasientocontable&mensaje=Asiento Modificado&alert=success');
							die();
						}
					}
				}
			}

		}

		/* Fin validación */

    	
	
    }

	if (!empty($easientocontable)) 
	{
		foreach ($easientocontable as $r) 
		{ 

		$idasiento = $r['id'];
		$operacion = $r['descripcion'];
		$codigo_operacion = $r['codigo_tipo_operacion'];
		$r['moneda'];
		$descripcion_asiento = $r['descripcion_asiento_cripto'];

		}
	} 

?>
	<br>
	<main style="background-color: #FFF; border-radius:5px;" role="main" class="container py-5 my-5">
		<div class="starter-template">
			<h1>Modificar Asiento <?= $_GET['id'] ?></h1>
			<hr>
			<div class="col-md-6 offset-3">
			<form action="index.php?page=editarasientocontable" method="POST" name="registroForm" id="registroForm" class="text-left">
				<div class="modal-body">

					<div class="form-group">
						<input type="hidden" name="id" value="<?= $_GET['id'] ?>">
						<label for="tipo_operacion">Tipo de Operación</label>
						<select  class="form-control" name="tipo_operacion" id="tipo_operacion" aria-describedby="nombreHelp">
							<option value="<?= $codigo_operacion ?>"><?= $operacion ?></option>
							<?php
								if (!empty($tipo_operacion)) {
									foreach ($tipo_operacion as $rr) { 
								?>

								<option value="<?=$rr['id'];?>"><?=$rr['descripcion'];?></option>
								
							<?php } } ?>
						</select>
						<small id="nombreHelp" class="form-text text-muted">Ingrese el tipo de criptomoneda.</small>
					</div>

					<div class="form-group">
						<label for="tipo_criptomoneda">Tipo de Moneda</label>
						<select class="form-control" name="tipo_criptomoneda" id="tipo_criptomoneda" aria-describedby="nombreHelp">
							<?php
								if (!empty($tipo_cripto_contable)) {
									foreach ($tipo_cripto_contable as $r) { 
								?>

								<option value="<?=$r['id_moneda'];?>"><?=$r['moneda'];?></option>
								
							<?php } } ?>
						</select>
						<small id="nombreHelp" class="form-text text-muted">Ingrese el tipo de moneda.</small>
					</div>
				
					<div class="form-group">
						<label for="descripcion_asiento_contable">Descripción</label>
						<input type="text" id="descripcion_asiento_contable" pattern="[a-z-A-Z ]{1,15}" maxlength="30" value="<?= $descripcion_asiento ?>" required name="descripcion_asiento_contable" class="form-control" aria-describedby="nombreHelp">
						<small id="descripcion_asiento_contable" class="form-text text-muted">Ingrese la descripción del asiento.</small>
					</div>

				</div>
				<div class="modal-footer" style="display: flex;
    				justify-content: space-between; margin-top:20px;">
				<a class="btn btn-primary" href="http://127.0.0.1/criptoremesas/index.php?page=inicioeasientocontable"> <i class="fas fa-arrow-left"></i> Regresar</a>
					<button type="submit" id="editar" name="editar" class="btn btn-primary">Guardar</button>
				</div>
			</form>
			</div>
		</div>
								
	</main><!-- /.container -->
	