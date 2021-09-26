<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Tabla con la cotizacion -->
<?php if (!empty($cotizacion->planes)) : ?>
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
                        <!-- contador para saber si existen valores vacios, para no permitir continuar de ser el caso -->
                        <?php $cont = 0 ?>

                        <?php foreach ($cotizacion->planes as $plan) : ?>
                            <tr>
                                <td><?= $plan["aseguradora"] ?></td>
                                <td>RD$<?= number_format($plan["total"], 2) ?></td>
                                <td><?= $plan["comentario"] ?></td>
                            </tr>

                            <?php
                            if ($plan["total"] > 0) {
                                $cont++;
                            }
                            ?>
                        <?php endforeach ?>
                    </tbody>
                </table>

                <?php if ($cont > 0) : ?>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completar_<?= $cotizacion->tipo ?>">
                        Continuar
                    </button>
                <?php endif ?>
            </div>
        </div>
    </div>
<?php endif ?>

<!-- Opciones para cotizar -->
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

<!-- Formularios a utilizar -->
<?= $this->section('modal') ?>

<!-- Modal para completar la cotizacion -->
<?php if (!empty($cotizacion)) : ?>
    <?php if ($cotizacion->tipo == "auto") : ?>
        <?= $this->include('modals/completar_auto') ?>

    <?php elseif ($cotizacion->tipo == "vida") : ?>
        <?= $this->include('modals/completar_vida') ?>



    <?php endif ?>
<?php endif ?>
<!-- Modal para auto -->
<?= $this->include('modals/cotizar_auto') ?>
<!-- Modal para vida -->
<?= $this->include('modals/cotizar_vida') ?>
<!-- Modal para desempleo -->
<?= $this->include('modals/cotizar_desempleo') ?>
<!-- Modal para incendio -->
<?= $this->include('modals/cotizar_incendio') ?>
<?= $this->endSection() ?>