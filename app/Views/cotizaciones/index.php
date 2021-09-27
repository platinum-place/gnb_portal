<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Opciones para cotizar -->
<div class="row row-cols-1 row-cols-md-4 g-4">
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/auto.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Plan Auto</h5>
                <a class="stretched-link" href="<?= site_url("cotizaciones/cotizar_auto") ?>"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/vida.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Plan Vida</h5>
                <!-- Button trigger modal -->
                <a class="stretched-link" href="<?= site_url("cotizaciones/cotizar_vida") ?>"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/desempleo.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Plan Vida/Desempleo</h5>
                <!-- Button trigger modal -->
                <a class="stretched-link" href="<?= site_url("cotizaciones/cotizar_desempleo") ?>"></a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/incendio.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Seguro Incendio Hipotecario</h5>
                <!-- Button trigger modal -->
                <a class="stretched-link" href="<?= site_url("cotizaciones/cotizar_incendio") ?>"></a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>