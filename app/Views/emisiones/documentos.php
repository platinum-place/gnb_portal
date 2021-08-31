<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Documentos</h1>
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
                <h4 class="mb-3">Adjuntar documentos</h4>
                <form enctype="multipart/form-data" class="needs-validation" novalidate method="post" action="<?= site_url("emisiones/documentos/" . $id) ?>">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="documentos" class="form-label">Documentos <span class="text-muted">(pueden ser varios)</span></label>
                            <input required type="file" name="documentos[]" multiple class="form-control" id="documentos">
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