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



require_once 'models/JornadasModel.php';


$modelJornadas              = new JornadasModel();

$id_jornada = $_GET['id'];

$id_ma              = $modelJornadas->getIDMA($id_jornada);
$id_sa              = $modelJornadas->getIDSA($id_jornada);
$id_vocero          = $modelJornadas->getIDVOCERO($id_jornada);
$id_doca            = $modelJornadas->getDOCA($id_jornada);
$datos_jornadas     = $modelJornadas->getDATOSJORNADA($id_jornada);
$datos_especies     = $modelJornadas->getDATOSESPECIES($id_jornada);

foreach ($id_ma as $id_ma) {
    $nombres_apellidos_idma = $id_ma['nombre_apellidos'];
    $telefono_idma = $id_ma['telefono'];
}

foreach ($id_sa as $id_sa) {
    $nombres_apellidos_idsa = $id_sa['nombre_apellidos'];
    $telefono_idsa = $id_sa['telefono'];
}

foreach ($id_vocero as $id_vocero) {
    $nombres_apellidos_id_vocero = $id_vocero['nombre_apellidos'];
    $telefono_id_vocero = $id_vocero['telefono'];
}

foreach ($id_doca as $id_doca) {
    $nombres_apellidos_id_doca = $id_doca['nombre_apellidos'];
    $telefono_id_doca = $id_doca['telefono'];
}

foreach ($datos_jornadas as $datos_jornadas) {
    $fecha                      = $datos_jornadas['fecha'];
    $parroquia                  = $datos_jornadas['parroquia'];
    $direccion                  = $datos_jornadas['direccion'];
    $nro_familias_atender       = $datos_jornadas['nro_familias_atender'];
    $descripcion                = $datos_jornadas['descripcion'];
    $kl_ofrecer                 = $datos_jornadas['kl_ofrecer'];
}




?>



<?php


if ($rol == 3) {
    echo "<h1>No tienes los permisos suficientes para ingresar en este modulo</h1>";
} else {
?>
    <style>
        .section-header {
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
            padding: 10px;
        }

        .section-content {
            padding: 10px;
        }

        th,
        td {
            text-align: center;
        }
    </style>

    <div class="section">
        
        <div class="row">
            <div class="col-lg-12">
            <div style="display: flex; flex-direction: column;"><img src="libs/img/cintillo1.png" alt="" style="max-height: 166px;"></div>
                <div class="container mt-4">
                    <div class="container">
                        <a href="index.php?page=reporteFichaJornada&id=<?php echo $id_jornada; ?>" style="margin-bottom: 10px;" 
                                    class="btn btn-danger"
                                    title="Reporte ficha"
                                    target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                        </a>
                        <br>
                    </div>
                    <div class="row">
                
                        <div class="col-12 mb-3">
                            <div class="section-header">FECHA: <?= $fecha ?></div>
                            <div class="section-content">
                                <p><strong>Parroquia:</strong> <?= $parroquia ?></p>
                                <p><strong>Dirección:</strong> <?= $direccion ?></p>
                                <p><strong>EMA Parroquial:</strong> <?= $nombres_apellidos_idma ?> - <?= $telefono_idma ?></p>
                                <p><strong>Secretaría de Alimentación de la Alcaldía de Caracas:</strong> <?= $nombres_apellidos_idsa ?> - <?= $telefono_idsa ?></p>
                                <p><strong>Vocero(a):</strong> <?= $nombres_apellidos_id_vocero ?> -<?= $telefono_id_vocero ?></p>
                                <p><strong>Datos Operador Caravana o Aliado:</strong> <?= $nombres_apellidos_id_doca ?> - <?= $telefono_id_doca ?></p>
                                <p><strong>Nro de Familias Atender:</strong> <?= $nro_familias_atender ?></p>
                                <p><strong>Nombre de la Comuna a Beneficiar:</strong> <?= $descripcion ?></p>
                                <p><strong>Kilogramos a Ofertar:</strong> <?= $kl_ofrecer ?></p>
                            </div>
                            <div class="col-12">
                                <div class="section-header">Especies a Distribuir</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-success">
                                        <thead>
                                            <tr>
                                                <th>Especies</th>
                                                <th>Bs</th>
                                                <th>Presentación</th>
                                                <th>$ (Tasa BCV: 36,30 Bs)</th>
                                                <th>Disponibilidad (Kg)</th>
                                                <th>Vendidos (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $suma_dolares = 0;
                                            $total_kilos = 0;
                                            $total_vendidos = 0;

                                            foreach ($datos_especies as $datos_especies) {
                                            
                                                $suma_dolares       = $suma_dolares + $datos_especies['precio_dolares'];
                                                $total_kilos        = $total_kilos + $datos_especies['disponibilidad_kl'];
                                                $total_vendidos     = $total_vendidos + $datos_especies['vendidos_kl'];
                                            ?>
                                                <tr>
                                                    <td><?= $especie                    = $datos_especies['especie']; ?></td>
                                                    <td><?= $precio_bs                  = $datos_especies['precio_bs']; ?></td>
                                                    <td><?= $presentacion               = $datos_especies['presentacion']; ?></td>
                                                    <td><?= $precio_dolares             = $datos_especies['precio_dolares']; ?></td>
                                                    <td><?= $disponibilidad_kl          = $datos_especies['disponibilidad_kl']; ?> Kg</td>
                                                    <td><?= $disponibilidad_kl          = $datos_especies['vendidos_kl']; ?> Kg</td>
                                                </tr>
                                            <?php
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total (Kg) Ofertados / Distribuidos</th>
                                                <th><?= $suma_dolares ?> $</th>
                                                <th>Total <?= $total_kilos ?> Kg</th>
                                                <th>Total 
                                                    <?php 
                                                        if($total_vendidos != 0)
                                                        {
                                                            echo $total_vendidos;
                                                        }
                                                    ?>    
                                                Kg</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                </div>
                               
                                
                            </div>
                            
                        </div>
                       
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column;">
                                <img src="libs/img/cintillo2.png" alt="" style="max-height: 166px;">
                                </div>
        </div>


    <?php
}

    ?>
    <!-- /.container-fluid -->