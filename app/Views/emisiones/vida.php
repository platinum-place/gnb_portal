<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<div class="card-group">
    <div class="card border-0">
        <div class="card-body">
            <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" width="250" height="70">
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <h5 class="card-title text-center">CERTIFICADO <br> PLAN VIDA</h5>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="card-text" style="text-align: right">
                <b>No.</b> <?= $emision->getFieldValue('TUA') ?> <br>
                <b>Desde:</b> <?= date("d/m/Y", strtotime($emision->getFieldValue("Fecha_de_inicio"))) ?> <br>
                <b>Hasta:</b> <?= date("d/m/Y", strtotime($emision->getFieldValue("Closing_Date"))) ?> <br>
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<h5 class="card-title d-flex justify-content-center bg-primary text-white">DATOS DEL DEUDOR</h5>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <td style="width: 30%"><b>Nombre</b></td>
                        <td> <?= $deudor->getFieldValue("First_Name") . " " . $deudor->getFieldValue("Last_Name") ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>RNC/Cédula</b></td>
                        <td><?= $deudor->getFieldValue("RNC_C_dula") ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>Email</b></td>
                        <td><?= $deudor->getFieldValue("Email") ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>Fecha de Nacimiento</b></td>
                        <td><?= $deudor->getFieldValue("Date_of_Birth") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <td style="width: 30%"><b>Tel. Residencia</b></td>
                        <td> <?= $emision->getFieldValue("Home_Phone") ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>Tel. Celular</b></td>
                        <td><?= $emision->getFieldValue("Mobile") ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>Tel. Trabajo</b></td>
                        <td><?= $emision->getFieldValue("Other_Phone") ?></td>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>Dirección</b></td>
                        <td><?= $emision->getFieldValue("Mailing_Street") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($codeudor->getFieldValue("First_Name"))) : ?>
    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="card-title d-flex justify-content-center bg-primary text-white">DATOS DEL CODEUDOR</h5>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td style="width: 30%"><b>Nombre</b></td>
                            <td> <?= $codeudor->getFieldValue("First_Name") . " " . $codeudor->getFieldValue("Last_Name") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>RNC/Cédula</b></td>
                            <td><?= $codeudor->getFieldValue("RNC_C_dula") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Email</b></td>
                            <td><?= $codeudor->getFieldValue("Email") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Fecha de Nacimiento</b></td>
                            <td><?= $codeudor->getFieldValue("Date_of_Birth") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td style="width: 30%"><b>Tel. Residencia</b></td>
                            <td> <?= $codeudor->getFieldValue("Home_Phone") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Tel. Celular</b></td>
                            <td><?= $codeudor->getFieldValue("Mobile") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Tel. Trabajo</b></td>
                            <td><?= $codeudor->getFieldValue("Other_Phone") ?></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Dirección</b></td>
                            <td><?= $codeudor->getFieldValue("Mailing_Street") ?></td>
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
<table class="table table-borderless border">
    <tbody>
        <tr>
            <td style="width: 50%"><b>Fecha de Nacimiento Deudor</b></td>
            <td><?= $deudor->getFieldValue("Date_of_Birth") ?></td>
        </tr>
        <?php if (!empty($codeudor->getFieldValue("First_Name"))) : ?>
            <tr>
                <td style="width: 50%"><b>Fecha de Nacimiento Codeudor</b></td>
                <td><?= $codeudor->getFieldValue("Date_of_Birth") ?></td>
            </tr>
        <?php endif ?>
        <tr>
            <td style="width: 50%"><b>Suma Asegurada</b></td>
            <td>RD$<?= number_format($emision->getFieldValue("Suma_asegurada"), 2) ?></td>
        </tr>
        <tr>
            <td style="width: 50%"><b>Plazo</b></td>
            <td><?= $emision->getFieldValue("Plazo") ?> meses</td>
        </tr>
        <tr>
            <td class="border-dark border-top" style="width: 50%"><b>Prima Neta</b></td>
            <td class="border-dark border-top">RD$<?= number_format($emision->getFieldValue("Amount") - $emision->getFieldValue("Amount") * 0.16, 2) ?></td>
        </tr>
        <tr>
            <td style="width: 50%"><b>ISC</b></td>
            <td>RD$<?= number_format($emision->getFieldValue("Amount") * 0.16, 2) ?></td>
        </tr>
        <tr>
            <td style="width: 50%"><b>Prima Mensual</b></td>
            <td>RD$<?= number_format($emision->getFieldValue("Amount"), 2) ?></td>
        </tr>
    </tbody>
</table>

<div class="card-group">
    <div class="card small">
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

    <?php if (!empty($emision->getFieldValue("Nombre_codeudor"))) : ?>
        <div class="card small">
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
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>