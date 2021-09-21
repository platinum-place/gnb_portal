<div class="row">
    <div class="col-4">
        <img src="<?= base_url("img/nobe.png") ?>" width="100" height="100">
    </div>

    <div class="col-4">
        <h4 class="text-center text-uppercase">COTIZACIÓN <br>
            SEGURO VEHÍCULO DE MOTOR <br>
            PLAN <?= $cotizacion->getFieldValue('Plan') ?>
        </h4>
    </div>

    <div class="col-4">
        <p style="text-align: right">
            <b>Fecha</b> <?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?> <br>
            <b>No.</b> <?= $cotizacion->getFieldValue('Quote_Number') ?>
        </p>
    </div>
</div>