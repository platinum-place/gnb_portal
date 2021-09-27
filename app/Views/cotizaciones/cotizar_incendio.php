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
            <form method="POST" action="<?= site_url("cotizaciones/cotizar_incendio") ?>">

                    <div class="mb-3">
                        <label class="form-label">Valor de la Propiedad</label>
                        <input type="number" class="form-control" name="suma" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor del Préstamo</label>
                        <input type="number" class="form-control" name="cuota" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plazo</label>
                        <input type="number" class="form-control" name="plazo" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Construcción</label>
                        <select class="form-select" name="construccion">
                            <option value="Superior">Superior</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Riesgo</label>
                        <select class="form-select" name="riesgo">
                            <option value="Vivienda">Vivienda</option>
                        </select>
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