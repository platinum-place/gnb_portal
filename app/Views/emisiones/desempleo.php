<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h4">Emisión Póliza Plan Vida/Desempleo</h1>
</div>

<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <div class="col-md-11 col-lg-12">
                <form enctype="multipart/form-data" class="needs-validation" novalidate method="post" action="<?= site_url("emisiones/desempleo/" . json_encode($cotizacion)) ?>">

                    <h4 class="mb-3">Cliente</h4>

                    <div class="mb-3 row">
                        <label for="nombre" class="col-sm-4 col-form-label">Nombre</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= (!empty($detalles["cliente"])) ? $cotizacion["cliente"] : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="apellido" class="col-sm-4 col-form-label">Apellido</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="apellido" name="apellido">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id" class="col-sm-4 col-form-label">Cédula/RNC</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="id" name="id">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="correo" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="correo" required name="correo">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tel1" class="col-sm-4 col-form-label">Tel. Residencia</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="tel1" required name="tel1">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tel2" class="col-sm-4 col-form-label">Tel. Celular</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="tel2" name="tel2">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tel3" class="col-sm-4 col-form-label">Tel. Trabajo</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="tel3" name="tel3">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Cotización</h4>

                    <div class="mb-3 row">
                        <label for="cotizacion" class="col-sm-4 col-form-label">Cotización firmada</label>
                        <div class="col-sm-8">
                            <input required type="file" name="cotizacion" class="form-control" id="cotizacion">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Aseguradora</h4>

                    <div class="mb-3 row">
                        <label for="aseguradora" class="col-sm-4 col-form-label">Aseguradora</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="aseguradora" name="aseguradora" required>
                                <option value="" selected disabled>Selecciona una aseguradora</option>
                                <?php foreach ($cotizacion["planes"] as $plan) : ?>
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