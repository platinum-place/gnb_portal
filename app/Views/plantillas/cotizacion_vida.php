<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/nobe.png") ?>" width="100" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            cotización <br> plan vida
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>Fecha Inicio</b> <?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?> <br>
            <b>Fecha Fin</b> <?= date('d/m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) ?> <br>
        </p>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<!-- cliente -->
<h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL DEUDOR</h5>
<?= $this->include('layouts/datos_cliente') ?>

<?php if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) : ?>
    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL CODEUDOR</h5>
    <?= $this->include('layouts/datos_codeudor') ?>

<?php endif ?>

<div class="col-12">
    &nbsp;
</div>

<h5 class="d-flex justify-content-center bg-primary text-white">PRIMA MENSUAL</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">

            <p class="card-title">
                <b>Fecha Deudor</b>

                <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
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

    <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
        <?php if ($lineItem->getNetTotal() > 0) : ?>
            <?php $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId()); ?>
            <div class="card border-0">
                <div class="card-body">
                    <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getLookupLabel() . ".png") ?>" height="50" width="150">

                    <p class="card-title">
                        <?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>

                        <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) {
                            echo "<br>" . $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor");
                        } ?>
                    </p>

                    <p class="card-title">
                        RD<?= number_format($cotizacion->getFieldValue("Suma_asegurada"), 2) ?> <br>
                        <?= $cotizacion->getFieldValue("Plazo") ?> meses
                    </p>

                    <p class="card-title">
                        RD$ <?= number_format($lineItem->getNetTotal() - $lineItem->getNetTotal() * 0.16, 2) ?> <br>
                        RD$ <?= number_format($lineItem->getNetTotal() * 0.16, 2) ?> <br>
                        RD$ <?= number_format($lineItem->getNetTotal(), 2) ?>
                    </p>
                </div>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
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
        <div class="card border-0">
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
    document.title = "Cotización No. " + <?= $cotizacion->getFieldValue('Quote_Number') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>