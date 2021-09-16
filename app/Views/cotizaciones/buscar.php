<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php if (session('usuario')->getFieldValue("Title") == "Administrador") : ?>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search"></i>
            Busqueda avanzada
        </div>
        <div class="card-body">
            <form class="row" action="<?= site_url("cotizaciones/buscar") ?>" method="post">
                <div class="col-md-4">
                    <select class="form-select" name="opcion" required>
                        <option value="codigo">No. cotización</option>
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
                    <a href="<?= site_url("cotizaciones/buscar") ?>" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>
<?php endif ?>

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
                <?php foreach ((array)$cotizaciones as $cotizacion) : ?>
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