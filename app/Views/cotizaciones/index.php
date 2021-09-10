<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="card-group">
    <div class="card">
        <img src="<?= base_url('img/auto.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Plan Auto</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
        </div>
    </div>
    <div class="card">
    <img src="<?= base_url('img/vida.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Plan Vida</h5>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
        </div>
    </div>
    <div class="card">
    <img src="<?= base_url('img/desempleo.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Plan Vida/Desempleo</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
        </div>
    </div>
    <div class="card">
    <img src="<?= base_url('img/incendio.png') ?>" class="card-img-top" height="350">
        <div class="card-body">
            <h5 class="card-title">Seguro Incendio Hipotecario</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>