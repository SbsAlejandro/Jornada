<?php

	require_once './controllers/TipocriptomonedacontableController.php';
	$objeto = new TipocriptomonedacontableController();
	$tipo_cripto_contable = $objeto->obtenerCriptomonedascontables();

	$objeto2 = new TipocriptomonedacontableController();
	$tipo_cripto_contable2 = $objeto2->obtenerCriptomonedascontables();

?>

	<main role="main" class="container py-5 my-5">

		<div class="starter-template">
			<h1>Tipo de Criptomoneda</h1>
			<div class="text-left">
				<button type="button" class="btn btn-primary text-left" data-bs-toggle="modal" data-bs-target="#insertarRegistroModal">Registrar Criptomoneda</button>				
			</div>
			<div class="row">
				<div class="col-md-6 offset-3">
					<?php
						if (isset($_GET['mensaje'])) {
							echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>
									".$_GET['mensaje']."
									<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
		    							<span aria-hidden='true'>&times;</span>
									</button>
								</div>";
						}
					?>
				</div>
			</div>
			<hr>
			<table class="table table-bordered" id="tablaCliente">
				<thead class="thead-dark">
					<tr>
						<th scope="col">id</th>
						<th scope="col">Criptomoneda</th>
                        <th scope="col">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if (!empty($tipo_cripto_contable)) {
							foreach ($tipo_cripto_contable as $r) { 
					?>
					<tr>
						<th scope="row"><?=$r['id'];?></th>
						<td><?=$r['descripcion_criptomoneda'];?></td>
						<td>
							<a href="?page=editartcriptomonedacontable&id=<?= $r['id']; ?>" type="a" class="btn btn-info">Editar</a>
							<a href="?page=eliminartcriptomonedacontable&id=<?= $r['id']; ?>" type="a" class="btn btn-danger">Eliminar</a>
						</td>
					</tr>
					<?php } } ?>
				</tbody>
			</table>
		</div>

	</main><!-- /.container -->


<!-- Modal Agregar -->
<div class="modal fade" id="insertarRegistroModal" tabindex="-1" role="dialog" aria-labelledby="agregarDatosModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="agregarDatosModalLabel">Insertar nuevos registros</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="index.php?page=insertar" method="POST" name="registroForm" id="registroForm" class="text-left">
				<div class="modal-body">
				<div class="form-group">
					<label for="tipo_criptomoneda">Criptomoneda</label>
					<select class="form-control" name="tipo_criptomoneda" id="tipo_criptomoneda" aria-describedby="nombreHelp">
						<?php
							if (!empty($tipo_cripto_contable2)) {
									foreach ($tipo_cripto_contable2 as $r) { 
							?>

							<option value="<?=$r['id'];?>"><?=$r['descripcion_criptomoneda'];?></option>
								
						<?php } } ?>
					</select>
					<small id="nombreHelp" class="form-text text-muted">Ingrese el tipo de criptomoneda.</small>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" id="btnInsertar" name="btnInsertar" class="btn btn-primary">Insertar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#tablaCliente').load();
	})


	$(document).ready(function(){
		$('#btnInsertar').click(function(){
			datos=$('#registroForm').serialize();

			$.ajax({
				type: "POST",
				data: datos,
				url:  "index.php?page=insertar.php",
				success: function(r){
					// if (r==1) {
					// 	$('#registroForm')[0].reset();
					// 	$('#tablaCliente').load();
					// 	alert ("Agregado Ok!");
					// } else {
					// 	alert("Fallo al agregar");
					// }
					alert(r);
				}
			});
		});
	});
</script>