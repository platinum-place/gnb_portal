<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h4">Cotización Seguro Incendio Hipotecario</h1>
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
                            <label for="cliente" class="form-label">Cliente <span class="h4asdasd a sdasd a asd as as dasd asd a-muted">(Optional)</span></label>
                            <input type="h4asdasd a sdasd a asd as as dasd asd a" class="form-control" id="cliente" name="cliente" value="<?= (!empty($cotizacion["cliente"])) ? $cotizacion["cliente"] : "" ?>">
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
                            <label for="plazo" class="form-label">Plazo <span class="h4asdasd a sdasd a asd as as dasd asd a-muted">(en meses)</span></label>
                            <input type="number" class="form-control" id="plazo" required name="plazo" value="<?= (!empty($cotizacion["plazo"])) ? $cotizacion["plazo"] : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label for="constrccion" class="form-label">Tipo de Construcción</label>
                            <select class="form-select" id="constrccion" name="constrccion">
                                <?php if (!empty($cotizacion["constrccion"])) : ?>
                                    <option value="<?= $cotizacion["constrccion"] ?>"><?= $cotizacion["constrccion"] ?></option>
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
                            <input type="h4asdasd a sdasd a asd as as dasd asd a" class="form-control" id="direccion" name="direccion" required value="<?= (!empty($cotizacion["direccion"])) ? $cotizacion["direccion"] : "" ?>">
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit">Cotizar</button>
                </form>

                <hr class="my-4">

                <?php if (!empty($cotizacion["planes"])) : ?>
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

                    <a href="<?= site_url("cotizaciones/cotizacionincendio/" . json_encode($cotizacion)) ?>"target="_blank" class="w-100 btn btn-success btn-lg">Descargar</a>


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