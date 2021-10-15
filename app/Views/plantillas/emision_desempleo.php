<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
    <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getLookupLabel() . ".png") ?>" width="200" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            certificado <br> plan vida/desempleo
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>Póliza No.</b> <?= $plan->getFieldValue("P_liza") ?> <br>
            <b>Fecha Inicio</b> <?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?> <br>
            <b>Fecha Fin</b> <?= date('d/m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) ?> <br>
            <b>No.</b> <?= $cotizacion->getFieldValue('Quote_Number') ?>
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

    <div class="card border-0">
        <div class="card-body">
            <p class="card-title">
                <?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>
            </p>

            <p class="card-title">
                RD<?= number_format($cotizacion->getFieldValue("Suma_asegurada"), 2) ?> <br>
                RD<?= number_format($cotizacion->getFieldValue("Cuota"), 2) ?> <br>
                <?= $cotizacion->getFieldValue("Plazo") ?> meses
            </p>

            <?php
            $neta = $plan->getFieldValue('Prima') - ($plan->getFieldValue('Prima') * 0.16);
            $isc = $plan->getFieldValue('Prima') * 0.16;
            $total = $plan->getFieldValue('Prima') - ($plan->getFieldValue('Prima') * 0.16);
            ?>

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
    document.title = "Emisión No. " + <?= $cotizacion->getFieldValue('Quote_Number') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>