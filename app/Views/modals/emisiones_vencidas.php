<div class="modal fade" id="emisiones_vencidas" tabindex="-1" aria-labelledby="emisiones_vencidas" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emisiones_vencidas">Pólizas vencidas este mes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">No.</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Plan</th>
                            <th scope="col">Referidor</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ((array)$cotizaciones as $cotizacion) : ?>
                            <?php if (date('m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) == date("m/Y") and $cotizacion->getFieldValue('Quote_Stage') == "Emitida") : ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?></td>
                                    <td><?= $cotizacion->getFieldValue('Quote_Number') ?></td>
                                    <td>
                                        <?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?>
                                    </td>
                                    <td><?= $cotizacion->getFieldValue('Plan') ?> </td>
                                    <td><?= $cotizacion->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                                    <td>
                                        <?php if ($cotizacion->getFieldValue('Quote_Stage') == "Cotizando") : ?>
                                            <a href="<?= site_url("cotizaciones/emitir/" . $cotizacion->getEntityId()) ?>" title="Emitir">
                                                <i class="far fa-user"></i>
                                            </a>
                                            |
                                            <a href="<?= site_url("cotizaciones/editar/" . $cotizacion->getEntityId()) ?>" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            |
                                            <a href="<?= site_url("plantillas/cotizacion/" . $cotizacion->getEntityId()) ?>" title="Descargar" target="__blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php else : ?>
                                            <a href="<?= site_url("cotizaciones/adjuntar/" . $cotizacion->getEntityId()) ?>" title="Adjuntar">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                            |
                                            <a href="<?= site_url("plantillas/emision/" . $cotizacion->getEntityId()) ?>" title="Descargar" target="__blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>