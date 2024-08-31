<?php


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

ob_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FICHA REPORTE DONACION</title>
    <link rel="stylesheet" href="libs/css/sb-admin-2.min.css">
</head>

<body>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 11px;
        }

        table {
            border: solid 1px #000;
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            font-size: 11px;

        }

        tr {
            border: solid 1px #000;
        }

        td {
            border: solid 1px #000;

            padding: 10px;
        }

        th {
            border: solid 1px #000;

            padding: 5px;
        }

        .btn-success {
            background-color: #48C9B0;
            color: #FFF;
            border: none;
            padding: 5px;
            border-radius: 5px;
        }

        .btn-danger {
            background-color: #E74C3C;
            color: #FFF;
            border: none;
            padding: 5px;
            border-radius: 5px;
        }
    </style>

<div class="section">
        
        <div class="row">
            <div class="col-lg-12">
            <div style="width: 100%;">
                <img style="width: 100%; height:auto;" src="<?= SERVERURL ?>libs/img/cintillo1.png" alt="">
            </div>
            <br>
                <div class="container mt-4">
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
                                        
                                            <tr>
                                                <th>Especies</th>
                                                <th>Bs</th>
                                                <th>Presentación</th>
                                                <th>$ (Tasa BCV: 36,30 Bs)</th>
                                                <th>Disponibilidad (Kg)</th>
                                                <th>Vendidos (Kg)</th>
                                            </tr>
                                        
                                        
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
                                        
                                    </table>
                                    
                                </div>
                               
                                
                            </div>
                            
                        </div>
                       
                    </div>
                </div>
            </div>
            <br>
            <div style="width: 100%;">
                <img style="width: 100%; height:auto;" src="<?= SERVERURL ?>libs/img/cintillo2.png" alt="">
            </div>
        </div>


</body>

</html>

<?php

$html = ob_get_clean();
//echo $html;


require_once 'libs/dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf = new Dompdf(array('enable_remote' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$nombre_documento = "FICHA_REPORTE_DONACION";

$dompdf->stream("$nombre_documento", array("Atachment" => false));



?>