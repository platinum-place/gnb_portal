<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Documentos</h1>
</div>

<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <div class="col-md-11 col-lg-12">
                <h4 class="mb-3">Adjuntar documentos</h4>
                <form enctype="multipart/form-data" class="needs-validation" novalidate method="post" action="<?= site_url("documentos/subir/" . $id) ?>">
                    <div class="mb-3 row">
                        <label for="documentos" class="col-sm-4 col-form-label">Documentos <span class="text-muted">(pueden ser varios)</span></label>
                        <div class="col-sm-8">
                            <input required type="file" name="documentos[]" multiple class="form-control" id="documentos">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary btn-lg" type="submit">Adjuntar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>