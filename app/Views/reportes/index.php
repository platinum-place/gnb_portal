<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= site_url("reportes") ?>">
                    <h6>Datos del reporte</h6>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Plan</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="" disabled selected>Selecciona un tipo</option>
                            <option value="auto">Auto</option>
                            <option value="vida">Vida</option>
                            <option value="desempleo">Vida/Desempleo</option>
                            <option value="incendio">Incendio Hipotecario</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Desde</label>
                        <input type="date" class="form-control" id="desde" name="desde" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="hasta" name="hasta" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-success">Generar reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- hace que el rango de clic del campo de fecha sea mas grande -->
<style>
    #desde::-webkit-calendar-picker-indicator {
        padding-left: 70%;
    }

    #hasta::-webkit-calendar-picker-indicator {
        padding-left: 70%;
    }
</style>
<?= $this->endSection() ?>