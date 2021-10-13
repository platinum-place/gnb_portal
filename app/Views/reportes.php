<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/reportes") ?>">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control" id="desde" name="desde" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Hasta</label>
                                <input type="date" class="form-control" id="hasta" name="hasta" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mb-0">
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-block">Generar Reporte</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>