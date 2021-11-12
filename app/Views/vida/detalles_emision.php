<h5 class="d-flex justify-content-center bg-primary text-white">PRIMA MENSUAL</h5>
<div class="card-group border" style="font-size: small;">
    <div class="card border-0">
        <div class="card-body">
            <p class="card-title">
                <b>Fecha Deudor</b>

                <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
                    <br> <b>Fecha Codeudor</b>
                <?php endif ?>
            </p>

            <p class="card-title">
                <b>Suma Asegurada</b> <br>
                <b>Plazo</b>
            </p>

            <p class="card-title">
                <b>Prima Neta</b> <br>
                <b>ISC</b> <br>
                <b>Prima Total</b>
            </p>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="card-title">
                <?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>

                <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
                    <br> <?= $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor") ?>
                <?php endif ?>
            </p>

            <p class="card-title">
                RD<?= number_format($cotizacion->getFieldValue("Suma_asegurada"), 2) ?> <br>
                <?= $cotizacion->getFieldValue("Plazo") ?> meses
            </p>

            <p class="card-title">
                RD$ <?= number_format($cotizacion->getFieldValue('Prima_neta'), 2) ?> <br>
                RD$ <?= number_format($cotizacion->getFieldValue('ISC'), 2) ?> <br>
                RD$ <?= number_format($cotizacion->getFieldValue('Prima'), 2) ?>
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<div class="card-group" style="font-size: small;">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title text-center">REQUISITOS DEL DEUDOR</h6>
            <?php $requisitos = $plan->getFieldValue("Requisitos_deudor"); ?>
            <ul>
                <?php foreach ($requisitos as $posicion => $requisito) : ?>
                    <li><?= $requisito  ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <?php if (!empty($cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor"))) : ?>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-center">REQUISITOS DEL CODEUDOR</h6>
                <?php $requisitos = $plan->getFieldValue("Requisitos_codeudor"); ?>
                <ul>
                    <?php foreach ($requisitos as $posicion => $requisito) : ?>
                        <li><?= $requisito  ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    <?php endif ?>
</div>