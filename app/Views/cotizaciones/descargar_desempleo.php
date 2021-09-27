<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/nobe.png") ?>" width="100" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            cotización <br> plan vida/desempleo
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>Fecha</b> <?= date('d/m/Y', strtotime($detalles->getCreatedTime())) ?> <br>
            <b>No.</b> <?= $detalles->getFieldValue('Quote_Number') ?>
        </p>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<!-- cliente -->
<h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL CLIENTE</h5>
<?= $this->include('layouts/datos_cliente') ?>

<div class="col-12">
    &nbsp;
</div>

<h5 class="d-flex justify-content-center bg-primary text-white">PRIMA MENSUAL</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">

            <p class="card-title">
                <b>Fecha Cliente</b>
            </p>

            <p class="card-title">
                <b>Suma Asegurada</b> <br>
                <b>Cuota Mensual de Prestamo</b> <br>
                <b>Plazo</b>
            </p>

            <p class="card-title">
                <b>Prima Neta</b> <br>
                <b>ISC</b> <br>
                <b>Prima Total</b>
            </p>
        </div>
    </div>

    <?php foreach ($detalles->getLineItems() as $lineItem) : ?>
        <?php if ($lineItem->getNetTotal() > 0) : ?>
            <?php $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId()); ?>
            <div class="card border-0">
                <div class="card-body">
                    <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" height="50" width="150">

                    <p class="card-title">
                        <?= $detalles->getFieldValue("Fecha_de_nacimiento") ?>
                    </p>

                    <p class="card-title">
                        RD<?= number_format($detalles->getFieldValue("Suma_asegurada"), 2) ?> <br>
                        RD<?= number_format($detalles->getFieldValue("Cuota"), 2) ?> <br>
                        <?= $detalles->getFieldValue("Plazo") ?> meses
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

            <?php foreach ($detalles->getLineItems() as $lineItem) : ?>
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

    <div class="card border-0">
        <div class="card-body">
            <h6 class="card-title text-center">OBSERVACIONES</h6>
            <ul>
                <li>Pago de desempleo por hasta 6 meses.</li>
            </ul>
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