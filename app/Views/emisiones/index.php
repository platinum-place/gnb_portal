<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php if (session('usuario')->getFieldValue("Title") == "Administrador") : ?>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search"></i>
            Busqueda avanzada
        </div>
        <div class="card-body">
            <form class="row" action="<?= site_url("emisiones") ?>" method="post">
                <div class="col-md-4">
                    <select class="form-select" name="opcion" required>
                        <option value="codigo">No. emisión</option>
                        <option value="nombre">Nombre del cliente</option>
                        <option value="apellido">Apellido del cliente</option>
                        <option value="id">RNC/Cédula del cliente</option>
                    </select>
                </div>

                <div class="col-4">
                    <input type="text" class="form-control" name="busqueda" required>
                </div>

                <div class="col-4">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    |
                    <a href="<?= site_url("emisiones") ?>" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>

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
                    <th>No. emisión</th>
                    <th>Nombre del cliente</th>
                    <th>RNC/Cédula del cliente</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Prima</th>
                    <th>Vendedor</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>No. emisión</th>
                    <th>Nombre del cliente</th>
                    <th>RNC/Cédula del cliente</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Prima</th>
                    <th>Vendedor</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ((array)$emisiones as $emision) : ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($emision->getCreatedTime())) ?></td>
                        <td><?= $emision->getFieldValue('TUA') ?></td>
                        <td>
                            <?= $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido') ?>
                        </td>
                        <td><?= $emision->getFieldValue('Identificaci_n') ?></td>
                        <td><?= $emision->getFieldValue('Type') ?> </td>
                        <td><?= $emision->getFieldValue('Stage') ?> </td>
                        <td>RD$<?= number_format($emision->getFieldValue('Amount'), 2) ?></td>
                        <td><?= $emision->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
                        <td>
                            <a href="<?= site_url("emisiones/descargar/" . $emision->getEntityId()) ?>" title="Descargar" target="__blank">
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