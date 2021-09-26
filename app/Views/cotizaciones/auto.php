<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/nobe.png") ?>" width="100" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            cotización <br>
            seguro vehí­culo de motor <br>
            plan <?= $detalles->getFieldValue('Plan') ?>
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
<?= $this->include('otros/datos_cliente') ?>

<div class="col-12">
    &nbsp;
</div>

<!-- vehiculo -->
<h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL VEHÍCULO</h5>
<?= $this->include('otros/datos_vehiculo') ?>

<div class="col-12">
    &nbsp;
</div>

<h5 class="d-flex justify-content-center bg-primary text-white">COBERTURAS</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <img src="<?= base_url("img/espacio.png") ?>" height="50" width="150">

            <p class="card-title">
                <b>DAÑOS PROPIOS</b> <br>
                Riesgos Comprensivos <br>
                Riesgos Compr. (Deducible) <br>
                Rotura de Cristales (Deducible) <br>
                Colisión y Vuelco <br>
                Incendio y Robo
            </p>

            <p class="card-title">
                <b>RESPONSABILIDAD CIVIL</b> <br>
                Daños Propiedad Ajena <br>
                Lesiones/Muerte 1 Pers <br>
                Lesiones/Muerte más de 1 Pers <br>
                Lesiones/Muerte 1 Pasajero <br>
                Lesiones/Muerte más de 1 Pas
            </p>

            <p class="card-title">
                <b>RIESGOS CONDUCTOR</b> <br>
                <b>FIANZA JUDICIAL</b>
            </p>

            <p class="card-title">
                <b>COBERTURAS ADICIONALES</b> <br>
                Asistencia Vial <br>
                Renta Vehí­culo <br>
                En Caso de Accidente
            </p>

            <p class="card-title">
                <b>PRIMA NETA</b> <br>
                <b>ISC</b> <br>
                <b>PRIMA TOTAL</b> <br>
            </p>
        </div>
    </div>

    <?php foreach ($detalles->getLineItems() as $lineItem) : ?>
        <?php if ($lineItem->getNetTotal() > 0) : ?>
            <?php
            $plan = $libreria->getRecord("Products", $lineItem->getProduct()->getEntityId());
            $riesgo_compresivo = $detalles->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Riesgos_comprensivos') / 100);
            $colision = $detalles->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Colisi_n_y_vuelco') / 100);
            $incendio = $detalles->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Incendio_y_robo') / 100);
            ?>
            <div class="card border-0">
                <div class="card-body">
                    <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" height="50" width="150">
                    <p class="card-title">
                        <br>
                        RD$<?= number_format($riesgo_compresivo, 2) ?><br>
                        <?= $plan->getFieldValue('Riesgos_comprensivos_deducible')  ?><br>
                        <?= $plan->getFieldValue('Rotura_de_cristales_deducible')  ?><br>
                        RD$ <?= number_format($colision, 2) ?><br>
                        RD$ <?= number_format($incendio, 2) ?>
                    </p>

                    <p class="card-title">
                        <br>
                        RD$ <?= number_format($plan->getFieldValue('Da_os_propiedad_ajena'), 2) ?> <br>
                        RD$ <?= number_format($plan->getFieldValue('Lesiones_muerte_1_pers'), 2) ?> <br>
                        RD$ <?= number_format($plan->getFieldValue('Lesiones_muerte_m_s_1_pers'), 2) ?> <br>
                        RD$ <?= number_format($plan->getFieldValue('Lesiones_muerte_1_pas'), 2) ?> <br>
                        RD$ <?= number_format($plan->getFieldValue('Lesiones_muerte_m_s_1_pas'), 2) ?>
                    </p>

                    <p class="card-title">
                        RD$ <?= number_format($plan->getFieldValue('Riesgos_conductor'), 2) ?> <br>
                        RD$ <?= number_format($plan->getFieldValue('Fianza_judicial'), 2) ?>
                    </p>

                    <p class="card-title">
                        <br>
                        <?php
                        if ($plan->getFieldValue('Asistencia_vial') == 1) {
                            echo 'Aplica <br>';
                        } else {
                            echo 'No aplica <br>';
                        }
                        if ($plan->getFieldValue('Renta_veh_culo') == 1) {
                            echo 'Aplica <br>';
                        } else {
                            echo 'No aplica <br>';
                        }
                        if (!empty($plan->getFieldValue('En_caso_de_accidente'))) {
                            echo $plan->getFieldValue('En_caso_de_accidente');
                        } else {
                            echo 'No aplica';
                        }
                        ?>
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

<div class="col-12">
    &nbsp;
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="row">
    <div class="col-4">
        <p class="text-center">
            _______________________________ <br> Firma Cliente
        </p>
    </div>

    <div class="col-4">
        <p class="text-center">
            _______________________________ <br> Aseguradora Elegida
        </p>
    </div>

    <div class="col-4">
        <p class="text-center">
            _______________________________ <br> Fecha
        </p>
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
    document.title = "Cotización No. " + <?= $detalles->getFieldValue('Quote_Number') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>