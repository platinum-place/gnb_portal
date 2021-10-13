<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Nombre</th>
                        <td><?= $cotizacion->getFieldValue("Nombre") . " " . $cotizacion->getFieldValue("Apellido") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">RNC/Cédula</th>
                        <td><?= $cotizacion->getFieldValue("RNC_C_dula") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Email</th>
                        <td><?= $cotizacion->getFieldValue("Correo_electr_nico") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Fecha de Nacimiento</th>
                        <td><?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?></td>
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
                        <td><?= $cotizacion->getFieldValue("Tel_Residencia") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Celular</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Celular") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Trabajo</th>
                        <td><?= $cotizacion->getFieldValue("Tel_Trabajo") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Dirección</th>
                        <td><?= $cotizacion->getFieldValue("Direcci_n") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>