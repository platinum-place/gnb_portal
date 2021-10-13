<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>No.</th>
                    <th>Cliente</th>
                    <th>RNC/Cédula</th>
                    <th>Codeudor</th>
                    <th>Plan</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>No.</th>
                    <th>Cliente</th>
                    <th>RNC/Cédula</th>
                    <th>Codeudor</th>
                    <th>Plan</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ((array)$cotizaciones as $cotizacion) : ?>
                    <?php if ($cotizacion->getFieldValue('Quote_Stage') == $filtro) : ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?></td>
                            <td><?= date('d/m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) ?></td>
                            <td><?= $cotizacion->getFieldValue('Quote_Number') ?></td>
                            <td>
                                <?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?>
                            </td>
                            <td><?= $cotizacion->getFieldValue('RNC_C_dula') ?></td>
                            <td><?= (!empty($cotizacion->getFieldValue('Nombre_codeudor'))) ? "Aplica" : "No aplica"; ?> </td>
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

<?= $this->endSection() ?>