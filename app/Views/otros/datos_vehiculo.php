<div class="row border">
    <div class="col-6">
        <div class="row">
            <div class="col-6">
                <p>
                    <b>Marca:</b> <br>
                    <b>Modelo:</b> <br>
                    <b>Año:</b> <br>
                    <b>Color:</b> <br>
                    <b>Tipo:</b>
                </p>
            </div>

            <div class="col-6">
                <p>
                    <?= $cotizacion->getFieldValue('Marca')->getLookupLabel() ?> <br>
                    <?= $cotizacion->getFieldValue('Modelo')->getLookupLabel() ?> <br>
                    <?= $cotizacion->getFieldValue("A_o") ?> <br>
                    <?= $cotizacion->getFieldValue("Color") ?> <br>
                    <?= $cotizacion->getFieldValue("Tipo_veh_culo") ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="row">
            <div class="col-6">
                <p>
                    <b>Chasis:</b> <br>
                    <b>Placa:</b> <br>
                    <b>Uso:</b> <br>
                    <b>Condiciones:</b> <br>
                    &nbsp;
                </p>
            </div>

            <div class="col-6">
                <p>
                    <?= $cotizacion->getFieldValue("Chasis") ?> <br>
                    <?= $cotizacion->getFieldValue('Placa') ?> <br>
                    <?= $cotizacion->getFieldValue("Uso") ?> <br>
                    <?= $cotizacion->getFieldValue("Condiciones") ?> <br>
                    &nbsp;
                </p>
            </div>
        </div>
    </div>
</div>