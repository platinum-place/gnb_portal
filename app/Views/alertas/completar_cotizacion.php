<?php
$fecha = date("d/m/Y", strtotime(date("Y-m-d") . "+ 30 days"));
?>

<h6>¡Cotización completada exitosamente!</h6>

<p>
    A continuación, debes completar el formulario para emitir la cotización,
    pero es posible continuar en otro momento. Fecha limite hasta el <b><?= $fecha ?></b>.
</p>