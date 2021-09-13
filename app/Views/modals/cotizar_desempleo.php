<div class="modal fade" id="cotizar_desempleo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cotización de Plan Vida</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="<?= site_url("cotizaciones") ?>">
        <input type="text" hidden value="desempleo" name="cotizacion">
        <div class="modal-body">
          <div class="mb-3">
            <label for="fecha" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha" required name="fecha">
          </div>
          <div class="mb-3">
            <label for="cuota" class="form-label">Cuota Mensual</label>
            <input type="number" class="form-control" name="cuota" required>
          </div>
          <div class="mb-3">
            <label for="plazo" class="form-label">Plazo</label>
            <input type="number" class="form-control" name="plazo" required>
          </div>
          <div class="mb-3">
            <label for="suma" class="form-label">Suma Asegurada</label>
            <input type="number" class="form-control" required name="suma">
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

<!-- Librerias adicionales -->

<!-- CSS personalizado -->
<?= $this->section('css') ?>
<!-- hace que el rango de clic del campo de fecha sea mas grande -->
<style>
  #fecha::-webkit-calendar-picker-indicator {
    padding-left: 50%;
  }
</style>
<?= $this->endSection() ?>