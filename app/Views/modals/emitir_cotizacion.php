<form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/emitir/" . $cotizacion->getEntityId()) ?>">
    <div class="modal fade" id="emitir_cotizacion" tabindex="-1" aria-labelledby="emitir_cotizacion" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emitir_cotizacion">
                        Emitir cotización, a nombre de <?= $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
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
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</form>


<?= $this->section('js') ?>
<script>
    //representan los modals
    var emitir_cotizacion = new bootstrap.Modal(document.getElementById('emitir_cotizacion'), {});
    //mostrar los resultados
    emitir_cotizacion.show();
</script>
<?= $this->endSection() ?>