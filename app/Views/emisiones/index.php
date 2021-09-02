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

<?php if (session()->getFlashdata('alerta')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('alerta') ?>
    </div>
<?php endif ?>

<div class="card mb-4">
    <h5 class="card-header">Buscar por:</h5>
    <div class="card-body">
        <form class="row" action="<?= site_url("emisiones") ?>" method="post">
            <div class="col-md-4">
                <select class="form-select" name="opcion" required>
                    <option value="nombre">Nombre del cliente</option>
                    <option value="apellido">Apellido del cliente</option>
                    <option value="id">RNC/Cédula del cliente</option>
                    <option value="codigo">Código</option>
                </select>
                <div class="invalid-feedback">
                    Campo obligatorio.
                </div>
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


<h2>Pólizas Emitidas</h2>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Código</th>
                <th>Cliente</th>
                <th>Cédula</th>
                <th>Aseguradora</th>
                <th>Plan</th>
                <th>Suma Aseguradora</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ((array)$emisiones as $emision) : ?>
                <tr>
                    <td><?= date("d/m/Y", strtotime($emision->getCreatedTime())) ?></td>
                    <td><?= $emision->getFieldValue("TUA")  ?></td>
                    <td><?= $emision->getFieldValue("Nombre") . " " . $emision->getFieldValue("Apellido") ?></td>
                    <td><?= $emision->getFieldValue("Identificaci_n") ?></td>
                    <td><?= $emision->getFieldValue("Aseguradora")->getLookupLabel() ?></td>
                    <td><?= $emision->getFieldValue("Plan") ?></td>
                    <td>RD$<?= number_format($emision->getFieldValue("Suma_asegurada"), 2) ?></td>
                    <td><?= $emision->getFieldValue("Stage") ?></td>
                    <td>
                        <a href="<?= site_url("plantillas/emision/" . $emision->getEntityId()) ?>" target="_blank" title="Descargar emisión">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                            </svg>
                        </a>
                        |
                        <a href="<?= site_url("documentos/bajar/" . $emision->getFieldValue("Coberturas")->getEntityId()) ?>" title="Descargar documentos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z" />
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                            </svg>
                        </a>
                        |
                        <a href="<?= site_url("documentos/subir/" . $emision->getEntityId()) ?>" title="Adjuntar documentos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>