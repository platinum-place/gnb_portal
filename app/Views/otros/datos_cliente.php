<div class="row border">
    <div class="col-6">
        <div class="row">
            <div class="col-6">
                <p>
                    <b>Nombre:</b> <br>
                    <b>RNC/Cédula:</b> <br>
                    <b>Email:</b> <br>
                    <b>Fecha de Nacimiento:</b>
                </p>
            </div>

            <div class="col-6">
                <p>
                    <?= $cotizacion->getFieldValue("Nombre") . " " . $cotizacion->getFieldValue("Apellido") ?> <br>
                    <?= $cotizacion->getFieldValue("RNC_C_dula") ?> <br>
                    <?= $cotizacion->getFieldValue("Correo_electr_nico") ?> <br>
                    <?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="row">
            <div class="col-6">
                <p>
                    <b>Tel. Residencia:</b> <br>
                    <b>Tel. Celular:</b> <br>
                    <b>Tel. Trabajo:</b> <br>
                    <b>Dirección:</b>
                </p>
            </div>

            <div class="col-6">
                <p>
                    <?= $cotizacion->getFieldValue("Tel_Residencia") ?> <br>
                    <?= $cotizacion->getFieldValue("Tel_Celular") ?> <br>
                    <?= $cotizacion->getFieldValue("Tel_Trabajo") ?> <br>
                    <?= $cotizacion->getFieldValue("Direcci_n") ?>
                </p>
            </div>
        </div>
    </div>
</div>