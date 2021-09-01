<?= $this->extend('app') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Panel de control</h1>
</div>

<div class="alert alert-success" role="alert">
    <h3 class="alert-heading">¡Bienvenido al Insurance Tech de Grupo Nobe!</h3>
    <p>Desde su panel de control podrás ver la infomación necesaria para manejar sus pólizas y cotizaciones.</p>
</div>

<div class="row row-cols-1 row-cols-md-3 mb-3">
    <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3 text-white bg-primary border-primary">
                <h4 class="my-0 fw-normal">Pólizas emitidas este mes</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title"><?= $polizas ?></h1>
                <!-- <button type="button" class="w-100 btn btn-lg btn-outline-primary">Ver más</button> -->
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3 text-white bg-success border-primary">
                <h4 class="my-0 fw-normal">Pólizas en evaluación</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title"><?= $evaluacion ?></h1>
                <!--<button type="button" class="w-100 btn btn-lg btn-outline-primary">Ver más</button> -->
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border-primary">
            <div class="card-header py-3 text-white bg-warning border-primary">
                <h4 class="my-0 fw-normal">Pólizas que vencen este mes</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title"><?= $vencidas ?></h1>
                <!-- <button type="button" class="w-100 btn btn-lg btn-outline-primary">Ver más</button> -->
            </div>
        </div>
    </div>

    <?php if (!empty($lista)) : ?>
        <div class="col">
            <div class="card mb-4 rounded-3 shadow-sm border-primary">
                <div class="card-header py-3">
                    <h4 class="my-0 fw-normal">Pólizas agrupadas por aseguradoras</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Aseguradora</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $aseguradora => $cantidad) : ?>
                                <tr>
                                    <td><?= $aseguradora ?></td>
                                    <td><?= $cantidad ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

<?= $this->endSection() ?>