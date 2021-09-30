<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Nombre</th>
                        <td><?= $detalles->getFieldValue("Nombre") . " " . $detalles->getFieldValue("Apellido") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">RNC/Cédula</th>
                        <td><?= $detalles->getFieldValue("RNC_C_dula") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Email</th>
                        <td><?= $detalles->getFieldValue("Correo_electr_nico") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Fecha de Nacimiento</th>
                        <td><?= $detalles->getFieldValue("Fecha_de_nacimiento") ?></td>
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
                        <td><?= $detalles->getFieldValue("Tel_Residencia") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Celular</th>
                        <td><?= $detalles->getFieldValue("Tel_Celular") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tel. Trabajo</th>
                        <td><?= $detalles->getFieldValue("Tel_Trabajo") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Dirección</th>
                        <td><?= $detalles->getFieldValue("Direcci_n") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>