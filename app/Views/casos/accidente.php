<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<div class="row">

    <div class="row">
        <div class="col-6">
            <img src="<?= base_url('img/tua.png') ?>" width="170" height="170">
        </div>

        <div class="col-6">
            &nbsp;
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>
    <div class="row">
        <div class="col-6">
            <h3>Reporte de accidente</h3>
        </div>

        <div class="col-6">
            &nbsp;
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>
    <div class="row">
        <div class="col-6">
            <b>Núm. de caso</b> <br>
            <?= $caso->getFieldValue('TUA') ?>
        </div>

        <div class="col-6">
            <b>Fecha</b> <br>
            <?= $caso->getFieldValue('Fecha') ?>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>
    <div class="row">
        <div class="col-6">
            <b>Asegurado</b> <br>
            <?= $caso->getFieldValue('Asegurado') ?>
        </div>

        <div class="col-6">
            <b>Aseguradora</b> <br>
            <?= $caso->getFieldValue('Aseguradora') ?>
        </div>
    </div>


    <div class="col-12">
        &nbsp;
    </div>
    <div class="row">
        <div class="col-6">
            <b>Inicio Vigencia</b> <br>
            <?= $caso->getFieldValue('Desde') ?>
        </div>

        <div class="col-6">
            <b>Fin Vigencia</b> <br>
            <?= $caso->getFieldValue('Hasta') ?>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Plan</b> <br>
            <?= $caso->getFieldValue('Plan') ?>
        </div>

        <div class="col-6">
            <b>Póliza</b> <br>
            <?= $caso->getFieldValue('P_liza') ?>
        </div>
    </div>


    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Marca</b> <br>
            <?= $caso->getFieldValue('Marca') ?>
        </div>

        <div class="col-6">
            <b>Modelo</b> <br>
            <?= $caso->getFieldValue('Modelo') ?>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Año</b> <br>
            <?= $caso->getFieldValue('A_o') ?>
        </div>

        <div class="col-6">
            <b>Placa</b> <br>
            <?= $caso->getFieldValue('Placa') ?>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Chasis</b> <br>
            <?= $caso->getFieldValue('Chasis') ?>
        </div>

        <div class="col-6">
            <b>Color</b> <br>
            <?= $caso->getFieldValue('Color') ?>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Solicitante</b> <br>
            <?= $caso->getFieldValue('Solicitante') ?>
        </div>

        <div class="col-6">
            <b>Teléfono</b> <br>
            <?= $caso->getFieldValue('Phone') ?>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Zona</b> <br>
            <?= $caso->getFieldValue('Zona') ?>
        </div>

        <div class="col-6">
            &nbsp;
        </div>
    </div>


    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Punto A</b> <br>
            <?= $caso->getFieldValue('Punto_A') ?>
        </div>

        <div class="col-6">
            <b>Punto B</b> <br>
            <?= $caso->getFieldValue('Punto_B') ?>
        </div>
    </div>


    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Hora de despacho</b> <br>
            <?= $caso->getFieldValue('Hora_de_despacho') ?>
        </div>

        <div class="col-6">
            <b>Hora contacto</b> <br>
            <?= $caso->getFieldValue('Hora_de_contacto') ?>
        </div>
    </div>


    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-6">
            <b>Hora de cierre</b> <br>
            <?= $caso->getFieldValue('Hora_de_cierre') ?>
        </div>

        <div class="col-6">
            &nbsp;
        </div>
    </div>


    <div class="col-12">
        &nbsp;
    </div>

    <div class="row">
        <div class="col-12">
            <b>Observaciones</b> <br>
            <?= $caso->getFieldValue('Description') ?>
        </div>

    </div>

</div>

<div class="saltopagina"></div>

<?php $cont = 2 ?>


<?php foreach ($api->getAttachments('Cases', $caso->getEntityId(), 1, 200) as $adjunto) : ?>
    <?php
    $ruta =  ROOTPATH . 'public/tmp/';
    $imagen = $api->downloadAttachment('Cases', $caso->getEntityId(), $adjunto->getId(), $ruta);
    $nombre = uniqid() . '.png';
    rename($imagen,  $ruta . $nombre);
    ?>

    <img src="<?= base_url("tmp/$nombre") ?>" style="width: 65%; height: 45%;">

    <div class="col-12">
        &nbsp;
    </div>

    <?php $cont++ ?>

    <?php if ($cont % 2 == 0) : ?>
        <div class="saltopagina"></div>
    <?php endif ?>

<?php endforeach ?>

<?= $this->endSection() ?>


<?= $this->section('css') ?>
<!-- Tamaño ideal para la plantilla -->
<style>
    @page {
        size: A3;
    }

    @media all {
        div.saltopagina {
            display: none;
        }
    }

    @media print {
        div.saltopagina {
            display: block;
            page-break-before: always;
        }
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('js') ?>
<!-- Tiempo para que la pagina se imprima y luego se cierre -->
<script>
    document.title = "<?= $caso->getFieldValue('TUA') ?>"; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>