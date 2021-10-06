<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" width="200" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            certificado <br> plan vida
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>No.</b> <?= $emision->getFieldValue('Numeraci_n') ?> <br>
            <b>Póliza No.</b> <?= $plan->getFieldValue("P_liza") ?> <br>
            <b>Desde</b> <?= date("d/m/Y", strtotime($emision->getFieldValue('Fecha_de_inicio'))) ?> <br>
            <b>Hasta</b> <?= date("d/m/Y", strtotime($emision->getFieldValue('Closing_Date'))) ?>
        </p>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<!-- cliente -->
<h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL DEUDOR</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Nombre</th>
                        <td><?= $deudor->getFieldValue("First_Name") . " " . $deudor->getFieldValue("Last_Name") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">RNC/Cédula</th>
                        <td><?= $deudor->getFieldValue("RNC_C_dula") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Email</th>
                        <td><?= $deudor->getFieldValue("Email") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Fecha de Nacimiento</th>
                        <td><?= $deudor->getFieldValue("Fecha_de_nacimiento") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Tel. Residencia</th>
                        <td><?= $deudor->getFieldValue("Phone") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Celular</th>
                        <td><?= $deudor->getFieldValue("Mobile") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Trabajo</th>
                        <td><?= $deudor->getFieldValue("Fax") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Dirección</th>
                        <td><?= $deudor->getFieldValue("Street") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($emision->getFieldValue("Codeudor"))) : ?>
    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL CODEUDOR</h5>
    <div class="card-group border" style="font-size: small;">
        <div class="card border-0">
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <th scope="col">Nombre</th>
                            <td><?= $codeudor->getFieldValue("First_Name") . " " . $codeudor->getFieldValue("Last_Name") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">RNC/Cédula</th>
                            <td><?= $codeudor->getFieldValue("RNC_C_dula") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Email</th>
                            <td><?= $codeudor->getFieldValue("Email") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Fecha de Nacimiento</th>
                            <td><?= $codeudor->getFieldValue("Fecha_de_nacimiento") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <th scope="col">Tel. Residencia</th>
                            <td><?= $codeudor->getFieldValue("Phone") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Tel. Celular</th>
                            <td><?= $codeudor->getFieldValue("Mobile") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Tel. Trabajo</th>
                            <td><?= $codeudor->getFieldValue("Fax") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Dirección</th>
                            <td><?= $codeudor->getFieldValue("Street") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="col-12">
    &nbsp;
</div>

<h5 class="d-flex justify-content-center bg-primary text-white">PRIMA MENSUAL</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <p class="card-title">
                <b>Fecha Deudor</b>

                <?php if (!empty($emision->getFieldValue("Codeudor"))) : ?>
                    <br> <b>Fecha Codeudor</b>
                <?php endif ?>
            </p>

            <p class="card-title">
                <b>Suma Asegurada</b> <br>
                <b>Plazo</b>
            </p>

            <p class="card-title">
                <b>Prima Neta</b> <br>
                <b>ISC</b> <br>
                <b>Prima Total</b>
            </p>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="card-title">
                <?= $deudor->getFieldValue("Fecha_de_nacimiento") ?>

                <?php if (!empty($emision->getFieldValue("Codeudor"))) : ?>
                    <br> <?= $codeudor->getFieldValue("Fecha_de_nacimiento") ?>
                <?php endif ?>
            </p>

            <p class="card-title">
                RD<?= number_format($emision->getFieldValue("Suma_asegurada"), 2) ?> <br>
                <?= $emision->getFieldValue("Plazo") ?> meses
            </p>

            <p class="card-title">
                RD$ <?= number_format($neta, 2) ?> <br>
                RD$ <?= number_format($isc, 2) ?> <br>
                RD$ <?= number_format($total, 2) ?>
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="card-group" style="font-size: small;">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title text-center">REQUISITOS DEL DEUDOR</h6>
            <?php $requisitos = $plan->getFieldValue("Requisitos_deudor"); ?>
            <ul>
                <?php foreach ($requisitos as $posicion => $requisito) : ?>
                    <li><?= $requisito  ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <?php if (!empty($emision->getFieldValue("Codeudor"))) : ?>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-center">REQUISITOS DEL CODEUDOR</h6>
                <?php $requisitos = $plan->getFieldValue("Requisitos_codeudor"); ?>
                <ul>
                    <?php foreach ($requisitos as $posicion => $requisito) : ?>
                        <li><?= $requisito  ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<!-- Tamaño ideal para la plantilla -->
<style>
    @page {
        size: A3;
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('js') ?>
<!-- Tiempo para que la pagina se imprima y luego se cierre -->
<script>
    document.title = "Emisión No. " + <?= $emision->getFieldValue('Numeraci_n') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>