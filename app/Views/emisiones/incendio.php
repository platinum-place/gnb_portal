<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h4">Emisión Póliza Incendio Hipotecario</h1>
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
                <form enctype="multipart/form-data" class="needs-validation" novalidate method="post" action="<?= site_url("emisiones/incendio/" . json_encode($detalles)) ?>">

                    <div class="row g-3">
                        <h4 class="mb-3">Cliente</h4>

                        <div class="col-sm-6">
                            <label for="nombre" class="form-label">Nombre</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= (!empty($detalles["cliente"])) ? $detalles["cliente"] : "" ?>">
                        </div>

                        <div class="col-sm-6">
                            <label for="apellido" class="form-label">Apellido</span></label>
                            <input type="text" class="form-control" id="apellido" name="apellido">
                        </div>

                        <div class="col-sm-6">
                            <label for="id" class="form-label">Cédula/RNC</label>
                            <input type="text" class="form-control" id="id" required name="id">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="fecha" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha" required name="fecha">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label for="correo" class="form-label">Email</label>
                            <input type="email" class="form-control" id="correo" required name="correo">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label for="tel1" class="form-label">Tel. Residencia</label>
                            <input type="tel" class="form-control" id="tel1" required name="tel1">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label for="tel2" class="form-label">Tel. Celular</label>
                            <input type="tel" class="form-control" id="tel2" name="tel2">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label for="tel3" class="form-label">Tel. Trabajo</label>
                            <input type="tel" class="form-control" id="tel3" name="tel3">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <h4 class="mb-3">Cotización</h4>

                        <div class="col-sm-6">
                            <label for="cotizacion" class="form-label">Cotización firmada</span></label>
                            <input required type="file" name="cotizacion" class="form-control" id="cotizacion">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <h4 class="mb-3">Aseguradora</h4>

                        <div class="col-sm-6">
                            <label for="aseguradora" class="form-label">Aseguradora</span></label>
                            <select class="form-select" id="aseguradora" name="aseguradora" required>
                                <option value="" selected disabled>Selecciona una aseguradora</option>
                                <?php foreach ($detalles["planes"] as $plan) : ?>
                                    <option value="<?= $plan["id"] . "," . $plan["total"] ?>"><?= $plan["nombre"] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>


                    <hr class="my-4">

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary btn-lg" type="submit">Emitir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>