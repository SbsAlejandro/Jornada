<!-- Begin Page Content -->

<?php

require_once 'models/JornadasModel.php';
require_once 'controllers/RolesController.php';
$objeto                 = new RolesController();
$modelJornadas          = new JornadasModel();

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

$estados                          = $modelJornadas->listarEstados();
$estado_update                    = $modelJornadas->listarEstados();


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
        <h1>Beneficiarios</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <p></p>
                        <!-- Button trigger modal  -->
                        <button title="Agregar Beneficiarios" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarBeneficiarios">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="tablaBeneficiarios">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>parroquia</th>
                                        <th>descripcion</th>
                                        <th>Estados</th>
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

<!--Modal Agregar Beneficiario -->
<div class="modal fade" id="modalAgregarBeneficiarios" tabindex="-1" aria-labelledby="agregarBeneficiariosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarBeneficiariosLabel">Agregar beneficiario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formRegistrarBeneficiarios">
                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($estados as $estados) {
                                ?>
                                    <option value="<?= $estados['id_estado'] ?>"><?= $estados['estado'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="municipio" id="municipio">
                                <option value="">Seleccione</option>


                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="parroquia">Parroquia</label>
                            <select class="form-control" name="parroquia" id="parroquia">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input class="form-control" type="text" onkeyup="mayus(this);" id="descripcion" name="descripcion" placeholder="Ingresa la descripción">
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
                        <button type="submit" class="btn btn-primary" id="agregar_beneficiarios" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>




<!-- Modal Actualizar Beneficiarios-->
<div class="modal fade" id="modalActualizarBeneficiarios" tabindex="-1" aria-labelledby="modalActualizarBeneficiariosLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarBeneficiariosLabel">Modificar el Beneficiario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formActualizarBeneficiarios">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input id="id_beneficiarios_update" name="id_beneficiarios" type="hidden" value="">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-sm-4">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado_update" id="estado_update">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($estado_update as $estado_update) {
                                ?>
                                    <option value="<?= $estado_update['id_estado'] ?>"><?= $estado_update['estado'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="municipio_update">Municipio</label>
                            <select class="form-control" name="municipio_update" id="municipio_update">
                                <option value="">Seleccione</option>

                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="parroquia_update">Parroquia</label>
                            <select class="form-control" name="parroquia_update" id="parroquia_update">
                                <option value="">Seleccione</option>

                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="descripcion_update">Descripción</label>
                                <input class="form-control" type="text" onkeyup="mayus(this);" id="descripcion_update" name="descripcion_update" placeholder="Ingresa la descripción">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="estatus_update">Estatus</label>
                                <select class="form-control" name="estatus_update" id="estatus_update">
                                    <option value="">Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="modificar_beneficiarios" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

< <!-- Modal Visualizar Beneficiarios-->

    <div class="modal fade" id="modalVisualizarBeneficiarios" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalVisualizarBeneficiariosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVisualizarBeneficiariosLabel">Informacion del beneficiario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hove">
                            <tr>
                                <th>Parroquia</th>
                                <th>descripcion</th>
                                <th>Estado</th>

                            </tr>
                            <tr>
                                <td id="id_parroquia_beneficiarios"></td>
                                <td id="descripcion_beneficiarios"></td>
                                <td id="estatus_beneficiarios"></td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>