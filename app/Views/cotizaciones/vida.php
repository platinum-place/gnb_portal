<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

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
                <b>Fecha</b> <?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?> <br>
                <b>No.</b> <?= $cotizacion->getFieldValue('Quote_Number') ?>
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<h5 class="card-title d-flex justify-content-center bg-primary text-white">DATOS DEL DEUDOR</h5>
<div class="card-group border">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th style="width: 30%">Nombre</th>
                        <td> <?= $cotizacion->getFieldValue("Nombre") . " " . $cotizacion->getFieldValue("Apellido") ?></td>
                    </tr>
                    <tr>
                        <th style="width: 30%">RNC/Cédula</th>
                        <td><?= $cotizacion->getFieldValue("RNC_C_dula") ?></td>
                    </tr>
                    <tr>
                        <th style="width: 30%">Email</th>
                        <td><?= $cotizacion->getFieldValue("Correo_electr_nico") ?></td>
                    </tr>
                    <tr>
                        <th style="width: 30%">Fecha de Nacimiento</th>
                        <td><?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th style="width: 30%">Tel. Residencia</th>
                        <td> <?= $cotizacion->getFieldValue("Tel_Residencia") ?></td>
                    </tr>
                    <tr>
                        <th style="width: 30%">Tel. Celular</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Celular") ?></td>
                    </tr>
                    <tr>
                        <th style="width: 30%">Tel. Trabajo</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Trabajo") ?></td>
                    </tr>
                    <tr>
                        <th style="width: 30%">Dirección</th>
                        <td><?= $cotizacion->getFieldValue("Direcci_n") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) : ?>
    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="card-title d-flex justify-content-center bg-primary text-white">DATOS DEL CODEUDOR</h5>
    <div class="card-group border">
        <div class="card border-0">
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <th style="width: 30%">Nombre</th>
                            <td> <?= $cotizacion->getFieldValue("Nombre_codeudor") . " " . $cotizacion->getFieldValue("Apellido_codeudor") ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%">RNC/Cédula</th>
                            <td><?= $cotizacion->getFieldValue("RNC_C_dula_codeudor") ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%">Email</th>
                            <td><?= $cotizacion->getFieldValue("Correo_electr_nico_codeudor") ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%">Fecha de Nacimiento</th>
                            <td><?= $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <th style="width: 30%">Tel. Residencia</th>
                            <td> <?= $cotizacion->getFieldValue("Tel_Residencia_codeudor") ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%">Tel. Celular</th>
                            <td><?= $cotizacion->getFieldValue("Tel_Celular_codeudor") ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%">Tel. Trabajo</th>
                            <td><?= $cotizacion->getFieldValue("Tel_Trabajo_codeudor") ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%">Dirección</th>
                            <td><?= $cotizacion->getFieldValue("Direcci_n_codeudor") ?></td>
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

<h5 class="card-title d-flex justify-content-center bg-primary text-white">PRIMA MENSUAL</h5>
<div class="card-group border">
    <div class="card border-0">
        <div class="card-body">
            <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">

            <p>
                <b>Fecha Deudor</b> <br>
                <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
                    <b>Fecha Codeudor</b> <br>
                <?php endif ?>
                <b>Suma Asegurada</b> <br>
                <b>Plazo</b> <br>
                <hr>
                <b>Prima Neta</b> <br>
                <b>ISC</b> <br>
                <b>Prima Total</b>
            </p>
        </div>
    </div>

    <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
        <?php if ($lineItem->getNetTotal() > 0) : ?>
            <?php $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId()); ?>
            <div class="card border-0">
                <div class="card-body">
                    <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" height="50" width="150">

                    <p>
                        <?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?> <br>
                        <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
                            <?= $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor") ?> <br>
                        <?php endif ?>
                        RD<?= number_format($cotizacion->getFieldValue("Suma_asegurada"), 2) ?> <br>
                        <?= $cotizacion->getFieldValue("Plazo") ?> meses
                        <hr>
                        RD<?= number_format($lineItem->getListPrice(), 2) ?> <br>
                        RD<?= number_format($lineItem->getTaxAmount(), 2) ?> <br>
                        RD<?= number_format($lineItem->getNetTotal(), 2) ?>
                    </p>
                </div>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="card-group">
    <div class="card small">
        <div class="card-body">
            <h6 class="card-title text-center">REQUISITOS DEL DEUDOR</h6>
            <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
                <?php if ($lineItem->getNetTotal() > 0) : ?>
                    <?php
                    $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId());
                    $requisitos = $plan->getFieldValue("Requisitos_deudor");
                    ?>
                    <ul>
                        <li>
                            <b><?= $plan->getFieldValue("Vendor_Name")->getLookupLabel() ?></b>:
                            <?php foreach ($requisitos as $posicion => $requisito) : ?>
                                <?= $requisito  ?>

                                <?php if ($requisito === end($requisitos)) : ?>
                                    .
                                <?php else : ?>
                                    ,
                                <?php endif ?>
                            <?php endforeach ?>
                        </li>
                    </ul>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>

    <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
        <div class="card small">
            <div class="card-body">
                <h6 class="card-title text-center">REQUISITOS DEL CODEUDOR</h6>
                <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
                    <?php if ($lineItem->getNetTotal() > 0) : ?>
                        <?php
                        $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId());
                        $requisitos = $plan->getFieldValue("Requisitos_codeudor");
                        ?>
                        <ul>
                            <li>
                                <b><?= $plan->getFieldValue("Vendor_Name")->getLookupLabel() ?></b>:
                                <?php foreach ($requisitos as $posicion => $requisito) : ?>
                                    <?= $requisito  ?>

                                    <?php if ($requisito === end($requisitos)) : ?>
                                        .
                                    <?php else : ?>
                                        ,
                                    <?php endif ?>
                                <?php endforeach ?>
                            </li>
                        </ul>
                    <?php endif ?>
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