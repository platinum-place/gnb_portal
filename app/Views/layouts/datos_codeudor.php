<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <p class="card-text">
                <b>Nombre</b> <br>
                <b>RNC/Cédula</b> <br>
                <b>Email</b> <br>
                <b>Fecha de Nacimiento</b>
            </p>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="card-text">
                <?= $cotizacion->getFieldValue("Nombre_codeudor") . " " . $cotizacion->getFieldValue("Apellido_codeudor") ?> <br>
                <?= $cotizacion->getFieldValue("RNC_C_dula_codeudor") ?> <br>
                <?= $cotizacion->getFieldValue("Correo_electr_nico_codeudor") ?> <br>
                <?= $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor") ?>
            </p>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="card-text">
                <b>Tel. Residencia</b> <br>
                <b>Tel. Celular</b> <br>
                <b>Tel. Trabajo</b> <br>
                <b>Dirección</b>
            </p>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="card-text">
                <?= $cotizacion->getFieldValue("Tel_Residencia_codeudor") ?> <br>
                <?= $cotizacion->getFieldValue("Tel_Celular_codeudor") ?> <br>
                <?= $cotizacion->getFieldValue("Tel_Trabajo_codeudor") ?> <br>
                <?= $cotizacion->getFieldValue("Direcci_n_codeudor") ?>
            </p>
        </div>
    </div>
</div>