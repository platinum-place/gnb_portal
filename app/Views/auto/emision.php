<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<!-- encabezado -->
<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getLookupLabel() . ".png") ?>" width="200" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">
            resumen <br>
            seguro vehí­culo de motor <br>
            plan <?= $cotizacion->getFieldValue('Plan') ?>
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>Póliza No.</b> <?= $plan->getFieldValue("P_liza") ?> <br>
            <b>Fecha Inicio</b> <?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?> <br>
            <b>Fecha Fin</b> <?= date('d/m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) ?> <br>
        </p>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<!-- cliente -->
<h5 class="d-flex justify-content-center bg-primary text-white">CLIENTE</h5>
<?= $this->include('layouts/datos_cliente') ?>

<div class="col-12">
    &nbsp;
</div>

<!-- vehiculo -->
<h5 class="d-flex justify-content-center bg-primary text-white">VEHÍCULO</h5>
<?= $this->include('layouts/datos_vehiculo') ?>

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
            $riesgo_compresivo = $cotizacion->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Riesgos_comprensivos') / 100);
            $colision = $cotizacion->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Colisi_n_y_vuelco') / 100);
            $incendio = $cotizacion->getFieldValue('Suma_asegurada') * ($plan->getFieldValue('Incendio_y_robo') / 100);
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

            <?php
            $neta = $cotizacion->getFieldValue('Prima') - ($cotizacion->getFieldValue('Prima') * 0.16);
            $isc = $cotizacion->getFieldValue('Prima') * 0.16;
            $total = $cotizacion->getFieldValue('Prima');
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

<div class="col-12">
    &nbsp;
</div>

<div class="row" style="font-size: small;">
    <div class="col-6 border">
        <img src="<?= base_url("img/aseguradoras/" . $plan->getFieldValue("Vendor_Name")->getLookupLabel() . ".png") ?>" width="150" height="50">

        <table class="table table-sm table-borderless">
            <tbody>
                <tr>
                    <th scope="col">Póliza</th>
                    <td><?= $plan->getFieldValue('P_liza') ?></td>
                </tr>

                <tr>
                    <th scope="col">Marca</th>
                    <td><?= $cotizacion->getFieldValue('Marca')->getLookupLabel() ?></td>
                </tr>

                <tr>
                    <th scope="col">Modelo</th>
                    <td><?= $cotizacion->getFieldValue('Modelo')->getLookupLabel() ?></td>
                </tr>

                <tr>
                    <th scope="col">Chasis</th>
                    <td><?= $cotizacion->getFieldValue("Chasis") ?></td>
                </tr>

                <tr>
                    <th scope="col">Placa</th>
                    <td><?= $cotizacion->getFieldValue("Placa") ?></td>
                </tr>


                <tr>
                    <th scope="col">Año</th>
                    <td><?= $cotizacion->getFieldValue("A_o") ?></td>
                </tr>

                <tr>
                    <th scope="col">Desde</th>
                    <td><?= date("d/m/Y", strtotime($cotizacion->getCreatedTime()))  ?></td>
                </tr>


                <tr>
                    <th scope="col">Hasta</th>
                    <td><?= date("d/m/Y", strtotime($cotizacion->getFieldValue('Valid_Till'))) ?></td>
                </tr>
            </tbody>
        </table>
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
    document.title = "Emisión No. " + <?= $cotizacion->getFieldValue('Quote_Number') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>