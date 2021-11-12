<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getLookupLabel() . ".png") ?>" width="200" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            <?php if ($cotizacion->getFieldValue("Plan") == "Anual Full" or $cotizacion->getFieldValue("Plan") == "Mensual Full") : ?>
                resumen
            <?php else : ?>
                certificado
            <?php endif ?>



            <?php if ($cotizacion->getFieldValue("Plan") == "Anual Full" or $cotizacion->getFieldValue("Plan") == "Mensual Full") : ?>
                seguro vehí­culo de motor <br>
            <?php endif ?>



            plan <?= $cotizacion->getFieldValue('Plan') ?>
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>Póliza No.</b> <?= $plan->getFieldValue("P_liza") ?> <br>
            <b>Fecha Inicio</b> <?= date('d/m/Y', strtotime($cotizacion->getFieldValue("Vigencia_desde"))) ?> <br>
            <b>Fecha Fin</b> <?= date('d/m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) ?> <br>
        </p>
    </div>
</div>



<div class="col-12">
    &nbsp;
</div>



<!-- cliente -->
<h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL CLIENTE</h5>
<?= $this->include('layouts/datos_cliente') ?>



<?php if ($cotizacion->getFieldValue("Plan") == "Anual Full" or $cotizacion->getFieldValue("Plan") == "Mensual Full") : ?>
    <div class="col-12">
        &nbsp;
    </div>

    <h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL VEHÍCULO</h5>
    <?= $this->include('auto/datos_vehiculo') ?>
<?php endif ?>



<div class="col-12">
    &nbsp;
</div>



<?php if ($cotizacion->getFieldValue("Plan") == "Anual Full" or $cotizacion->getFieldValue("Plan") == "Mensual Full") : ?>
    <?= $this->include('auto/detalles_emision') ?>

<?php elseif ($cotizacion->getFieldValue("Plan") == "Vida/Desempleo") : ?>
    <?= $this->include('desempleo/detalles_emision') ?>

<?php elseif ($cotizacion->getFieldValue("Plan") == "Vida") : ?>
    <?= $this->include('vida/detalles_emision') ?>

<?php elseif ($cotizacion->getFieldValue("Plan") == "Seguro Incendio Hipotecario") : ?>
    <?= $this->include('incendio/detalles_emision') ?>

<?php endif ?>

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
    document.title = "Emisión No. " + <?= $cotizacion->getFieldValue('Quote_Number') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>