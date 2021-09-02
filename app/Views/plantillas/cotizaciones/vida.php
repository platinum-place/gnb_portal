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
                <h4 class="card-title text-center">COTIZACIÓN <br> PLAN VIDA</h4>
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

                <dl><b>Fecha Deudor</b></dl>
                <?php if (!empty($cotizacion["fecha_codeudor"])) : ?>
                    <dl><b>Fecha Codeudor</b></dl>
                <?php endif ?>
                <dl><b>Suma Asegurada</b></dl>
                <dl><b>Plazo</b></dl>

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

                    <dl><?= $cotizacion["fecha_deudor"] ?></dl>
                    <?php if (!empty($cotizacion["fecha_codeudor"])) : ?>
                        <dl><?= $cotizacion["fecha_codeudor"] ?></dl>
                    <?php endif ?>
                    <dl>RD$<?= number_format($cotizacion["suma"], 2) ?></dl>
                    <dl><?= $cotizacion["plazo"] ?> meses</dl>

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

    <div class="card-group">
        <div class="card small">
            <div class="card-body">
                <h6 class="card-title text-center">REQUISITOS DEL DEUDOR</h6>
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
            </div>
        </div>

        <?php if (!empty($cotizacion["fecha_codeudor"])) : ?>
            <div class="card small">
                <div class="card-body">
                    <h6 class="card-title text-center">REQUISITOS DEL CODEUDOR</h6>
                    <?php foreach ($corequisitos as $aseguradora => $lista) : ?>
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
                </div>
            </div>
        <?php endif ?>
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