<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/adjuntar/" . $cotizacion->getEntityId()) ?>">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    Documentos
                </div>

                <div class="card-body">
                    <div class="mb-3 mb-md-0">
                        <label class="form-label">Adjuntar documentos</label>
                        <input required type="file" name="documentos[]" multiple class="form-control">
                    </div>
                </div>
            </div>

            <div class="mt-4 mb-0">
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-block">Adjuntar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>