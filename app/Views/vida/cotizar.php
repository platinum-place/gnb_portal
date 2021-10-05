<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Tabla con la cotizacion -->
<?php if (!empty($cotizacion->planes)) : ?>
    <?= $this->include('cotizaciones/tabla') ?>
<?php endif ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= site_url("vida/cotizar") ?>">

                    <div class="mb-3">
                        <label class="form-label">Fecha de Nacimiento Deudor</label>
                        <input type="date" class="form-control" id="deudor" required name="deudor">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de Nacimiento Codeudor (Si aplica)</label>
                        <input type="date" class="form-control" id="codeudor" name="codeudor">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plazo</label>
                        <input type="number" class="form-control" name="plazo" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Suma Asegurada</label>
                        <input type="number" class="form-control" required name="suma">
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-success">Cotizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- CSS personalizado -->
<?= $this->section('css') ?>
<!-- hace que el rango de clic del campo de fecha sea mas grande -->
<style>
    #deudor::-webkit-calendar-picker-indicator {
        padding-left: 60%;
    }

    #codeudor::-webkit-calendar-picker-indicator {
        padding-left: 60%;
    }
</style>
<?= $this->endSection() ?>