<div class="card mb-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Aseguradoras</th>
                        <th scope="col">Prima</th>
                        <th scope="col">Comentario</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- contador para saber si existen valores vacios, para no permitir continuar de ser el caso -->
                    <?php $cont = 0 ?>

                    <?php foreach ($cotizacion->planes as $plan) : ?>
                        <tr>
                            <td><?= $plan["aseguradora"] ?></td>
                            <td>RD$<?= number_format($plan["total"], 2) ?></td>
                            <td><?= $plan["comentario"] ?></td>
                        </tr>

                        <?php
                        if ($plan["total"] > 0) {
                            $cont++;
                        }
                        ?>
                    <?php endforeach ?>
                </tbody>
            </table>

            <?php if ($cont > 0) : ?>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completar">
                    Continuar
                </button>
            <?php endif ?>
        </div>
    </div>
</div>

<!-- Formularios a utilizar -->
<?= $this->section('modal') ?>

<!-- Modal para auto -->
<?= $this->include('cotizaciones/completar') ?>

<?= $this->endSection() ?>