<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reportes de Pólizas Emitidas</h1>
</div>

<?php if (session()->getFlashdata('alerta')) : ?>
    <div class="alert alert-info" role="alert">
        <?= session()->getFlashdata('alerta') ?>
    </div>
<?php endif ?>

<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <div class="col-md-11 col-lg-12">
                <form class="needs-validation" novalidate method="POST" action="<?= site_url("reportes") ?>">
                    <h4 class="mb-3">Formulario</h4>
                    <div class="mb-3 row">
                        <label for="tipo" class="col-sm-4 col-form-label">Plan</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="" disabled selected>Selecciona un tipo</option>
                                <option value="Auto">Auto</option>
                                <option value="Vida">Vida</option>
                                <option value="Desempleo">Vida/Desempleo</option>
                                <option value="Incendio">Incendio Hipotecario</option>
                            </select>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="desde" class="col-sm-4 col-form-label">Desde</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="desde" name="desde" required>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <!-- hace que el rango de clic del campo de fecha sea mas grande -->
                    <style>
                        #desde::-webkit-calendar-picker-indicator {
                            padding-left: 70%;
                        }
                    </style>

                    <div class="mb-3 row">
                        <label for="hasta" class="col-sm-4 col-form-label">Hasta</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="hasta" id="hasta" required>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <!-- hace que el rango de clic del campo de fecha sea mas grande -->
                    <style>
                        #hasta::-webkit-calendar-picker-indicator {
                            padding-left: 70%;
                        }
                    </style>

                    <hr class="my-4">

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary btn-lg" type="submit">Generar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>