<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th scope="col">Marca</th>
                        <td><?= $cotizacion->getFieldValue('Marca')->getLookupLabel() ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Modelo</th>
                        <td><?= $cotizacion->getFieldValue('Modelo')->getLookupLabel() ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Año</th>
                        <td><?= $cotizacion->getFieldValue("A_o") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Color</th>
                        <td><?= $cotizacion->getFieldValue("Color") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Tipo</th>
                        <td><?= $cotizacion->getFieldValue("Tipo_veh_culo") ?></td>
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
                        <td><?= $cotizacion->getFieldValue("Chasis") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Placa</th>
                        <td><?= $cotizacion->getFieldValue("Placa") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Uso</th>
                        <td><?= $cotizacion->getFieldValue("Uso") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Condiciones</th>
                        <td><?= $cotizacion->getFieldValue("Condiciones") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Suma asegurada</th>
                        <td>RD$<?= number_format($cotizacion->getFieldValue("Suma_asegurada"), 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>