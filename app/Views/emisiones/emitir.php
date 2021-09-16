<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card  rounded-lg mt-5">
            <div class="card-header">
                &nbsp;
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="<?= site_url("emisiones/emitir/" . $cotizacion->getEntityId()) ?>">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Descargar cotización</label>
                        <div class="col-sm-8">
                            <a href="<?= site_url("cotizaciones/descargar/" . $cotizacion->getEntityId()) ?>" class="btn btn-success mb-3" target="__blank">Descargar</a>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Aseguradora</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="aseguradora" required onchange="botonDescargar(this)">
                                <option value="" selected disabled>Selecciona una aseguradora</option>
                                <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
                                    <?php if ($lineItem->getNetTotal() > 0) : ?>
                                        <option value="<?= $lineItem->getNetTotal() . "," . $lineItem->getProduct()->getEntityId() ?>"><?= $lineItem->getDescription() ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Descargar documentos</label>
                        <div class="col-sm-8">
                            <div id="boton"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Adjuntar documentos</label>
                        <div class="col-sm-8">
                            <input required multiple type="file" name="documentos[]" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Acuerdo</label>
                        <div class="col-sm-8">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="acuerdo" required>
                                    <label class="form-check-label" for="exampleCheck1">
                                        Estoy de acuerdo que quiero emitir la cotización no. <b><?= $cotizacion->getFieldValue('Quote_Number') ?></b>
                                        , a nombre de <b><?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?></b>,
                                        RNC/Cédula <b><?= $cotizacion->getFieldValue('RNC_C_dula') ?></b>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-primary">Emitir</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                &nbsp;
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Funcion para cargar una url con codigo php cuando hagan una solicitud con ajax -->
<script>
    function botonDescargar(val) {
        var boton = '<a target="__blank" href="<?= site_url("cotizaciones/documentos/") ?>' + val.value + '" class="btn btn-secondary mb-3">Descargar</a>';
        document.getElementById("boton").innerHTML = boton;
    }
</script>
<?= $this->endSection() ?>