<div class="card-group" style="font-size: small;">
    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <b>Marca</b> <br>
                <b>Modelo</b> <br>
                <b>Año</b> <br>
                <b>Color</b> <br>
                <b>Tipo</b>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <?= $detalles->getFieldValue('Marca')->getLookupLabel() ?> <br>
                <?= $detalles->getFieldValue('Modelo')->getLookupLabel() ?> <br>
                <?= $detalles->getFieldValue("A_o") ?> <br>
                <?= $detalles->getFieldValue("Color") ?> <br>
                <?= $detalles->getFieldValue("Tipo_veh_culo") ?>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <b>Chasis</b> <br>
                <b>Placa</b> <br>
                <b>Uso</b> <br>
                <b>Condiciones</b>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <?= $detalles->getFieldValue("Chasis") ?> <br>
                <?= $detalles->getFieldValue('Placa') ?> <br>
                <?= $detalles->getFieldValue("Uso") ?> <br>
                <?= $detalles->getFieldValue("Condiciones") ?>
            </p>
        </div>
    </div>
</div>