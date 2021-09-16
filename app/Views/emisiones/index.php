<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Lista de emisiones
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>No. cotización</th>
                    <th>Nombre del cliente</th>
                    <th>RNC/Cédula del cliente</th>
                    <th>Tipo</th>
                    <th>Suma Asegurado</th>
                    <th>Vendedor</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>No. cotización</th>
                    <th>Nombre del cliente</th>
                    <th>RNC/Cédula del cliente</th>
                    <th>Tipo</th>
                    <th>Suma Asegurado</th>
                    <th>Vendedor</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ((array)$emisiones as $emision) : ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?></td>
                        <td><?= $cotizacion->getFieldValue('Quote_Number') ?></td>
                        <td>
                            <?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?>
                        </td>
                        <td><?= $cotizacion->getFieldValue('RNC_C_dula') ?></td>
                        <td><?= $cotizacion->getFieldValue('Tipo') ?> </td>
                        <td>RD$<?= number_format($cotizacion->getFieldValue('Suma_asegurada'), 2) ?></td>
                        <td><?= $cotizacion->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                        <td>
                            <a href="<?= site_url("emisiones/emitir/" . $cotizacion->getEntityId()) ?>" title="Emitir">
                                <i class="far fa-user"></i>
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