<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Opciones para cotizar -->
<div class="row row-cols-1 row-cols-md-4 g-4">
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/auto.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="#" data-bs-toggle="modal" data-bs-target="#cotizar_auto"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/vida.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="<?= site_url("vida/cotizar") ?>"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/desempleo.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="<?= site_url("desempleo/cotizar") ?>"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/incendio.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="<?= site_url("incendio/cotizar") ?>"></a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('modal') ?>
<?php if (!empty($cotizaciones)) : ?>
    <?= $this->include('modals/tabla_resultados') ?>
    <?= $this->include('modals/completar_cotizacion') ?>
<?php endif ?>

<!-- Formulario para cotizar auto -->
<?= $this->include('modals/cotizar_auto') ?>
<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>
    //representan los modals
    var tabla_resultados = new bootstrap.Modal(document.getElementById('tabla_resultados'), {});
    var completar_cotizacion = new bootstrap.Modal(document.getElementById('completar_cotizacion'), {});

    //mostrar los resultados
    tabla_resultados.show();

    //cerrar los resultados y mostrar el formulario
    function cerrar() {
        tabla_resultados.hide();
        completar_cotizacion.show();
    }
</script>
<?= $this->endSection() ?>


<!-- CSS personalizado -->
<?= $this->section('css') ?>
<!-- hace que el rango de clic del campo de fecha sea mas grande -->
<style>
    #fecha::-webkit-calendar-picker-indicator {
        padding-left: 60%;
    }
</style>
<?= $this->endSection() ?>