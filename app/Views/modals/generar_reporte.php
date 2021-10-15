<form method="POST" action="<?= site_url("cotizaciones/reportes") ?>">
    <div class="modal fade" id="generar_reporte" tabindex="-1" aria-labelledby="generar_reporte" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generar_reporte">Reporte de Emisiones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Plan</label>
                                <select class="form-select" name="plan">
                                    <option value="Todos">Todos</option>
                                    <option value="Auto">Auto</option>
                                    <option value="Vida">Vida</option>
                                    <option value="Vida/Desempleo">Vida/Desempleo</option>
                                    <option value="Seguro Incendio Hipotecario">Seguro Incendio Hipotecario</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control" id="desde" name="desde" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Hasta</label>
                                <input type="date" class="form-control" id="hasta" name="hasta" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Generar</button>
                </div>
            </div>
        </div>
    </div>
</form>