<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url("assets/dist/css/bootstrap.min.css") ?>" rel="stylesheet">
    
    <title>resumen</title>

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
                <img src="<?= base_url("img/aseguradoras/" . $detalles->getFieldValue("Aseguradora")->getEntityId() . ".png") ?>" width="150" height="50">
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body">
                <h5 class="card-title text-center">RESUMEN <br> SEGURO INCENDIO HIPOTECARIO</h5>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body">
                <p class="card-text" style="text-align: right">
                    <b>Código:</b> <?= $detalles->getFieldValue("TUA") ?> <br>
                    <b>Desde:</b> <?= date("d/m/Y", strtotime($detalles->getCreatedTime())) ?> <br>
                    <b>Hasta:</b> <?= date("d/m/Y", strtotime($detalles->getFieldValue("Closing_Date"))) ?> <br>
                </p>
            </div>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="card-title d-flex justify-content-center bg-primary text-white">DATOS DEL CLIENTE</h5>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td style="width: 30%"><b>Cliente</b></td>
                            <td> <?= $detalles->getFieldValue("Nombre") . " " . $detalles->getFieldValue("Apellido") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Cédula/RNC</b></td>
                            <td><?= $detalles->getFieldValue("Identificaci_n") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Email</b></td>
                            <td><?= $detalles->getFieldValue("Correo_electr_nico") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Fecha de Nacimiento</b></td>
                            <td><?= $detalles->getFieldValue("Fecha_de_nacimiento") ?></td>
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
                            <td style="width: 30%"><b>Tel. Residencia</b></td>
                            <td> <?= $detalles->getFieldValue("Tel_Residencia") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Tel. Celular</b></td>
                            <td><?= $detalles->getFieldValue("Tel_Celular") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Tel. Trabajo</b></td>
                            <td><?= $detalles->getFieldValue("Tel_Trabajo") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="card-title d-flex justify-content-center bg-primary text-white">COBERTURAS</h5>
    <table class="table table-borderless border">
        <tbody>
            <tr>
                <td style="width: 50%"><b>Valor de la propiedad</b></td>
                <td>RD$<?= number_format($detalles->getFieldValue("Suma_asegurada"), 2) ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>Valor del Préstamo</b></td>
                <td>RD$<?= number_format($detalles->getFieldValue("Prestamo"), 2) ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>Plazo en meses</b></td>
                <td><?= $detalles->getFieldValue("Plazo") ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>Dirección</b></td>
                <td><?= $detalles->getFieldValue("Direcci_n") ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>Tipo de Construcción</b></td>
                <td><?= $detalles->getFieldValue("Tipo_de_Construcci_n") ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>Tipo de Riesgo</b></td>
                <td><?= $detalles->getFieldValue("Tipo_de_Riesgo") ?></td>
            </tr>
            <tr>
                <td class="border-dark border-top" style="width: 50%"><b>Prima Neta</b></td>
                <td class="border-dark border-top">RD$<?= number_format($detalles->getFieldValue("Amount") - $detalles->getFieldValue("Amount") * 0.16, 2) ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>ISC</b></td>
                <td>RD$<?= number_format($detalles->getFieldValue("Amount") * 0.16, 2) ?></td>
            </tr>
            <tr>
                <td style="width: 50%"><b>Prima Mensual</b></td>
                <td>RD$<?= number_format($detalles->getFieldValue("Amount"), 2) ?></td>
            </tr>
        </tbody>
    </table>

    <script>
        setTimeout(function() {
            window.print();
            window.close();
        }, 2000);
    </script>
</body>

</html>