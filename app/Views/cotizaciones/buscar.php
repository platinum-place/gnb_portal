<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Lista de cotizaciones
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>No.</th>
                    <th>Nombre del cliente</th>
                    <th>RNC/Cédula del cliente</th>
                    <th>Tipo</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>No.</th>
                    <th>Nombre del cliente</th>
                    <th>RNC/Cédula del cliente</th>
                    <th>Tipo</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ((array)$cotizaciones as $cotizacion) : ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?></td>
                        <td><?= $cotizacion->getFieldValue('Quote_Number') ?></td>
                        <td>
                            <?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?>
                        </td>
                        <td><?= $cotizacion->getFieldValue('RNC_C_dula') ?></td>
                        <td><?= $cotizacion->getFieldValue('Tipo') ?> </td>
                        <td><?= $cotizacion->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                        <td>
                            <a href="<?= site_url("emisiones/emitir/" . $cotizacion->getEntityId()) ?>" title="Emitir">
                                <i class="far fa-user"></i>
                            </a>
                            |
                            <a href="<?= site_url("cotizaciones/editar/" . $cotizacion->getEntityId()) ?>" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            |
                            <a href="<?= site_url("cotizaciones/descargar/" . $cotizacion->getEntityId()) ?>" title="Descargar" target="__blank">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>