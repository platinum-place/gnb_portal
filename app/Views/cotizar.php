<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!-- Opciones para cotizar -->
<div class="row row-cols-1 row-cols-md-4 g-4">
    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/auto.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="#" data-bs-toggle="modal" data-bs-target="#cotizar_auto"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/vida.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="#" data-bs-toggle="modal" data-bs-target="#cotizar_vida"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/desempleo.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="#" data-bs-toggle="modal" data-bs-target="#cotizar_desempleo"></a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="<?= base_url('img/incendio.png') ?>" class="card-img-top" height="350">
            <div class="card-body">
                <a class="stretched-link" href="#" data-bs-toggle="modal" data-bs-target="#cotizar_incendio"></a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>



<?= $this->section('modal') ?>
<?php if (!empty($cotizaciones)) : ?>
    <?= $this->include('modals/tabla_resultados') ?>
    <?= $this->include('modals/completar_cotizacion') ?>
<?php endif ?>

<!-- Formulario para cotizar -->
<?= $this->include('modals/cotizar_auto') ?>
<?= $this->include('modals/cotizar_vida') ?>
<?= $this->include('modals/cotizar_desempleo') ?>
<?= $this->include('modals/cotizar_incendio') ?>
<?= $this->endSection() ?>



<?= $this->section('js') ?>
<script>
    //representan los modals
    var tabla_resultados = new bootstrap.Modal(document.getElementById('tabla_resultados'), {});
    var completar_cotizacion = new bootstrap.Modal(document.getElementById('completar_cotizacion'), {});

    //mostrar los resultados
    tabla_resultados.show();

    //cerrar los resultados y mostrar el formulario
    function cerrar() {
        tabla_resultados.hide();
        completar_cotizacion.show();
    }


    //Funcion para cargar una url con codigo php cuando hagan una solicitud con ajax
    function modelosAJAX(val) {
        $.ajax({
            type: 'ajax',
            url: "<?= site_url('cotizaciones/lista_modelos') ?>",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            method: "POST",
            data: {
                marcaid: val.value
            },
            success: function(response) {
                //agrega el codigo php en el select
                document.getElementById("modelos").innerHTML = response;
                //refresca solo el select para actualizar la interfaz del select
                $('.selectpicker').selectpicker('refresh');
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
</script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<?= $this->endSection() ?>


<!-- CSS personalizado -->
<?= $this->section('css') ?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

<!-- hace que el rango de clic del campo de fecha sea mas grande -->
<style>
    #fecha::-webkit-calendar-picker-indicator {
        padding-left: 50%;
    }

    #deudor::-webkit-calendar-picker-indicator {
        padding-left: 50%;
    }

    #codeudor::-webkit-calendar-picker-indicator {
        padding-left: 50%;
    }
</style>
<?= $this->endSection() ?>