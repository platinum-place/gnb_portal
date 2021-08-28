<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cambiar contraseña</h1>
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
                <form class="needs-validation" novalidate method="POST" action="<?= site_url("login/editar") ?>">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="actual" class="form-label">Contraseña actual</label>
                            <input type="password" class="form-control" id="actual" placeholder="" value="" required name="actual">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="nueva" class="form-label">Contraseña nueva</label>
                            <input type="password" class="form-control" id="nueva" placeholder="" value="" required name="nueva">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>