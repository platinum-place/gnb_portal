<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Marca</th>
                        <td><?= $detalles->getFieldValue('Marca')->getLookupLabel() ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Modelo</th>
                        <td><?= $detalles->getFieldValue('Modelo')->getLookupLabel() ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Año</th>
                        <td><?= $detalles->getFieldValue("A_o") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Color</th>
                        <td><?= $detalles->getFieldValue("Color") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tipo</th>
                        <td><?= $detalles->getFieldValue("Tipo_veh_culo") ?></td>
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
                        <th scope="col">Chasis</th>
                        <td><?= $detalles->getFieldValue("Chasis") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Placa</th>
                        <td><?= $detalles->getFieldValue("Placa") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Uso</th>
                        <td><?= $detalles->getFieldValue("Uso") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Condiciones</th>
                        <td><?= $detalles->getFieldValue("Condiciones") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Suma asegurada</th>
                        <td>RD$<?= number_format($detalles->getFieldValue("Suma_asegurada"), 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>