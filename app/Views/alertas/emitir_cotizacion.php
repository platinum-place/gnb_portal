<h6>¡Cotización, a nombre de <? $cliente ?>, emitida correctamente!</h6>

<p>Mientras puedes:</p>

<ul>
    <li>
        <i class="fas fa-upload"></i>, <b>Adjuntar:</b> Adjuntar documentos a la emisión para ser validados.

        Presiona <a href="<?= site_url("cotizaciones/adjuntar/$id") ?>">aqui</a> para adjuntar algun documento.
    </li>

    <li>
        <i class="fas fa-download"></i>, <b>Descargar:</b> Emisión en formato PDF.

        Presiona <a href="<?= site_url("plantillas/emision/$id") ?>">aqui</a> para descargar la emisión.
    </li>
</ul>