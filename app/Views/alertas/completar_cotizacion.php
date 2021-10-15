<?php
$fecha = date("d/m/Y", strtotime(date("Y-m-d") . "+ 30 days"));
?>

<p>
    ¡Cotización,a nombre de <? $cliente ?>, completada exitosamente! A continuación,
    debe emitida, pero es posible continuar el proceso en otro momento.
    La cotización un límite de vigencia hasta el <b><?= $fecha ?></b>.
</p>

<p>También puede:</p>

<ul>
    <li>
        <i class="far fa-user"></i>, <b>Emitir:</b> Es necesario adjuntar documentos, el condicionado y la cotización firmada.
    </li>

    <li>
        <i class="fas fa-edit"></i>, <b>Editar:</b> Cambiar algunos datos de la cotización.
    </li>

    <li>
        <i class="fas fa-download"></i>, <b>Descargar:</b> Cotización en formato PDF.
    </li>
</ul>