<div class="modal fade" id="cotizar_incendio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cotización Seguro Incendio Hipotecario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="<?= site_url("cotizaciones") ?>">
        <input type="text" hidden value="incendio" name="cotizacion">
        <div class="modal-body">
          <div class="mb-3">
            <label for="suma" class="form-label">Valor de la Propiedad</label>
            <input type="number" class="form-control" name="suma" required>
          </div>
          <div class="mb-3">
            <label for="cuota" class="form-label">Valor del Préstamo</label>
            <input type="number" class="form-control" name="cuota" required>
          </div>
          <div class="mb-3">
            <label for="construccion" class="form-label">Tipo de Construcción</label>
            <select class="form-select" name="construccion">
              <option value="Superior">Superior</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="riesgo" class="form-label">Tipo de Riesgo</label>
            <select class="form-select" name="riesgo">
              <option value="Vivienda">Vivienda</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Cotizar</button>
        </div>
      </form>
    </div>
  </div>
</div>