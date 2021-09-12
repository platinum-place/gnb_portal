<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card-group">
    <div class="card">
        <img src="<?= base_url('img/auto.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Plan Auto</h5>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cotizar_auto">Cotizar</button>
        </div>
    </div>
    <div class="card">
        <img src="<?= base_url('img/vida.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Plan Vida</h5>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cotizar_vida">Cotizar</button>
        </div>
    </div>
    <div class="card">
        <img src="<?= base_url('img/desempleo.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Plan Vida/Desempleo</h5>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cotizar_desempleo">Cotizar</button>
        </div>
    </div>
    <div class="card">
        <img src="<?= base_url('img/incendio.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Seguro Incendio Hipotecario</h5>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cotizar_incendio">Cotizar</button>
        </div>
    </div>
</div>

<?= $this->section('modal') ?>

<!-- Modal para auto -->
<?= $this->include('modals/cotizar_auto') ?>


<?= $this->endSection() ?>

<?= $this->endSection() ?>