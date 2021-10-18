<form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/adjuntar/" . $cotizacion->getEntityId()) ?>">
    <div class="modal fade" id="adjuntar_documentos" tabindex="-1" aria-labelledby="adjuntar_documentos" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adjuntar_documentos">
                        Adjuntar documentos a emisión, a nombre de <?= $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Adjuntar documentos</label>
                        <input required type="file" name="documentos[]" multiple class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Adjuntar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->section('js') ?>
<script>
    //representan los modals
    var adjuntar_documentos = new bootstrap.Modal(document.getElementById('adjuntar_documentos'), {});
    //mostrar los resultados
    adjuntar_documentos.show();
</script>
<?= $this->endSection() ?>