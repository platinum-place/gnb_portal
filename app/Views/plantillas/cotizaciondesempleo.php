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
        <h5 class="card-title text-center">COTIZACIÓN <br> PLAN VIDA/DESEMPLEO</h5>
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

  <h5 class="card-title d-flex justify-content-center bg-primary text-white">DEUDOR</h5>
  <div class="card-group">
    <div class="card">
      <div class="card-body">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td style="width: 50%"><b>Cliente</b></td>
              <td><?= $detalles["cliente"] ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td style="width: 50%"><b>Fecha de Nacimiento</b></td>
              <td><?= $detalles["fecha"] ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    &nbsp;
  </div>

  <h5 class="card-title d-flex justify-content-center bg-primary text-white">PRIMA MENSUAL</h5>
  <div class="card-group border">
    <div class="card border-0">
      <div class="card-body">
        <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">

        <p class="card-text">
        <dl><b>Suma Asegurada</b></dl>
        <dl><b>Cuota Mensual de Prestamo</b></dl>
        <dl><b>Plazo</b></dl>
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

      <div class="card border-0">
        <div class="card-body">
          <img src="<?= base_url("img/aseguradoras/" . $plan["id"] . ".png") ?>" height="50" width="150">

          <p class="card-text">
          <dl>RD$<?= number_format($detalles["suma"], 2) ?></dl>
          <dl>RD$<?= number_format($detalles["cuota"], 2) ?></dl>
          <dl><?= $detalles["plazo"] ?></dl>
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

  <div class="card-group">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title text-center">REQUISITOS DEL DEUDOR</h5>
        <p class="card-text">
          <?php foreach ($requisitos as $aseguradora => $lista) : ?>
        <ul>
          <li>
            <b><?= $aseguradora ?></b>:
            <?php foreach ($lista as $requisito) : ?>
              <?= $requisito  ?>

              <?php if ($requisito === end($lista)) : ?>
                .
              <?php else : ?>
                ,
              <?php endif ?>
            <?php endforeach ?>
          </li>
        </ul>
      <?php endforeach ?>
      </p>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title text-center">OBSERVACIONES</h5>
        <p class="card-text">
        <ul>
          <li>Pago de desempleo por hasta 6 meses.</li>
        </ul>
        </p>
      </div>
    </div>
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