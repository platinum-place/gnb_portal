<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Emisiones</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?= site_url("cotizaciones") ?>" class="btn btn-sm btn-outline-secondary">Cotizar</a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <h5 class="card-header">Buscar por:</h5>
    <div class="card-body">
        <form class="row" action="<?= site_url("emisiones") ?>" method="post">

            <div class="col-3">
                <select name="opcion" class="form-control" required>
                    <option value="cliente">Nombre del cliente</option>
                </select>
            </div>

            <div class="col-4">
                <input type="text" class="form-control" name="busqueda" required>
            </div>

            <div class="col-2">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>

        </form>
    </div>
</div>


<h2>Pólizas Emitidas</h2>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Cédula</th>
                <th>Aseguradora</th>
                <th>Plan</th>
                <th>Prima</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emisiones as $emision) : ?>
                <tr>
                    <td><?= date("d/m/Y", strtotime($emision->getCreatedTime())) ?></td>
                    <td><?= $emision->getFieldValue("Nombre") . " " . $emision->getFieldValue("Apellido") ?></td>
                    <td><?= $emision->getFieldValue("Identificaci_n") ?></td>
                    <td><?= $emision->getFieldValue("Aseguradora")->getLookupLabel() ?></td>
                    <td>text</td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>


<?= $this->endSection() ?>