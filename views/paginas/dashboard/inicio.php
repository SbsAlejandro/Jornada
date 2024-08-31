<?php

require_once 'models/dashboardModel.php';


$dashboardModel = new dashboardModel();

$totalEquivUSD = $dashboardModel->totalEquivUSD();

foreach ($totalEquivUSD as $totalEquivUSD) {
  $totalEquivUSD = $totalEquivUSD['totalEquivUSD'];
}

$totalKlVendidos = $dashboardModel->totalKlVendidos();

foreach ($totalKlVendidos as $totalKlVendidos) {
  $totalKlVendidos = $totalKlVendidos['kl_vendidos'];
}


$totalBs = $dashboardModel->totalBs();

foreach ($totalBs as $totalBs) {
  $totalBs = $totalBs['total_bs'];
}





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
<div class="pagetitle">
  <h1>MATRIZ OPERACIÓN "VENEZUELA COME PESCADO"</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Incio</a></li>
      <li class="breadcrumb-item active">Panel</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">

        <!-- Sales Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Total vendidos <span>| General</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fas fa-cart-plus"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalKlVendidos ?> Kg</h6>


                </div>
              </div>
            </div>

          </div>
        </div><!-- End Sales Card -->

        <!-- Sales Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Total Distribuido <span>| General</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fas fa-fish"></i>
                </div>
                <div class="ps-3">
                  <h6><?= $totalKlVendidos ?> Kg</h6>


                </div>
              </div>
            </div>

          </div>
        </div><!-- End Sales Card -->

        <!-- Revenue Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Total equiv $ <span>| Total vendidos equiv USD</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="ps-3">
                  <h6>$<?= $totalEquivUSD ?></h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->


        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Total Bs <span>| Total vendidos equiv BS</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fas fa-money-bill-wave-alt"></i>
                </div>
                <div class="ps-3">
                  <h6><?= number_format($totalBs, 0, '', '.') ?> Bs</h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <!-- Reports -->
        <div class="col-12">
          <div class="card">

            <div class="card-body">
              <h5 class="card-title">Matriz <span>/ Por especie</span></h5>
              <div class="table-responsive">
                <!-- Table with stripped rows -->

                <div class="container">
                  <img src="libs/img/cintillo1.png" alt="" style="width: 100%;">
                </div><br>
                <div class="container">
                  <a href="index.php?page=reporteMatrizJornada&id=" style="margin-bottom: 10px;"
                    class="btn btn-danger"
                    title="Reporte Matriz"
                    target="_blank">
                    <i class="fas fa-file-pdf"></i>
                  </a>
                </div>
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

                  </tbody>
                </table>
                <!-- End Table with stripped rows -->

              </div>
            </div>

          </div>
        </div><!-- End Reports -->




      </div>
    </div><!-- End Left side columns -->

  </div>
</section>

<?php

?>