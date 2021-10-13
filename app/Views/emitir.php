<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<form enctype="multipart/form-data" method="POST" action="<?= site_url("emisiones/emitir/" . $cotizacion->getEntityId()) ?>">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Cotización</label>
                        <br>
                        <a href="<?= site_url("plantillas/cotizacion/" . $cotizacion->getEntityId()) ?>" class="btn btn-success mb-3" target="__blank">Descargar</a>
                    </div>



                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Aseguradora</label>
                                <select class="form-select" name="planid" required onchange="botonDescargar(this)">
                                    <option value="" selected disabled>Selecciona una aseguradora</option>
                                    <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
                                        <?php if ($lineItem->getNetTotal() > 0) : ?>
                                            <option value="<?= $lineItem->getProduct()->getEntityId() ?>"><?= $lineItem->getProduct()->getLookupLabel() ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Condicionado</label>
                                <div id="boton"></div>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Adjuntar documentos</label>
                                <input required multiple type="file" name="documentos[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="acuerdo" required>
                                    <label class="form-check-label" for="exampleCheck1">
                                        Estoy de acuerdo que quiero emitir la cotización no. <b><?= $cotizacion->getFieldValue('Quote_Number') ?></b>
                                        , a nombre de <b><?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?></b>,
                                        RNC/Cédula <b><?= $cotizacion->getFieldValue('RNC_C_dula') ?></b> .
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 mb-0">
                        <div class="d-grid">
                            <button button type="submit" class="btn btn-success btn-block">Emitir cotización</button>
                        </div>
                    </div>

                    <div class="mt-4 mb-0">
                        <div class="d-grid">
                            <button button type="submit" class="btn btn-primary btn-block">Editar cotización</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Funcion para cargar una url con codigo php cuando hagan una solicitud con ajax -->
<script>
    function botonDescargar(val) {
        var boton = '<a target="__blank" href="<?= site_url("cotizaciones/condicionado/") ?>' + val.value + '" class="btn btn-secondary mb-3">Descargar</a>';
        document.getElementById("boton").innerHTML = boton;
    }
</script>
<?= $this->endSection() ?>