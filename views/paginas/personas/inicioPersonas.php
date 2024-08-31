<!-- Begin Page Content -->

<?php


require_once 'controllers/RolesController.php';
require_once 'models/TipopersonaModel.php';
require_once 'models/PersonasModel.php';

$objeto                 = new RolesController();

$modelPersona = new PersonasModel();

$tipopersonaModel = new TipopersonaModel();

$tipos_personas = $tipopersonaModel->listarTipopersona();


$roles                  = $objeto->listaRoles();
$roles_update           = $objeto->listaRoles();


if (session_status() === PHP_SESSION_ACTIVE) {
    //echo "La sesión está activa.";
    $usuario            = $_SESSION['usuario'];
    $id_usuario         = $_SESSION['user_id'];
    $rol                = $_SESSION['rol_usuario'];
} else {
    //echo "La sesión no está activa.";
    session_start();
    $usuario            = $_SESSION['usuario'];
    $id_usuario         = $_SESSION['user_id'];
    $rol           = $_SESSION['rol_usuario'];
}


?>




<style>
    .file-upload {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 150px;
        padding: 30px;
        border: 1px dashed silver;
        border-radius: 8px;
    }

    .file-upload input {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        cursor: pointer;
        opacity: 0;
    }

    .preview_img {
        height: 80px;
        width: 80px;
        border: 4px solid silver;
        border-radius: 100%;
        object-fit: cover;
    }
</style>



<?php

if ($rol == 3) {
    echo "<h1>No tienes los permisos suficientes para ingresar en este modulo</h1>";
} else {
?>

    <div class="pagetitle">
        <h1>Proveedores</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <p></p>
                        <!-- Button trigger modal  -->
                        <button title="Agregar Personas" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarPersonas">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="tablaPersonas">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Proveedores</th>
                                        <th>telefono</th>
                                        <th>fecha</th>
                                        <th>Tipo de proveedores</th>
                                        <th>Estatus</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php
}

?>
<!-- /.container-fluid -->

<!--Modal Agregar Personas -->
<div class="modal fade" id="modalAgregarPersonas" tabindex="-1" aria-labelledby="agregarPersonasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarPersonasLabel">Agregar la Personas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formRegistrarPersonas">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nombre_apellidos">Proveedores</label>
                                <input class="form-control" type="text" onkeyup="mayus(this);" id="nombre_apellidos" name="nombre_apellidos" placeholder="Ingresa el Proveedor">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input class="form-control" type="number" onkeyup="mayus(this);" id="telefono" name="telefono" placeholder="Ingrese el numero de telefono">
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="id_tipo_persona">Tipo de Proveedores</label>
                                <select class="form-control" name="id_tipo_persona" id="id_tipo_persona">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($tipos_personas as $tipos_personas) {
                                    ?>
                                        <option value="<?= $tipos_personas['id'] ?>"><?= $tipos_personas['descripcion'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="estatus">Estatus</label>
                                <select class="form-control" name="estatus" id="estatus">
                                    <option value="">Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_personas" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>




<!-- Modal Actualizar Personas-->
<!-- <div class="modal fade" id="modalActualizarPersonas" tabindex="-1" aria-labelledby="modalActualizarPersonasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarPersonasLabel">Modificar la Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formActualizarPersonas">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input id="id_personas_update" type="hidden" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nombre_apellidos_update">Nombre y apellido</label>
                                <input class="form-control" type="text" onkeyup="mayus(this);" id="nombre_apellidos_update" placeholder="Ingresa el nombre y el apellido">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="telefono_update">Telefono</label>
                                <input class="form-control" type="text" onkeyup="mayus(this);" id="telefono_update" placeholder="Ingrese el telefono">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="id_tipo_persona_update">Tipo de Persona</label>
                                <select class="form-control" name="id_tipo_persona_update" id="id_tipo_persona_update">
                                    <option value="">Seleccione</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="estatus_update">Estatus</label>
                                <select class="form-control" name="estatus" id="estatus_update">
                                    <option value="">Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="modificar_persona" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->






<!-- Modal Visualizar Personas-->
<div class="modal fade" id="modalVisualizarPersonas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalVisualizarPersonasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVisualizarPersonasLabel">Personas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hove">
                        <tr>
                            <th>Proveedores</th>
                            <th>Telefono</th>
                            <th>Fecha</th>
                            <th>descripcion</th>
                            <th>Estado</th>

                        </tr>
                        <tr>
                            <td id="nombre_apellidos_personas"></td>
                            <td id="telefono_personas"></td>
                            <td id="fecha_registro_personas"></td>
                            <td id="id_tipo_persona_personas"></td>
                            <td id="estatus_personas"></td>
                        </tr>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>