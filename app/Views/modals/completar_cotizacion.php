<div class="modal fade" id="completar_cotizacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Completar cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="<?= site_url("cotizaciones/cotizar") ?>">
                <div class="modal-body">
                    <h6>Datos del cliente</h6>
                    <hr>
                    <!-- datos del cliente -->
                    <?= $this->include('otros/formulario_cliente') ?>

                    <!-- Formulario codeudor, en caso de plan vida -->
                    <?php if (!empty($cotizacion->fecha_codeudor)) : ?>
                        <h6>Datos del Codeudor</h6>
                        <hr>
                        <!-- datos del cliente -->
                        <?= $this->include('otros/formulario_codeudor') ?>

                        <input type="text" hidden name="fecha_codeudor" value="<?= $cotizacion->fecha_codeudor ?>">
                    <?php endif ?>

                    <!-- datos para plan vida -->
                    <?php if ($cotizacion->tipo == "Vida") : ?>
                        <input type="number" hidden name="plazo" value="<?= $cotizacion->plazo ?>">
                    <?php endif ?>


                    <?php if (!empty($cotizacion->marcaid)) : ?>
                        <h6>Datos del vehículo</h6>
                        <hr>
                        <!-- datos del vehiculo -->
                        <?= $this->include('otros/formulario_vehiculo') ?>

                        <input type="text" hidden name="marcaid" value="<?= $cotizacion->marcaid ?>">
                        <input type="text" hidden name="estado" value="<?= $cotizacion->estado ?>">
                        <input type="text" hidden name="uso" value="<?= $cotizacion->uso ?>">
                        <input type="text" hidden name="ano" value="<?= $cotizacion->ano ?>">
                        <input type="text" hidden name="modeloid" value="<?= $cotizacion->modeloid ?>">
                        <input type="text" hidden name="modelotipo" value="<?= $cotizacion->modelotipo ?>">
                    <?php endif ?>

                    <!-- datos en general -->
                    <input type="text" hidden name="plan" value="<?= $cotizacion->plan ?>">
                    <input type="text" hidden name="tipo" value="<?= $cotizacion->tipo ?>">
                    <input type="number" hidden name="suma" value="<?= $cotizacion->suma ?>">
                    <input type="text" hidden name="planes" value='<?= json_encode($cotizacion->planes)  ?>'>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Completar</button>
                </div>
            </form>
        </div>
    </div>
</div>