<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reportes</h1>
</div>

<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <?php if (session()->getFlashdata('alerta')) : ?>
                <div class="alert alert-info" role="alert">
                    <?= session()->getFlashdata('alerta') ?>
                </div>
            <?php endif ?>

            <div class="col-md-11 col-lg-12">
                <form class="needs-validation" novalidate method="POST" action="<?= site_url("emisiones/reporte") ?>">
                    <div class="row g-3">
                        <h4 class="mb-3">Formulario</h4>

                        <div class="col-sm-4">
                            <label for="tipo" class="form-label">Plan</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="" disabled selected>Selecciona un tipo</option>
                                <option value="auto">Auto</option>
                                <option value="vida">Vida</option>
                                <option value="desempleo">Vida/Desempleo</option>
                                <option value="incendio">Incendio Hipotecario</option>
                            </select>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="desde" class="form-label">Desde</label>
                            <input type="date" class="form-control" id="desde" name="desde" required>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="hasta" class="form-label">Hasta</label>
                            <input type="date" class="form-control" name="hasta" id="hasta" required>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

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