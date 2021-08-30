<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cotización Seguro Incendio Hipotecario</h1>
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
                <h4 class="mb-3">Formulario</h4>
                <form class="needs-validation" novalidate method="post" action="<?= site_url("cotizaciones/incendio") ?>">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label for="cliente" class="form-label">Cliente <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="cliente" name="cliente" value="<?= (!empty($cotizacion["cliente"])) ? $cotizacion["cliente"] : "" ?>">
                        </div>

                        <div class="col-sm-4">
                            <label for="propiedad" class="form-label">Valor de la Propiedad</label>
                            <input type="number" class="form-control" id="propiedad" required name="propiedad" value="<?= (!empty($cotizacion["propiedad"])) ? $cotizacion["propiedad"] : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label for="prestamo" class="form-label">Valor del Préstamo</label>
                            <input type="number" class="form-control" id="prestamo" required name="prestamo" value="<?= (!empty($cotizacion["prestamo"])) ? $cotizacion["prestamo"] : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label for="plazo" class="form-label">Plazo <span class="text-muted">(en meses)</span></label>
                            <input type="number" class="form-control" id="plazo" required name="plazo" value="<?= (!empty($cotizacion["plazo"])) ? $cotizacion["plazo"] : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label for="construccion" class="form-label">Tipo de Construcción</label>
                            <select class="form-select" id="construccion" name="construccion">
                                <?php if (!empty($cotizacion["construccion"])) : ?>
                                    <option value="<?= $cotizacion["construccion"] ?>"><?= $cotizacion["construccion"] ?></option>
                                <?php endif ?>
                                <option value="Superior">Superior</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="riesgo" class="form-label">Tipo de Riesgo</label>
                            <select class="form-select" id="riesgo" name="riesgo" required>
                                <?php if (!empty($cotizacion["riesgo"])) : ?>
                                    <option value="<?= $cotizacion["riesgo"] ?>"><?= $cotizacion["riesgo"] ?></option>
                                <?php endif ?>
                                <option value="Vivienda">Vivienda</option>
                                <option value="Oficina">Oficina</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required value="<?= (!empty($cotizacion["direccion"])) ? $cotizacion["direccion"] : "" ?>">
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

                <?php if (!empty($cotizacion["planes"])) : ?>
                    <hr class="my-4">

                    <h4 class="mb-3" id="cotizacion">Cotización</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Aseguradora</th>
                                    <th scope="col">Prima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cotizacion["planes"] as $plan) : ?>
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
                        <a href="<?= site_url("cotizaciones/cotizacionincendio/" . json_encode($cotizacion)) ?>" target="_blank" class="btn btn-success btn-lg">Descargar</a>
                        <a href="<?= site_url("emisiones/incendio/" . json_encode($cotizacion)) ?>" class="btn btn-secondary btn-lg">Emitir</a>
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