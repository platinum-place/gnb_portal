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
                            <th scope="col">#</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Plan</th>
                            <th scope="col">Aseguradora</th>
                            <th scope="col">Referidor</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $cont = 1; ?>
                        <?php foreach ((array)$cotizaciones as $cotizacion) : ?>
                            <?php if (date('m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) == date("m/Y") and $cotizacion->getFieldValue('Quote_Stage') == "Emitida") : ?>
                                <tr>
                                    <td><?= $cont ?></td>
                                    <td><?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?></td>
                                    <td>
                                        <?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?>
                                    </td>
                                    <td><?= $cotizacion->getFieldValue('Plan') ?> </td>
                                    <td><?= $cotizacion->getFieldValue('Coberturas')->getLookupLabel() ?></td>
                                    <td><?= $cotizacion->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                                </tr>
                                <?php $cont++ ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>