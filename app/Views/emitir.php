<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/emitir/" . $cotizacion->getEntityId()) ?>">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    Cotización
                </div>

                <div class="card-body">
                    <div class="mt-4 mb-0">
                        <div class="d-grid">
                            <a href="<?= site_url("plantillas/cotizacion/" . $cotizacion->getEntityId()) ?>" class="btn btn-primary btn-block" target="__blank">Descargar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    Aseguradora
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
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
                                <div id="boton"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    Documentos
                </div>

                <div class="card-body">
                    <div class="mb-3 mb-md-0">
                        <label class="form-label">Adjuntar documentos</label>
                        <input required multiple type="file" name="documentos[]" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    Acuerdo
                </div>

                <div class="card-body">
                    <div class="mb-3 mb-md-0">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="acuerdo" name="acuerdo" required>
                            <label class="form-check-label" for="acuerdo">
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
                    <button type="submit" class="btn btn-success btn-block">Emitir</button>
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
        var boton = '<a target="__blank" href="<?= site_url("cotizaciones/condicionado/") ?>' + val.value + '" class="btn btn-secondary mb-3">Descargar Condicionado</a>';
        document.getElementById("boton").innerHTML = boton;
    }
</script>
<?= $this->endSection() ?>