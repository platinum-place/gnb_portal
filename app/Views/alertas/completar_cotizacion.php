<?php
$fecha = date("d/m/Y", strtotime(date("Y-m-d") . "+ 30 days"));
?>

<h6>¡Cotización completada exitosamente!</h6>

<p>
    A continuación, debes completar el formulario para emitir la cotización,
    pero es posible continuar hasta antes del <b><?= $fecha ?></b>.
</p>

<p>También puede:</p>

<ul>
    <li>
        <i class="far fa-user"></i> <b>Emitir:</b> Es necesario adjuntar documentos, el condicionado y la cotización firmada.
    </li>

    <li>
        <i class="fas fa-edit"></i> <b>Editar:</b> Cambiar algunos datos de la cotización.
    </li>

    <li>
        <i class="fas fa-download"></i> <b>Descargar:</b> Cotización en formato PDF.
    </li>
</ul>