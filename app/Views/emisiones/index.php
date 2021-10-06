<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- formulario para buscar usando la api  -->
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
                        <option value="codigo">Codigo</option>
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
                <?php foreach ((array)$emisiones as $emision) : ?>
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
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>