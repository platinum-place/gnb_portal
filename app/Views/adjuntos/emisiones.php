<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Documento</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ((array)$adjuntos as $adjunto) : ?>
                                <tr>
                                    <td><?= $adjunto->getFileName() ?></td>
                                    <td>
                                        <?php $json = json_encode([$emision->getEntityId(), $adjunto->getId()]) ?>
                                        <a href="<?= site_url("adjuntos/adjunto_emision/" .  $json) ?>" class="btn btn-primary">
                                            Descargar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= site_url("adjuntos/emisiones/" . $emision->getEntityId()) ?>">

                    <div class="mb-3">
                        <label class="form-label">Adjuntar documentos</label>
                        <input required type="file" name="documentos[]" multiple class="form-control">
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-success">Adjuntar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>