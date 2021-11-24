<form method="POST" action="<?= site_url("cotizaciones") ?>">
<input type="text" hidden value="Vida/Desempleo" name="plan">

    <div class="modal fade" id="cotizar_desempleo" tabindex="-1" aria-labelledby="cotizar_desempleo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cotizar_desempleo">Cotizar Plan Vida/Desempleo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="deudor" required name="deudor">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Cuota Mensual</label>
                                <input type="number" class="form-control" name="cuota" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Plazo</label>
                                <input type="number" class="form-control" name="plazo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Suma Asegurada</label>
                                <input type="number" class="form-control" required name="suma">
                            </div>
                        </div>
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