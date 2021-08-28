<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h4">Cotizaciones</h1>
</div>

<div class="container py-4">
    <div class="row align-items-md-stretch text-center">
        <div class="col-md">
            <div class="h-100 p-5 bg-light border rounded-3">
                <img src="<?= base_url("img/auto.png") ?>" height="150">
                <a class="btn btn-outline-secondary" href="<?= site_url("cotizaciones/auto") ?>">Cotizar</a>
            </div>
        </div>

        <div class="col-md">
            <div class="h-100 p-5 bg-light border rounded-3">
                <img src="<?= base_url("img/vida.png") ?>" height="150">
                <a class="btn btn-outline-secondary" href="<?= site_url("cotizaciones/vida") ?>">Cotizar</a>
            </div>
        </div>

        <div class="col-md">
            <div class="h-100 p-5 bg-light border rounded-3">
                <img src="<?= base_url("img/desempleo.png") ?>" height="150">
                <a class="btn btn-outline-secondary" href="<?= site_url("cotizaciones/desempleo") ?>">Cotizar</a>
            </div>
        </div>

        <div class="col-md">
            <div class="h-100 p-5 bg-light border rounded-3">
                <img src="<?= base_url("img/incendio.png") ?>" height="150">
                <a class="btn btn-outline-secondary" href="<?= site_url("cotizaciones/incendio") ?>">Cotizar</a>
            </div>
        </div>
    </div>

    <hr>

    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Custom jumbotron</h1>
            <p class="col-md-8 fs-4">Using a series of utilities, you can create this jumbotron, just like the one in previous versions of Bootstrap. Check out the examples below for how you can remix and restyle it to your liking.</p>
            <button class="btn btn-primary btn-lg" type="button">Example button</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>