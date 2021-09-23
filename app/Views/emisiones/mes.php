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
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>No.</th>
                    <th>Nombre Cliente</th>
                    <th>RNC/Cédula Cliente</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Suma Asegurada</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>No. emisión</th>
                    <th>Nombre Cliente</th>
                    <th>RNC/Cédula Cliente</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th>Suma Asegurada</th>
                    <th>Referidor</th>
                </tr>
            </tfoot>
            <tbody>
                <!-- contador para los modals -->
                <?php $cont = 0 ?>
                <?php foreach ((array)$emisiones as $emision) : ?>
                    <?php if (date("m/Y", strtotime($emision->getCreatedTime())) == date("m/Y")) : ?>
                        <tr>
                            <td><?= date("d/m/Y", strtotime($emision->getCreatedTime())) ?></td>
                            <td><?= date("d/m/Y", strtotime($emision->getFieldValue('Due_Date'))) ?></td>
                            <td><?= $emision->getFieldValue('SO_Number') ?></td>
                            <td>
                                <?= $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido') ?>
                            </td>
                            <td><?= $emision->getFieldValue('RNC_C_dula') ?></td>
                            <td><?= $emision->getFieldValue('Plan') ?> </td>
                            <td><?= $emision->getFieldValue('Status') ?> </td>
                            <td>RD$<?= number_format($emision->getFieldValue('Suma_asegurada'), 2) ?></td>
                            <td><?= $emision->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                            <td>
                                <a href="<?= site_url("emisiones/descargar/" . $emision->getEntityId()) ?>" title="Descargar" target="__blank">
                                    <i class="fas fa-download"></i>
                                </a>
                                |
                                <!-- Button trigger modal -->
                                <a href="#" data-bs-toggle="modal" data-bs-target="#subir<?= $cont ?>">
                                    <i class="fas fa-upload"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="subir<?= $cont ?>" tabindex="-1" aria-labelledby="label<?= $cont ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="label<?= $cont ?>">
                                            Adjuntar a emisión a nombre de
                                            <?= $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido') ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?= site_url("emisiones/adjuntar/" . $emision->getEntityId()) ?>" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Adjuntar documentos</label>
                                                <input required type="file" name="documentos[]" multiple class="form-control">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Adjuntar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>