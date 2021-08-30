<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h4">Cotizaciones</h1>
</div>

<div class="container py-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card h-100">
                <img src="<?= base_url("img/auto.png") ?>" class="rounded mx-auto d-block">
                <div class="card-body text-center">
                    <h5 class="card-title">PLAN AUTO</h5>
                    <a class="btn btn-outline-secondary stretched-link" href="<?= site_url("cotizaciones/auto") ?>">Cotizar</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <img src="<?= base_url("img/vida.png") ?>" class="rounded mx-auto d-block" height="200">
                <div class="card-body text-center">
                    <h5 class="card-title">PLAN VIDA</h5>
                    <a class="btn btn-outline-secondary stretched-link" href="<?= site_url("cotizaciones/vida") ?>">Cotizar</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <img src="<?= base_url("img/desempleo.png") ?>" class="rounded mx-auto d-block" height="200">
                <div class="card-body text-center">
                    <h5 class="card-title">PLAN VIDA/DESEMPLEO</h5>
                    <a class="btn btn-outline-secondary stretched-link" href="<?= site_url("cotizaciones/desempleo") ?>">Cotizar</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100">
                <img src="<?= base_url("img/incendio.png") ?>" class="rounded mx-auto d-block" height="200">
                <div class="card-body text-center">
                    <h5 class="card-title">PLAN INCENDIO HIPOTECARIO</h5>
                    <a class="btn btn-outline-secondary stretched-link" href="<?= site_url("cotizaciones/incendio") ?>">Cotizar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>