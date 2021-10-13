<form method="POST" action="<?= site_url("cotizaciones/cotizar") ?>">
    <input type="text" hidden name="tipo" value="auto">

    <div class="modal fade" id="cotizar_auto" tabindex="-1" aria-labelledby="cotizar_auto" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cotizar_auto">Cotizar Plan Auto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Marca</label>
                                <select name="marca" class="form-control selectpicker" id="marca" onchange="modelosAJAX(this)" required data-live-search="true">
                                    <option value="" selected disabled>Selecciona una Marca</option>
                                    <?php foreach ($marcas as $marca) : ?>
                                        <option value="<?= $marca->getEntityId() ?>">
                                            <?= strtoupper($marca->getFieldValue('Name')) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Modelo</label>
                                <select name="modelo" class="form-control selectpicker" id="modelos" required data-live-search="true">
                                    <option value="" selected disabled>Selecciona un modelo</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Año</label>
                                <input type="number" class="form-control" name="ano" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Suma Asegurada</label>
                                <input type="number" class="form-control" required name="suma">
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Uso</label>
                                <select name="uso" class="form-control">
                                    <option value="Privado" selected>Privado</option>
                                    <option value="Publico">Publico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Plan</label>
                                <select name="plan" class="form-control">
                                    <option value="Mensual full" selected>Mensual Full</option>
                                    <option value="Anual full">Anual Full</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="Nuevo" selected>Nuevo</option>
                            <option value="Usado">Usado</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Cotizar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- CSS personalizado -->
<?= $this->section('css') ?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<?= $this->endSection() ?>


<!-- JS personalizado -->
<?= $this->section('js') ?>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<!-- Funcion para cargar una url con codigo php cuando hagan una solicitud con ajax -->
<script>
    function modelosAJAX(val) {
        $.ajax({
            type: 'ajax',
            url: "<?= site_url('cotizaciones/lista_modelos') ?>",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            method: "POST",
            data: {
                marcaid: val.value
            },
            success: function(response) {
                //agrega el codigo php en el select
                document.getElementById("modelos").innerHTML = response;
                //refresca solo el select para actualizar la interfaz del select
                $('.selectpicker').selectpicker('refresh');
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
</script>
<?= $this->endSection() ?>