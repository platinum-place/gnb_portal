<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cotización de Plan Vida/Desempleo</h1>
</div>

<div class="container py-4">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <div class="col-md-11 col-lg-12">
                <form class="needs-validation" novalidate method="post" action="<?= site_url("cotizaciones/desempleo") ?>">
                    <h4 class="mb-3">Formulario</h4>
                    <div class="mb-3 row">
                        <label for="fecha" class="col-sm-4 col-form-label">Fecha de Nacimiento</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="fecha" required name="fecha" value="<?= (!empty($cotizacion->fecha)) ? $cotizacion->fecha : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <!-- hace que el rango de clic del campo de fecha sea mas grande -->
                    <style>
                        #fecha::-webkit-calendar-picker-indicator {
                            padding-left: 70%;
                        }
                    </style>

                    <div class="mb-3 row">
                        <label for="suma" class="col-sm-4 col-form-label">Suma Asegurada</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="suma" required name="suma" value="<?= (!empty($cotizacion->suma)) ? $cotizacion->suma : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="cuota" class="col-sm-4 col-form-label">Cuota Mensual</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="cuota" required name="cuota" value="<?= (!empty($cotizacion->cuota)) ? $cotizacion->cuota : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="plazo" class="col-sm-4 col-form-label">Plazo</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="plazo" required name="plazo" value="<?= (!empty($cotizacion->plazo)) ? $cotizacion->plazo : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-primary btn-lg" type="submit">Cotizar</button>
                    </div>
                </form>

                <?php if (session()->getFlashdata('alerta')) : ?>
                    <hr class="my-4">

                    <div class="alert alert-info" role="alert">
                        <?= session()->getFlashdata('alerta') ?>
                    </div>
                <?php endif ?>

                <?php if (!empty($cotizacion->planes)) : ?>
                    <hr class="my-4">

                    <h4 class="mb-3" id="cotizacion">Cotización</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Aseguradora</th>
                                    <th scope="col">Prima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cotizacion->planes as $plan) : ?>
                                    <tr>
                                        <td><?= $plan["nombre"] ?></td>
                                        <td>RD$<?= number_format($plan["total"], 2) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <a href="<?= site_url("plantillas/cotizacion/" . $cotizacion->plantilla() . "/desempleo") ?>" target="_blank" class="btn btn-success btn-lg">Descargar</a>
                        <a href="<?= site_url("emisiones/desempleo/" .  $cotizacion->plantilla()) ?>" class="btn btn-secondary btn-lg">Emitir</a>
                    </div>

                    <!-- Hacer scroll automatico cuando aparece la tabla de cotizaciones -->
                    <script>
                        var my_element = document.getElementById("cotizacion");

                        my_element.scrollIntoView({
                            behavior: "smooth",
                            block: "start",
                            inline: "nearest"
                        });
                    </script>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>