<?php

    require_once './controllers/TipocriptomonedacontableController.php';
	$objeto = new TipocriptomonedacontableController();

    /*obtener el valor 'id' de la url para buscar en la bd
    y posteriormente llenar el formulario*/
    
    if (isset($_GET['id'])) {
	    $idTcriptocontable = $_GET['id'];
    	$tcritomonedacontable = $objeto->obtenerCriptomonedacontable($idTcriptocontable);
    }

    if (isset($_POST['editar'])) {
    	$id = $_POST['id'];
    	$datos = array(
			'nombre'   	=> $_POST['nombre'],
			'email'    	=> $_POST['email'],
		);
		$objeto->editarCriptomonedacontable($id, $datos);
    }

?>
	<main role="main" class="container">
        <br><br><br>
		<div class="starter-template">
			<h1>Modificar Asiento</h1>
			<hr>
			<div class="col-md-6 offset-3">
				<form action="index.php?page=editar" method="POST" name="editarForm" id="editarForm" class="text-left">
					<?php
						if (!empty($tcritomonedacontable)) {
							foreach ($tcritomonedacontable as $r) { 
					?>
						<input type="hidden" name="id" value="<?= $r['id']; ?>">
						<div class="form-group">
							<label for="codasientocripto">Codigo Asiento Cripto</label>
							<input type="text" id="descripcion_criptomoneda" name="codasientocripto" class="form-control" aria-describedby="nombreHelp" value="<?= $r['descripcion_criptomoneda']; ?>">
							<small id="nombreHelp" class="form-text text-muted">Ingrese el nombre completo del cliente.</small>
						</div>

						<button type="submit" name="editar" class="btn btn-info">Editar registro</button>
					<?php } } ?>
				</form>
			</div>
		</div>

	</main><!-- /.container -->
	