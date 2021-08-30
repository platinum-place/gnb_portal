<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>Cotización</title>
</head>

<body>
  <div class="row">

    <!-- Encabezados -->
    <div class="col-3">
      <img src="<?= base_url("img/nobe.png") ?>" width="100" height="100">
    </div>
    <div class="col-6 text-center">
      <h5>COTIZACIÓN <br> SEGURO INCENDIO HIPOTECARIO</h5>
    </div>
    <div class="col-3" style="text-align: right">
      Fecha <?= date("d/m/Y") ?>
    </div>

    <div class="col-12">
      &nbsp;
    </div>

    <div class="col-12 d-flex justify-content-center bg-primary text-white">
      <h6>DATOS</h6>
    </div>

    <div class="col-6 border">
      <div class="row">
        <div class="col-6">
          <b>Cliente:</b><br>
          <b>Valor de la propiedad:</b><br>
          <b>Valor del Préstamo:</b><br>
          <b>Plazo en meses:</b>
        </div>

        <div class="col-6">
          <?= $detalles["cliente"] ?> <br>
          RD$<?= number_format($detalles["propiedad"], 2) ?><br>
          RD$<?= number_format($detalles["prestamo"], 2) ?> <br>
          <?= $detalles["plazo"] ?>
        </div>
      </div>
    </div>

    <div class="col-6 border">
      <div class="row">
        <div class="col-4">
          <b>Tipo de Construcción:</b><br>
          <b>Tipo de Riesgo:</b><br>
          <b>Dirección:</b>
        </div>

        <div class="col-8">
          <?= $detalles["riesgo"] ?> <br>
          <?= $detalles["construccion"] ?><br>
          <?= $detalles["direccion"] ?>
        </div>
      </div>
    </div>

    <div class="col-12">
      &nbsp;
    </div>

    <div class="col-12 d-flex justify-content-center bg-primary text-white">
      <h6>ASEGURADORAS</h6>
    </div>

    <div class="card-group">
      <div class="card">
        <div class="card-body">
          <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">
          <hr>
          <p class="card-text">
          <dl><b>Valor de la propiedad</b></dl>
          <dl><b>Valor del Préstamo</b></dl>
          </p>
          <hr>
          <p class="card-text">
          <dl><b>Prima Neta</b></dl>
          <dl><b>ISC</b></dl>
          <dl><b>Prima Mensual</b></dl>
          </p>
        </div>
      </div>

      <?php foreach ($detalles["planes"] as $plan) : ?>

        <div class="card">
          <div class="card-body">
            <img src="<?= base_url("img/aseguradoras/" . $plan["id"] . ".png") ?>" height="50" width="150">
            <hr>
            <p class="card-text">
            <dl>RD$<?= number_format($detalles["propiedad"], 2) ?></dl>
            <dl>RD$<?= number_format($detalles["prestamo"], 2) ?></dl>
            </p>
            <hr>
            <p class="card-text">
            <dl>RD$<?= number_format($plan["neta"], 2) ?></dl>
            <dl>RD$<?= number_format($plan["isc"], 2) ?></dl>
            <dl>RD$<?= number_format($plan["total"], 2) ?></dl>
            </p>
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


    <div class="row">
      <div class="col-4">
        <p class="text-center">
          _______________________________ <br> Firma Cliente
        </p>
      </div>

      <div class="col-4">
        <p class="text-center">
          _______________________________ <br> Aseguradora Elegida
        </p>
      </div>

      <div class="col-4">
        <p class="text-center">
          _______________________________ <br> Fecha
        </p>
      </div>
    </div>

  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->


  <script>
    setTimeout(function() {
      window.print();
      window.close();
    }, 2000);
  </script>
</body>

</html>