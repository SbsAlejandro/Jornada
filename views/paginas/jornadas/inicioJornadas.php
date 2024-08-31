<!-- Begin Page Content -->

<?php

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


require_once 'models/PersonasModel.php';
require_once 'models/EspecieModel.php';
require_once 'models/PresentacionModel.php';
require_once 'models/JornadasModel.php';
require_once 'models/BeneficiariosModel.php';

$modelPersona               = new PersonasModel();
$modelEspecies              = new EspecieModel();
$modelPresentacion          = new PresentacionModel();
$modelJornadas              = new JornadasModel();
$modelBeneficiarios         = new BeneficiariosModel();

$emma_parroquial            = $modelPersona->obtenerTipoPersona('EMMA PARROQUIAL');
$secretaria_alimentacion    = $modelPersona->obtenerTipoPersona('SECRETARIA DE ALIMENTACION DE LA ALCALDIA DE CARACAS');
$vocero                     = $modelPersona->obtenerTipoPersona('VOCERO');
$doca                       = $modelPersona->obtenerTipoPersona('OPERADOR CARAVANA O ALIADO');
$especies                   = $modelEspecies->listarEspecies();
$presentaciones             = $modelPresentacion->listarPresentacion();
$estados                    = $modelJornadas->listarEstados();
$estados_update             = $modelJornadas->listarEstados();
$beneficiarios              = $modelBeneficiarios->listarBeneficiarios();

$emma_parroquial_update            = $modelPersona->obtenerTipoPersona('EMMA PARROQUIAL');
$secretaria_alimentacion_update    = $modelPersona->obtenerTipoPersona('SECRETARIA DE ALIMENTACION DE LA ALCALDIA DE CARACAS');
$vocero_update                     = $modelPersona->obtenerTipoPersona('VOCERO');
$doca_update                       = $modelPersona->obtenerTipoPersona('OPERADOR CARAVANA O ALIADO');
$especies_update                   = $modelEspecies->listarEspecies();
$presentaciones_update             = $modelPresentacion->listarPresentacion();
$estados_update                    = $modelJornadas->listarEstados();
$beneficiarios_update              = $modelBeneficiarios->listarBeneficiarios();
$parroquia_listar                  = $modelJornadas->listarParroquias();




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
        <h1>Jornadas</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <p></p>
                        <!-- Button trigger modal  -->
                        <button title="Agregar Jornada" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearJornada">
                            <i class="fas fa-plus"></i>
                        </button>
                        <div class="table-responsive">
                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="tablaJornadas">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Observaciones</th>
                                        <th>Beneficiario</th>
                                        <th>Tipo de distribución</th>
                                        <th>Origen</th>
                                        <th>Destino</th>
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

