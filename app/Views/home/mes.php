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
                    <th>Codigo</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>Plan</th>
                    <th>Cliente</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>Codigo</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>Plan</th>
                    <th>Cliente</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>

            <tbody>
                <!-- contador para los modals -->
                <?php $cont = 0 ?>
                <?php foreach ((array)$emisiones as $emision) : ?>
                    <?php if (date("m/Y", strtotime($emision->getFieldValue('Fecha_de_inicio'))) == date("m/Y")) : ?>
                        <tr>
                            <td><?= $emision->getFieldValue('Numeraci_n') ?></td>
                            <td><?= date("d/m/Y", strtotime($emision->getFieldValue('Fecha_de_inicio'))) ?></td>
                            <td><?= date("d/m/Y", strtotime($emision->getFieldValue('Closing_Date'))) ?></td>
                            <td><?= $emision->getFieldValue('Plan') ?> </td>
                            <td> <?= $emision->getFieldValue('Cliente')->getLookupLabel() ?> </td>
                            <td><?= $emision->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                            <td>
                                <a href="<?= site_url("emisiones/descargar/" . $emision->getEntityId()) ?>" title="Descargar" target="__blank">
                                    <i class="fas fa-download"></i>
                                </a>
                                |
                                <a href="<?= site_url("adjuntos/emisiones/" . $emision->getEntityId()) ?>" title="Adjuntar">
                                    <i class="fas fa-upload"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endif ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>