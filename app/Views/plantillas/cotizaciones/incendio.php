<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap core CSS -->
  <link href="<?= base_url("assets/dist/css/bootstrap.min.css") ?>" rel="stylesheet">

  <title>Cotización</title>

  <!-- Tamaño ideal para la plantilla -->
  <style>
    @page {
      size: A3;
    }
  </style>
</head>

<body>
  <div class="card-group">
    <div class="card border-0">
      <div class="card-body">
        <img src="<?= base_url("img/nobe.png") ?>" width="100" height="100">
      </div>
    </div>

    <div class="card border-0">
      <div class="card-body">
        <h4 class="card-title text-center">COTIZACIÓN <br> SEGURO INCENDIO HIPOTECARIO</h4>
      </div>
    </div>

    <div class="card border-0">
      <div class="card-body">
        <p class="card-text" style="text-align: right">
          Fecha <?= date("d/m/Y") ?>
        </p>
      </div>
    </div>
  </div>

  <div class="col-12">
    &nbsp;
  </div>

  <h5 class="card-title d-flex justify-content-center bg-primary text-white">ASEGURADORAS</h5>
  <div class="card-group border">
    <div class="card border-0">
      <div class="card-body">
        <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">

        <dl><b>Tipo de Construcción</b></dl>
        <dl><b>Tipo de Riesgo</b></dl>
        <dl><b>Plazo en meses</b></dl>
        <dl><b>Valor de la propiedad</b></dl>
        <dl><b>Valor del Préstamo</b></dl>

        <hr>

        <dl><b>Prima Neta</b></dl>
        <dl><b>ISC</b></dl>
        <dl><b>Prima Mensual</b></dl>
      </div>
    </div>

    <?php foreach ($cotizacion["planes"] as $plan) : ?>

      <div class="card border-0">
        <div class="card-body">
          <img src="<?= base_url("img/aseguradoras/" . $plan["id"] . ".png") ?>" height="50" width="150">

          <dl><?= $cotizacion["construccion"] ?></dl>
          <dl><?= $cotizacion["riesgo"] ?></dl>
          <dl><?= $cotizacion["plazo"] ?></dl>
          <dl>RD$<?= number_format($cotizacion["propiedad"], 2) ?></dl>
          <dl>RD$<?= number_format($cotizacion["prestamo"], 2) ?></dl>

          <hr>

          <dl>RD$<?= number_format($plan["neta"], 2) ?></dl>
          <dl>RD$<?= number_format($plan["isc"], 2) ?></dl>
          <dl>RD$<?= number_format($plan["total"], 2) ?></dl>
        </div>
      </div>

    <?php endforeach ?>

  </div>


  <div class="col-12">
    &nbsp;
  </div>
  <div class="col-12">
    &nbsp;
  </div>
  <div class="col-12">
    &nbsp;
  </div>
  <div class="col-12">
    &nbsp;
  </div>
  <div class="col-12">
    &nbsp;
  </div>
  <div class="col-12">
    &nbsp;
  </div>
  <div class="col-12">
    &nbsp;
  </div>

  <div class="card-group">
    <div class="card border-0">
      <div class="card-body">
        <p class="card-text text-center">
          _______________________________ <br> Firma Cliente
        </p>
      </div>
    </div>

    <div class="card border-0">
      <div class="card-body">
        <p class="card-text text-center">
          _______________________________ <br> Aseguradora Elegida
        </p>
      </div>
    </div>

    <div class="card border-0">
      <div class="card-body">
        <p class="card-text text-center">
          _______________________________ <br> Fecha
        </p>
      </div>
    </div>
  </div>

  <script>
    setTimeout(function() {
      window.print();
      window.close();
    }, 2000);
  </script>
</body>

</html>