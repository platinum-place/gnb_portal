<div class="modal fade" id="completar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Completar cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="<?= site_url("cotizaciones/completar") ?>">
                <div class="modal-body">
                    <h6>Datos del cliente</h6>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" name="apellido" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">RNC/Cédula</label>
                        <input type="text" class="form-control" name="rnc_cedula" required>
                    </div>

                    <!-- Formulario codeudor, en caso de plan vida -->
                    <?php if (!$cotizacion->fecha_deudor) : ?>

                        <div class="mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="fecha" id="fecha">
                        </div>

                    <?php else : ?>
                        <input type="text" hidden name="fecha" value="<?= $cotizacion->fecha_deudor ?>">
                    <?php endif ?>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tel. Celular</label>
                        <input type="tel" class="form-control" name="telefono" required placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tel. Residencial</label>
                        <input type="tel" class="form-control" name="tel_residencia" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tel. Trabajo</label>
                        <input type="tel" class="form-control" name="tel_trabajo" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                    </div>

                    <?php if ($cotizacion->marcaid) : ?>

                        <h6>Datos del vehículo</h6>
                        <hr>
                        <!-- datos del vehiculo -->
                        <div class="mb-3">
                            <label class="form-label">Chasis</label>
                            <input type="text" class="form-control" name="chasis" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Placa</label>
                            <input type="text" class="form-control" name="placa" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" class="form-control" name="color">
                        </div>

                        <input type="text" hidden name="marcaid" value="<?= $cotizacion->marcaid ?>">
                        <input type="text" hidden name="uso" value="<?= $cotizacion->uso ?>">
                        <input type="text" hidden name="ano" value="<?= $cotizacion->ano ?>">
                        <input type="text" hidden name="modeloid" value="<?= $cotizacion->modeloid ?>">
                        <input type="text" hidden name="modelotipo" value="<?= $cotizacion->modelotipo ?>">

                    <?php endif ?>

                    <!-- Formulario codeudor, en caso de plan vida -->
                    <?php if ($cotizacion->fecha_codeudor) : ?>

                        <h6>Datos del Codeudor</h6>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre_codeudor" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido_codeudor" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RNC/Cédula</label>
                            <input type="text" class="form-control" name="rnc_cedula_codeudor" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo_codeudor">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion_codeudor">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tel. Celular</label>
                            <input type="tel" class="form-control" name="telefono_codeudor" required placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tel. Residencial</label>
                            <input type="tel" class="form-control" name="tel_residencia_codeudor" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tel. Trabajo</label>
                            <input type="tel" class="form-control" name="tel_trabajo_codeudor" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                        </div>

                    <?php endif ?>

                    <!-- datos en general -->
                    <input type="text" hidden name="tipo" value="<?= $cotizacion->tipo ?>">
                    <input type="text" hidden name="plan" value="<?= $cotizacion->plan ?>">
                    <input type="number" hidden name="suma" value="<?= $cotizacion->suma ?>">
                    <input type="text" hidden name="planes" value='<?= json_encode($cotizacion->planes)  ?>'>
                    <input type="text" hidden name="cuota" value="<?= $cotizacion->cuota ?>">
                    <input type="text" hidden name="plazo" value="<?= $cotizacion->plazo ?>">
                    <input type="text" hidden name="fecha_codeudor" value="<?= $cotizacion->fecha_codeudor ?>">
                    <input type="text" hidden name="tipo_crm" value="<?= $cotizacion->tipo_crm ?>">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Completar</button>
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
        padding-left: 60%;
    }
</style>
<?= $this->endSection() ?>