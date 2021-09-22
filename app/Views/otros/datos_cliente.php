<div class="card-group" style="font-size: small;">
    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <b>Nombre</b> <br>
                <b>RNC/Cédula</b> <br>
                <b>Email</b> <br>
                <b>Fecha de Nacimiento</b>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <?= $detalles->getFieldValue("Nombre") . " " . $detalles->getFieldValue("Apellido") ?> <br>
                <?= $detalles->getFieldValue("RNC_C_dula") ?> <br>
                <?= $detalles->getFieldValue("Correo_electr_nico") ?> <br>
                <?= $detalles->getFieldValue("Fecha_de_nacimiento") ?>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <b>Tel. Residencia</b> <br>
                <b>Tel. Celular</b> <br>
                <b>Tel. Trabajo</b> <br>
                <b>Dirección</b>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="card-text">
                <?= $detalles->getFieldValue("Tel_Residencia") ?> <br>
                <?= $detalles->getFieldValue("Tel_Celular") ?> <br>
                <?= $detalles->getFieldValue("Tel_Trabajo") ?> <br>
                <?= $detalles->getFieldValue("Direcci_n") ?>
            </p>
        </div>
    </div>
</div>