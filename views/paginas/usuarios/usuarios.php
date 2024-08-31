<!-- Begin Page Content -->

<?php


require_once 'controllers/RolesController.php';
$objeto                 = new RolesController();

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
        <h1>Usuarios</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <p></p>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarUsuarioModal">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="tablaUsuario">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cedula</th>
                                        <th>Usuario</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Correo</th>
                                        <th>Foto</th>
                                        <th>Estado</th>
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




<!-- Modal Agregar Usuario -->
<div class="modal fade" id="agregarUsuarioModal" tabindex="-1" aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarUsuarioModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarUsuario">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="cedula">Cedula</label>
                                    <input class="form-control" type="text" name="cedula" id="cedula" maxlength="10" placeholder="Ingresa el número de Foto">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input class="form-control" type="text" name="nombre" id="nombre" onkeyup="mayus(this);" maxlength="40" placeholder="Ingresa el nombre">
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="apellido">Apellido</label>
                                    <input class="form-control" type="apellido" name="apellido" id="apellido" onkeyup="mayus(this);" maxlength="40" placeholder="Ingresa el apellido">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="correos">Correo</label>
                                    <input class="form-control" type="email" name="correo" id="correo" onkeyup="mayus(this);" maxlength="60" placeholder="Ingresa la dirección">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contrasena">constraseña</label>
                                    <input class="form-control" type="password" name="contrasena" id="contrasena" maxlength="60" placeholder="Ingresa la contraseña">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="confirmar_contrasena">confirmar contraseña</label>
                                    <input class="form-control" type="password" name="confirmar_contrasena" id="confirmar_contrasena" maxlength="60" placeholder="Ingresa la contraseña">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="usuario">Usuario</label>
                                    <input class="form-control" type="text" name="usuario" id="usuario" maxlength="40" placeholder="Ingresa el nombre de usuario">
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


                        <div class="row">
                            <div class="col-sm-6" id="rol">
                                <div class="form-group">
                                    <label for="rol">Rol</label>
                                    <select class="form-control" name="rol" id="rol">
                                        <option value="">Seleccione</option>
                                        <?php
                                        foreach ($roles as $roles) {
                                        ?>
                                            <option value="<?= $roles['id'] ?>"><?= $roles['rol'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-6" id="cont_input_file2">
                                <div class="form-group">
                                    <label for="Foto">Foto</label>
                                    <input type="file" class=" form-control" name="archivo" id="subirfoto2" accept="image/*">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_usuario" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Actualizar Usuario-->

<!-- Modal -->
<div class="modal fade" id="modalActualizarUsuarios" tabindex="-1" aria-labelledby="modalActualizarUsuariosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarUsuariosLabel">Modificar Usuarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formActualizarUsuario">


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input id="id_usuario_update" name="id_usuario_update" type="hidden" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="cedula_update">Cedula</label>
                                <input class="form-control" type="text" id="cedula_update" name="cedula_update" placeholder="V/E">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nombre_update">Nombre</label>
                                <input class="form-control" type="text" id="nombre_update" name="nombre_update" onkeyup="mayus(this);" maxlength="40" placeholder="Ingresa el nombre">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="apellido_update">Apellido</label>
                                <input class="form-control" type="apellido" id="apellido_update" name="apellido_update" onkeyup="mayus(this);" maxlength="40" placeholder="Ingresa la Apellido">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="correo_update">Correo</label>
                                <input class="form-control" type="email" id="correo_update" name="correo_update" onkeyup="mayus(this);" maxlength="60" placeholder="Ingresa la dirección">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="contrasena_update">Contraseña</label>
                                <input class="form-control" type="password" id="contrasena_update" name="contrasena_update" maxlength="60" placeholder="Ingresa la contraseña">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="telefono_update">Confirmar contraseña</label>
                                <input class="form-control" type="password" id="confirmar_contrasena_update" name="confirmar_contrasena_update" maxlength="60" placeholder="Ingresa la contraseña">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="usuario_update">Usuario</label>
                                <input class="form-control" type="text" id="usuario_update" name="usuario_update" maxlength="40" placeholder="Ingresa el nombre de usuario">
                            </div>
                        </div>


                        <div class="col-sm-4">

                            <div class="form-group">
                                <label for="rol_update">Rol</label>
                                <select class="form-control" name="rol_update" id="rol_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($roles_update as $roles_update) {
                                    ?>
                                        <option value="<?= $roles_update['id'] ?>"><?= $roles_update['rol'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus_update">Estatus</label>
                                <select class="form-control" name="estatus_update" id="estatus_update">
                                    <option value="">Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4" id="cont_input_file" style="display: none;">
                            <div class="form-group">
                                <label for="Foto">Foto</label>
                                <input type="file" class=" form-control" name="archivo" id="subirfotoUpdate" accept="image/*">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <img class="img-circle" id="img_update_preview" style="width:100%;" src="" alt="">
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="check_foto" name="check_foto">
                            <label class="custom-control-label" for="check_foto">Actualizar foto de perfil</label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="modificar_usuario" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>










<!-- Modal Visualizar Usuario-->

<div class="modal fade" id="modalVisualizarUsuario" tabindex="-1" aria-labelledby="modalVisualizarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVisualizarUsuarioLabel">Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <a title="Datos del visitante" href="#" class="list-group-item  list-group-item-action active">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 id="nombre_apellido" class="mb-1">Informacion del Usuario</h5>
                                    <small id="fecha_usuario"></small>
                                </div>
                                <p id="cedula_usuario" class="mb-1"></p>
                                <p id="nombre_usuario" class="mb-1"></p>
                                <p id="usuario_usuario" class="mb-1"></p>
                                <p id="apellido_usuario" class="mb-1"></p>
                                <p id="correo_usuario" class="mb-1"></p>

                                <br>
                                <p id="estatus_usuario" class="mb-1"></p>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <img title="Foto del usuario" style="width: 100%; height: 100%;" id="foto_usuario" src="" alt="">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>