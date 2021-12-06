<h6>¡Cotización emitida correctamente!</h6>

<p>Mientras la emisión, <b>a nombre de <?= $cliente ?></b>, está siendo depurada por nosotros, puedes hacer clic en las
    siguientes acciones:</p>

<ul>
    <li>
        <a href="<?= site_url("cotizaciones/adjuntar/$id") ?>" title="Adjuntar">
            <i class="fas fa-upload"></i>
            <b>Adjuntar:</b>
        </a>

        Adjuntar más documentos a la emisión para ser validados.
    </li>

    <li>
        <a href="<?= site_url("cotizaciones/descargar/$id") ?>" title="Descargar" target="__blank">
            <i class="fas fa-download"></i>
            <b>Descargar:</b>
        </a>

        Constancia de emisión en formato PDF.
    </li>
</ul>