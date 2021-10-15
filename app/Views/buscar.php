<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cliente</th>
                    <th>RNC/Cédula</th>
                    <th>Codeudor</th>
                    <th>Plan</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cliente</th>
                    <th>RNC/Cédula</th>
                    <th>Codeudor</th>
                    <th>Plan</th>
                    <th>Referidor</th>
                    <th>Opciones</th>
                </tr>
            </tfoot>
            <tbody>
                <?= $this->include('layouts/lista_cotizaciones') ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Funcion para cargar una url con codigo php cuando hagan una solicitud con ajax -->
<script>
    function botonDescargar(val) {
        var boton = '<a target="__blank" href="<?= site_url("cotizaciones/condicionado/") ?>' + val.value + '" class="btn btn-secondary mb-3">Descargar</a>';
        document.getElementById("boton").innerHTML = boton;
    }
</script>
<?= $this->endSection() ?>