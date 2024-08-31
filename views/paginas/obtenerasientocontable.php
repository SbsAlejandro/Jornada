<?php

    require_once 'controllers/EncabezadoasientocontableController.php';
    $objeto = new EncabezadoasientocontableController();

	require_once 'controllers/DetallecontableController.php';

	$detalle = new DetallecontableController();

    /*obtener el valor 'id' de la url para buscar en la bd
    y posteriormente llenar el formulario*/


    if (isset($_GET['id'])) {
	    $idEasientocontable = $_GET['id'];

    	$easientocontable = $objeto->obtenerAsientocontable($idEasientocontable);
		$detalles = $detalle->obtenerDetallecontable($idEasientocontable);
    }

?>

<br>
	<main style="background-color: #FFF; border-radius:5px;" role="main" class="container py-5 my-5">
	<h2 style="margin-bottom: 30px;">Asiento Contable <?php echo $idEasientocontable; ?></h2>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li style="font-size:20px;" class="breadcrumb-item"><a href="<?= SERVERURL ?>index.php?page=inicioeasientocontable">Asientos Contables</a></li>
			<li style="font-size:20px;" class="breadcrumb-item active" aria-current="page">Ver Asiento Contable</li>
		</ol>
	</nav>
    <table class="table table-bordered" id="tablaCliente">
		<thead class="thead-dark">
			<tr>
				<!--<th>id</th>-->
				<th>Operación</th>
				<th>Moneda</th>
				<th>Descripción</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($easientocontable)) {
				foreach ($easientocontable as $r) { 
				
					if($r["estatus"] == 0){
			?>
				<tr>
					<td style="background-color: #F1948A;"><?=$r['descripcion'];?></td>
					<td style="background-color: #F1948A;"><?=$r['moneda'];?></td>
					<td style="background-color: #F1948A;"><?=$r['descripcion_asiento_cripto'];?></td>
					<td style="background-color: #F1948A;"><?=$r['fecha'];?></td>
				</tr>
				<?php } } 
			}
			if($r["estatus"] == 1)
			{
				?>
				<tr>
					<td><?=$r['descripcion'];?></td>
					<td><?=$r['moneda'];?></td>
					<td><?=$r['descripcion_asiento_cripto'];?></td>
					<td><?=$r['fecha'];?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<br>
	<table class="table table-bordered" id="tablaDetalles">
		<thead class="thead-dark">
			<tr>
				<!--<th>id</th>-->
				<th>Codigo contable</th>
				<th>Cuenta movimiento</th>
				<th>Debito/Credito</th>
				<th>Descripción</th>
				<th>Fecha</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($detalles)){
				foreach ($detalles as $a){
					if($a["estatus"] == 1)
					{
					?>
					<tr>
						<td><?=$a['codigo_contable'];?></td>
						<td><?=$a['cuenta_movimiento'];?></td>
						<td>
							<?php
								if($a['debito_credito'] == "+"){
									?>
										<button style="width: 20%;" class="btn btn-success btn-sm" title="Crédito">+</button>
									<?php	
								}else{
									?>
										<button style="width: 20%;"  class="btn btn-success btn-danger btn-sm" title="Debito">-</button>
									<?php	
								}
							?>
						</td>
						<td><?=$a['descripcion_asiento_cripto'];?></td>
						<td><?=$a['fecha'];?></td>
					</tr>
					<?php
					}
					if($a["estatus"] == 0){
						?>
						<tr>
							<td style="background-color: #F1948A;"><?=$a['codigo_contable'];?></td>
							<td style="background-color: #F1948A;"><?=$a['cuenta_movimiento'];?></td>
							<td style="background-color: #F1948A;">
								<?php
									if($a['debito_credito'] == "+"){
										?>
											<button style="width: 20%;" class="btn btn-success btn-sm" title="Crédito">+</button>
										<?php	
									}else{
										?>
											<button style="width: 20%;"  class="btn btn-success btn-danger btn-sm" title="Debito">-</button>
										<?php	
									}
								?>
							</td>
							<td style="background-color: #F1948A;"><?=$a['descripcion_asiento_cripto'];?></td>
							<td style="background-color: #F1948A;"><?=$a['fecha'];?></td>
						</tr>
					<?php
					}
				}
			}
			
			?>
		</tbody>
	</table>
	<?php
	if(empty($a))
	{
		?>
		
		<div style=" display: flex; justify-content: center;flex-direction: column;align-items: center;">
		<p style="font-size: 21px;">Aún no hay detalles contables registrados en este asiento.</p>
		<p style="font-size: 18px;">Para registrar detalles contables debes ir a la sección modificar <a href="<?= SERVERURL ?>index.php?page=inicioeasientocontable">Modificar</a></p>
		</div>
		<?php
	}
	?>
</main><!-- /.container -->
	