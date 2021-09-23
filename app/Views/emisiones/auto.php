<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" width="200" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            resumen <br>
            seguro vehí­culo de motor <br>
            plan <?= $detalles->getFieldValue('Plan') ?>
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>No.</b> <?= $detalles->getFieldValue('SO_Number') ?> <br>
            <b>Póliza No.</b> <?= $plan->getFieldValue("P_liza") ?> <br>
            <b>Desde</b> <?= date("d/m/Y", strtotime($detalles->getCreatedTime())) ?> <br>
            <b>Hasta</b> <?= date("d/m/Y", strtotime($detalles->getFieldValue('Due_Date'))) ?>
        </p>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<!-- cliente -->
<h5 class="d-flex justify-content-center bg-primary text-white">CLIENTE</h5>
<?= $this->include('otros/datos_cliente') ?>

<div class="col-12">
    &nbsp;
</div>

<!-- vehiculo -->
<h5 class="d-flex justify-content-center bg-primary text-white">VEHÍCULO</h5>
<?= $this->include('otros/datos_vehiculo') ?>

<div class="col-12">
    &nbsp;
</div>

<h5 class="d-flex justify-content-center bg-primary text-white">COBERTURAS</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
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
    
    <div class="card border-0">
        <div class="card-body">
            <?php
            $riesgo_compresivo = $detalles->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Riesgos_comprensivos') / 100);
            $colision = $detalles->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Colisi_n_y_vuelco') / 100);
            $incendio = $detalles->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Incendio_y_robo') / 100);
            ?>
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
                <?php
                echo "RD$" . $neta . "<br>";
                echo "RD$" . $isc . "<br>";
                echo "RD$" . $total;
                ?>
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="row" style="font-size: small;">
    <div class="col-6 border">
        <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getEntityId() . ".png") ?>" width="150" height="50">

        <div class="card-group">
            <div class="card border-0">
                <div class="card-body">
                    <p>
                        <b>Póliza</b><br>
                        <b>Marca</b> <br>
                        <b>Modelo</b> <br>
                        <b>Chasis</b> <br>
                        <b>Placa</b> <br>
                        <b>Año</b> <br>
                        <b>Desde</b> <br>
                        <b>Hasta</b>
                    </p>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body">
                    <p>
                        <?= $plan->getFieldValue('P_liza') ?> <br>
                        <?= $detalles->getFieldValue('Marca')->getLookupLabel() ?> <br>
                        <?= $detalles->getFieldValue('Modelo')->getLookupLabel() ?> <br>
                        <?= $detalles->getFieldValue('Chasis') ?> <br>
                        <?= $detalles->getFieldValue('Placa') ?> <br>
                        <?= $detalles->getFieldValue('A_o') ?><br>
                        <?= date("d/m/Y", strtotime($detalles->getCreatedTime())) ?><br>
                        <?= date("d/m/Y", strtotime($detalles->getFieldValue('Due_Date'))) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 border">
        <h6 class="text-center">EN CASO DE ACCIDENTE</h6>

        <p class="card-text">
            Realiza el levantamiento del acta policial y obténga la siguente cotizacionrmación:
        </p>

        <ul>
            <li>Nombre,dirección y teléfonos del conductor,los lesionados,del propietario y de los testigos.
            </li>
            <li>Número de placa y póliza del vehí­culo involucrado, y nombre de la aseguradora</li>
        </ul>

        <p class="card-text">
            <b>EN CASO DE ROBO:</b> Notifica de inmediato a la Policí­a y a la aseguradora.
        </p>

        <br>
        <h6 class="text-center">RESERVE SU DERECHO</h6>

        <p class="card-text">
            <b>Aseguradora:</b> Tel. <?= $plan->getFieldValue('Tel_aseguradora') ?>
        </p>

        <div class="card-group">
            <?php if ($plan->getFieldValue('En_caso_de_accidente')) : ?>
                <div class="card border-0">
                    <div class="card-body">
                        <p class="card-text">
                            <b><?= $plan->getFieldValue('En_caso_de_accidente') ?></b> <br>
                            Tel. Sto. Dgo <?= $plan->getFieldValue('Tel_santo_domingo') ?> <br>
                            Tel. Santiago <?= $plan->getFieldValue('Tel_santiago') ?>
                        </p>
                    </div>
                </div>
            <?php endif ?>

            <?php if ($plan->getFieldValue('Asistencia_vial') == 1) : ?>
                <div class="card border-0">
                    <div class="card-body">
                        <p class="card-text">
                            <b>Asistencia vial 24 horas</b> <br>
                            Tel. <?= $plan->getFieldValue('Tel_asistencia_vial')  ?>
                        </p>
                    </div>
                </div>
            <?php endif ?>
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
    document.title = "Emisión No. " + <?= $detalles->getFieldValue('SO_Number') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>