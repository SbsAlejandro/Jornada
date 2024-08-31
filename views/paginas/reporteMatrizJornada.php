<?php

require_once 'models/JornadasModel.php';

$modelJornadas = new JornadasModel();

$matriz = $modelJornadas->reporteMatrizJornada();




?>

<?php

ob_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE MATRIZ</title>
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
            font-size: 5px;

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
                        <table class="table datatable table-success" id="tablaMatriz">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>TIPO DE DISTRIBUCIÓN</th>
                                    <th>ORIGEN</th>
                                    <th>Nº DE PLACA O Nº DE CARAVANA</th>
                                    <th>DESTINO</th>
                                    <th>BENEFICIARIO</th>
                                    <th>PARROQUIA</th>
                                    <th>ESPECIE</th>
                                    <th>PRESENTACIÓN</th>
                                    <th>KG. DISTRIBUIDOS</th>
                                    <th>KG. VENDIDOS</th>
                                    <th>PRECIO UNIT. BS</th>
                                    <th>TASA CAMBIO</th>
                                    <th>TOTAL BS</th>
                                    <th>EQUIV. USD</th>
                                    <th>OBSERVACIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($matriz as $matriz) 
                                    {
                                        ?>
                                            <tr>
                                                <td><?= $fecha = $matriz['fecha'] ?></td>
                                                <td><?= $id_tipo_distribucion = $matriz['id_tipo_distribucion'] ?></td>
                                                <td><?= $id_origen = $matriz['id_origen'] ?></td>
                                                <td><?= $id_doca = $matriz['id_doca'] ?></td>
                                                <td><?= $id_destino = $matriz['id_destino'] ?></td>
                                                <td><?= $beneficiarios = $matriz['beneficiarios'] ?></td>
                                                <td><?= $parroquia = $matriz['parroquia'] ?></td>
                                                <td><?= $especie = $matriz['especie'] ?></td>
                                                <td><?= $presentacion = $matriz['presentacion'] ?></td>
                                                <td><?= $kilos_distribuidos = $matriz['kilos_distribuidos'] ?></td>
                                                <td><?= $kl_vendidos = $matriz['kl_vendidos'] ?></td>
                                                <td><?= $precio_unitario_bs = $matriz['precio_unitario_bs'] ?></td>
                                                <td><?= $tasa_cambio_bcv = $matriz['tasa_cambio_bcv'] ?></td>
                                                <td><?= $total_bs = $matriz['total_bs'] ?></td>
                                                <td><?= $equiv_usd = $matriz['equiv_usd'] ?></td>
                                                <td><?= $observacion = $matriz['observacion'] ?></td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
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

$nombre_documento = "REPORTE_MATRIZ";

$dompdf->stream("$nombre_documento", array("Atachment" => false));



?>