<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Nombre</th>
                        <td><?= $cotizacion->getFieldValue("Nombre_codeudor") . " " . $cotizacion->getFieldValue("Apellido_codeudor") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">RNC/Cédula</th>
                        <td><?= $cotizacion->getFieldValue("RNC_C_dula_codeudor") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Email</th>
                        <td><?= $cotizacion->getFieldValue("Correo_electr_nico_codeudor") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Fecha de Nacimiento</th>
                        <td><?= $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Tel. Residencia</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Residencia_codeudor") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Celular</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Celular_codeudor") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Trabajo</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Trabajo_codeudor") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Dirección</th>
                        <td><?= $cotizacion->getFieldValue("Direcci_n_codeudor") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>