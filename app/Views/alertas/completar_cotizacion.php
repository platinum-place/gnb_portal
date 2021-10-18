<?php
$fecha = date("d/m/Y", strtotime(date("Y-m-d") . "+ 30 days"));
?>

<h6>¡Cotización,a nombre de <? $cliente ?>, completada exitosamente!</h6>

<p>
    A continuación, debe emitida o continuar el proceso en otro momento.
    La cotización tiene un límite de vigencia hasta el <b><?= $fecha ?></b>.
</p>

<p>También puede:</p>

<ul>
    <li>
        <i class="far fa-user"></i> <b>Emitir:</b> Es necesario adjuntar documentos, el condicionado y la cotización firmada.

        Presiona <a href="<?= site_url("cotizaciones/emitir/$id") ?>">aquí</a> para emitir la cotización.
    </li>

    <li>
        <i class="fas fa-edit"></i> <b>Editar:</b> Cambiar algunos datos de la cotización.

        Presiona <a href="<?= site_url("cotizaciones/editar/$id") ?>">aquí</a> para editar la cotización.
    </li>

    <li>
        <i class="fas fa-download"></i> <b>Descargar:</b> Cotización en formato PDF.

        Presiona <a href="<?= site_url("plantillas/cotizacion/$id") ?>" target="__blank">aquí</a> para descargar la cotización.
    </li>
</ul>