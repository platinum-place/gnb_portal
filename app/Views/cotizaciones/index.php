<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php if (!empty($cotizacion)) : ?>
    <div class="card mb-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Aseguradoras</th>
                            <th scope="col">Prima</th>
                            <th scope="col">Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cotizacion as $valores) : ?>
                            <tr>
                                <td><?= $valores["aseguradora"] ?></td>
                                <td>RD$<?= number_format($valores["total"], 2) ?></td>
                                <td><?= $valores["comentario"] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <a href="<?=site_url("cotizaciones/descargar")?>" class="btn btn-success">Continuar</a>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="row row-cols-1 row-cols-md-4 g-4">
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/auto.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Plan Auto</h5>
                <!-- Button trigger modal -->
                <a role="button" class="stretched-link" data-bs-toggle="modal" data-bs-target="#cotizar_auto"></a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/vida.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Plan Vida</h5>
                <!-- Button trigger modal -->
                <a role="button" class="stretched-link" data-bs-toggle="modal" data-bs-target="#cotizar_vida"></a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/desempleo.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Plan Vida/Desempleo</h5>
                <!-- Button trigger modal -->
                <a role="button" class="stretched-link" data-bs-toggle="modal" data-bs-target="#cotizar_desempleo"></a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/incendio.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <h5 class="card-title">Seguro Incendio Hipotecario</h5>
                <!-- Button trigger modal -->
                <a role="button" class="stretched-link" data-bs-toggle="modal" data-bs-target="#cotizar_incendio"></a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<!-- Modal para auto -->
<?= $this->include('modals/cotizar_auto') ?>
<!-- Modal para vida -->
<?= $this->include('modals/cotizar_vida') ?>
<!-- Modal para desempleo -->
<?= $this->include('modals/cotizar_desempleo') ?>
<!-- Modal para incendio -->
<?= $this->include('modals/cotizar_incendio') ?>
<?= $this->endSection() ?>