<!-- Modal Agregar Jornadas-->
<div class="modal fade" id="modalCrearJornada" tabindex="-1" aria-labelledby="modalCrearJornadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearJornadaLabel">Crear Jornada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formRegistrarJornada">

                    <p><strong><span class="badge bg-info text-white">Datos de la jornada</span></strong></p>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">EMA parroquial</label>
                                <select class="form-control" name="id_ma" id="id_ma">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($emma_parroquial as $emma_parroquial) {
                                    ?>
                                        <option value="<?= $emma_parroquial['id'] ?>"><?= $emma_parroquial['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Secretaria de Alimentación</label>
                                <select class="form-control" name="id_sa" id="id_sa">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($secretaria_alimentacion as $secretaria_alimentacion) {
                                    ?>
                                        <option value="<?= $secretaria_alimentacion['id'] ?>"><?= $secretaria_alimentacion['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Vocer@</label>
                                <select class="form-control" name="id_vocero" id="id_vocero">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($vocero as $vocero) {
                                    ?>
                                        <option value="<?= $vocero['id'] ?>"><?= $vocero['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Datos operador caravana o aliado</label>
                                <select class="form-control" name="id_doca" id="id_doca">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($doca as $doca) {
                                    ?>
                                        <option value="<?= $doca['id'] ?>"><?= $doca['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="especie">Número de familias a atender</label>
                                <input class="form-control" type="number" id="nro_familias_atender" name="nro_familias_atender" placeholder="Ingrese el número de familias a atender">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Beneficiario</label>
                                <select class="form-control" name="id_beneficiario" id="id_beneficiario">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($beneficiarios as $beneficiarios) {
                                    ?>
                                        <option value="<?= $beneficiarios['id'] ?>"><?= $beneficiarios['descripcion'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="especie">Kilos a ofrecer</label>
                                <input class="form-control" type="number" id="kl_ofrecer" name="kl_ofrecer" placeholder="Ingrese el número de kilos a ofrecer">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="especie">Número de placa o caravana</label>
                                <input class="form-control" type="text" id="nro_placa_caravana" name="nro_placa_caravana" placeholder="Ingrese el número de placa o caravana">
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Tipo de distribución</label>
                                <select class="form-control" name="id_tipo_distribucion" id="id_tipo_distribucion">
                                    <option value="">Seleccione</option>
                                    <option value="VENTAS">VENTAS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Origen</label>
                                <select class="form-control" name="id_origen" id="id_origen">
                                    <option value="">Seleccione</option>
                                    <option value="ALIANZA CON PRIVADAS">ALIANZA CON PRIVADAS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Destino</label>
                                <select class="form-control" name="id_destino" id="id_destino">
                                    <option value="">Seleccione</option>
                                    <option value="COMUNIDADES">COMUNIDADES</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="fecha">Fecha</label>
                            <input class="form-control" type="date" id="fecha">
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="especie">Dirección</label>
                                <input class="form-control" type="text" id="direccion" name="direccion" placeholder="Ejemplo: Calle zamora">
                            </div>
                        </div>
                    </div>

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
                                <option value="1">Nombre</option>
                                <option value="2">Apellido</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="parroquia">Parroquia</label>
                            <select class="form-control" name="parroquia" id="parroquia">
                                <option value="">Seleccione</option>
                                <option value="1">Nombre</option>
                                <option value="2">Apellido</option>
                            </select>
                        </div>
                    </div>

                    <br>
                    <p><strong><span class="badge bg-info text-white">Especies de la jornada</span></strong></p>
                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="tasa_bcv"><span class="badge bg-success">Tasa BCV</span></label>
                                <input class="form-control form-control-lg" id="tasa_bcv" type="number">
                            </div>
                        </div>

                        <div class="col-sm-3" style="display: flex; justify-content: start; align-items: end;">
                            <button type="button" style="margin-right: 10px;" class="btn btn-primary btn-lg" id="agregar_tasa_bcv"><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-lg" id="eliminar_tasa_bcv"><i class="fas fa-trash"></i></button>
                        </div>

                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="id_especie">Especies</label>
                                <select class="form-control" name="id_especie" id="id_especie">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($especies as $especies) {
                                    ?>
                                        <option value="<?= $especies['id'] ?>"><?= $especies['especie'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="id_presentaciacion">Presentacion</label>
                                <select class="form-control" name="id_presentacion" id="id_presentacion">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($presentaciones as $presentaciones) {
                                    ?>
                                        <option value="<?= $presentaciones['id'] ?>"><?= $presentaciones['descripcion'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="">Precio Bs</label>
                                <input class="form-control" type="number" name="precio_bs_update" id="precio_bs" placeholder="Ejemplo: 10,00">
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="">Disponibilidad</label>
                                <input class="form-control" type="number" name="disponibilidad_kl" id="disponibilidad_kl" placeholder="Ejemplo: 20 kl">
                            </div>
                        </div>


                        <div class="col-sm-2" style="display: flex; align-items: flex-end; margin-bottom: 2px;">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-circle" id="agregar_especie_temporal" title="Agregar especie"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row" id="contenedor_datos_especies_multiples" style="display:none;">
                        <div class="col-sm-12 table-responsive" id="">
                            <p>Especies</p>
                            <table class="table table-bordered table-striped table-hover" id="multiples_especies">
                                <tr>
                                    <th>Especies</th>
                                    <th>Presentación</th>
                                    <th>Bolivares</th>
                                    <th>Dolares</th>
                                    <th>Disponibilidad</th>
                                    <!--<th>Kilos</th>-->
                                    <th>Acciones</th>
                                </tr>
                            </table>
                        </div>
                                  
                    </div>

                    <br>
                    <div class="row">
                        <div class="form-group">
                            <label for="">Observación</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="" id="observacion" rows="10" cols="10">

                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="agregar_jornada" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>





<!-- Modal Actualizar jornada-->
<div class="modal fade" id="modalActualizarJornadas" tabindex="-1" aria-labelledby="modalActualizarJornadasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarJornadasLabel">Modificar Jornada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formActualizarJornadas">

                    <p><strong><span class="badge bg-info text-white">Datos de la jornada</span></strong></p>
                    <div class="row">

                        <input type="hidden" id="id_jornada_update" value="">

                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">EMA parroquial</label>
                                <select class="form-control" name="id_ma_update" id="id_ma_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($emma_parroquial_update as $emma_parroquial_update) {
                                    ?>
                                        <option value="<?= $emma_parroquial_update['id'] ?>"><?= $emma_parroquial_update['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Secretaria de Alimentación</label>
                                <select class="form-control" name="id_sa_update" id="id_sa_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($secretaria_alimentacion_update as $secretaria_alimentacion_update) {
                                    ?>
                                        <option value="<?= $secretaria_alimentacion_update['id'] ?>"><?= $secretaria_alimentacion_update['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Vocer@</label>
                                <select class="form-control" name="id_vocero_update" id="id_vocero_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($vocero_update as $vocero_update) {
                                    ?>
                                        <option value="<?= $vocero_update['id'] ?>"><?= $vocero_update['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Datos operador caravana o aliado</label>
                                <select class="form-control" name="id_doca_update" id="id_doca_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($doca_update as $doca_update) {
                                    ?>
                                        <option value="<?= $doca_update['id'] ?>"><?= $doca_update['nombre_apellidos'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nro_familias_atender_update">Número de familias a atender</label>
                                <input class="form-control" type="number" id="nro_familias_atender_update" name="nro_familias_atender_update" placeholder="Ingrese el número de familias a atender">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Beneficiario</label>
                                <select class="form-control" name="id_beneficiario_update" id="id_beneficiario_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($beneficiarios_update as $beneficiarios_update) {
                                    ?>
                                        <option value="<?= $beneficiarios_update['id'] ?>"><?= $beneficiarios_update['descripcion'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="especie">Kilos a ofrecer</label>
                                <input class="form-control" type="number" id="kl_ofrecer_update" name="kl_ofrecer_update" placeholder="Ingrese el número de kilos a ofrecer">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nro_placa_caravana_update">Número de placa o caravana</label>
                                <input class="form-control" type="text" id="nro_placa_caravana_update" name="nro_placa_caravana_update" placeholder="Ingrese el número de placa o caravana">
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Tipo de distribución</label>
                                <select class="form-control" name="id_tipo_distribucion_update" id="id_tipo_distribucion_update">
                                    <option value="">Seleccione</option>
                                    <option value="VENTAS">VENTAS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Origen</label>
                                <select class="form-control" name="id_origen_update" id="id_origen_update">
                                    <option value="">Seleccione</option>
                                    <option value="ALIANZA CON PRIVADAS">ALIANZA CON PRIVADAS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="estatus">Destino</label>
                                <select class="form-control" name="id_destino_update" id="id_destino_update">
                                    <option value="">Seleccione</option>
                                    <option value="COMUNIDADES">COMUNIDADES</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="fecha">Fecha</label>
                            <input class="form-control" disabled type="date" id="fecha_update">
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="especie">Dirección</label>
                                <input class="form-control" type="text" id="direccion_update" name="direccion_update" placeholder="Ejemplo: Calle zamora">
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
                                foreach ($estados_update as $estados_update) {
                                ?>
                                    <option value="<?= $estados_update['id_estado'] ?>"><?= $estados_update['estado'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="municipio">Municipio</label>
                            <select class="form-control" name="municipio_update" id="municipio_update">
                                <option value="">Seleccione</option>
                                <option value="1">Nombre</option>
                                <option value="2">Apellido</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="parroquia">Parroquia</label>
                            <select class="form-control" name="parroquia_update" id="parroquia_update">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($parroquia_listar as $parroquia) {
                                ?>
                                    <option value="<?= $parroquia['id_parroquia'] ?>"><?= $parroquia['parroquia'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <br>
                    <p><strong><span class="badge bg-info text-white">Especies de la jornada</span></strong></p>
                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="tasa_bcv"><span class="badge bg-success">Tasa BCV</span></label>
                                <input class="form-control form-control-lg" id="tasa_bcv_update" type="number">
                            </div>
                        </div>

                        <div class="col-sm-3" style="display: flex; justify-content: start; align-items: end;">
                            <button type="button" style="margin-right: 10px;" class="btn btn-primary btn-lg" id="agregar_vendidos_kl_update"><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-lg" id="eliminar_tasa_bcv_update"><i class="fas fa-trash"></i></button>
                        </div>

                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="id_especie_update">Especies</label>
                                <input type="hidden" id="id_modificar_especie" value="">
                                <select class="form-control" name="id_especie_update" id="id_especie_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($especies_update as $especies_update) {
                                    ?>
                                        <option value="<?= $especies_update['id'] ?>"><?= $especies_update['especie'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="id_presentacion_update">Presentacion</label>
                                <select class="form-control" name="id_presentacion_update" id="id_presentacion_update">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($presentaciones_update as $presentaciones_update) {
                                    ?>
                                        <option value="<?= $presentaciones_update['id'] ?>"><?= $presentaciones_update['descripcion'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Precio Bs</label>
                                <input class="form-control" type="number" name="precio_bs_update" id="precio_bs_update" placeholder="Ejemplo: 10,00">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Disponibilidad</label>
                                <input class="form-control" type="number" name="disponibilidad_kl_update" id="disponibilidad_kl_update" placeholder="Ejemplo: 20 kl">
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="vendidos_kl_update">kilos vendidos</label>
                                <input class="form-control" type="number" name="vendidos_kl_update" id="vendidos_kl_update" placeholder="">
                            </div>
                        </div>

                        <div class="col-sm-2" style="display: flex; align-items: flex-end; margin-bottom: 2px;">
                           <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-circle" id="agregar_especie_update" title="Agregar especie"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success btn-circle" style="display: none;" id="modificar_especie_jornada" title="Modificar"><i class="fas fa-edit"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger btn-circle" style="display: none;" id="cancelar_especie_update" onclick="cancelarEspecieUpdate()" title="Cancelar"><i class="fas fa-ban"></i></button>
                                    </div>
                                </div>
                           </div>
                        </div>

                        <br>
                        <div class="row" id="contenedor_datos_especies_multiples_update" style="display:none;">
                            <div class="col-sm-12 table-responsive" id="">
                                <p>Especies</p>
                                <table class="table table-bordered table-striped table-hover" id="multiples_especies_update">
                                    <tr>
                                        <th>Especies</th>
                                        <th>Presentación</th>
                                        <th>Bolivares</th>
                                        <th>Dolares</th>
                                        <th>Disponibilidad</th>
                                        <th>Kilos vendidos</th>
                                        <th>Acciones</th>
                                    </tr>
                                </table>
                            </div>
                                      
                        </div>

                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label for="">Observación</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="" id="observacion_update" rows="10" cols="10">

                                </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" title="Cerrar el modal" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="modificar_jornadas" title="Guardar cambios"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                </form>

            </div>

        </div>
    </div>
</div>