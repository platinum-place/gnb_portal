<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-4">
            <img src="<?= base_url("img/tua.png") ?>" width="150" height="150">
        </div>

        <div class="col-4">
            <h5 class="text-center text-uppercase">
                REGISTRO <br> TU ASISTENCIA
            </h5>
        </div>

        <div class="col-4">
            &nbsp;
        </div>

        <h6>DATOS TUA</h6>
        <div class="row" style="font-size: small;">
            <div class="col-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Producto</th>
                            <td><?= $tua->getFieldValue("Type") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Vigencia Desde</th>
                            <td><?= date('d/m/Y', strtotime($tua->getFieldValue("Fecha_de_inicio"))) ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Creado por</th>
                            <td><?= $creado_por->getName() ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Número TUA</th>
                            <td><?= $tua->getFieldValue("TUA") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Vigencia Hasta</th>
                            <td><?= date('d/m/Y', strtotime($tua->getFieldValue("Closing_Date"))) ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Hora de creación</th>
                            <td><?= date('d/m/Y h:i:s A', strtotime($tua->getCreatedTime())) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h6>DATOS CLIENTE</h6>
        <div class="row" style="font-size: small;">
            <div class="col-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Nombre</th>
                            <td><?= $cliente->getFieldValue("First_Name") . " " . $cliente->getFieldValue("Last_Name") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">RNC/Cédula</th>
                            <td><?= $cliente->getFieldValue("RNC_C_dula") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Fecha de Nacimiento</th>
                            <td><?= $cliente->getFieldValue("Fecha_de_nacimiento") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Tel.</th>
                            <td><?= $cliente->getFieldValue("Phone") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Email</th>
                            <td><?= $cliente->getFieldValue("Email") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Dirección</th>
                            <td><?= $cliente->getFieldValue("Street") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h6>DATOS INTERMEDIARIO</h6>
        <div class="row" style="font-size: small;">
            <div class="col-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Nombre</th>
                            <td><?= $tua->getFieldValue("Account_Name")->getLookupLabel() ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Contacto</th>
                            <td><?= ($tua->getFieldValue("Contact_Name")) ? $tua->getFieldValue("Contact_Name")->getLookupLabel() : ""; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h6>VEHÍCULOS</h6>
        <div class="col-12">
            <table class="table table-sm table-bordered" style="font-size: small;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Placa</th>
                        <th>Chasis</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $cont = 1 ?>
                    <?php foreach ((array)$vehiculos as $vehiculo) : ?>
                        <tr>
                            <td><?= $cont ?></td>
                            <td><?= $vehiculo->getFieldValue('Marca') ?></td>
                            <td><?= $vehiculo->getFieldValue('Modelo') ?></td>
                            <td><?= $vehiculo->getFieldValue('Tipo') ?></td>
                            <td><?= $vehiculo->getFieldValue('A_o') ?></td>
                            <td><?= $vehiculo->getFieldValue('Color') ?></td>
                            <td><?= $vehiculo->getFieldValue('Placa') ?></td>
                            <td><?= $vehiculo->getFieldValue('Name') ?></td>
                        </tr>
                        <?php $cont++ ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <h6>NOTA</h6>
    <div class="card">
        <div class="card-body">
            <p class="card-text"><?= $tua->getFieldValue('Description') ?></p>
        </div>
    </div>


    <div class="row">
        <div <?= ($cont < 8) ? 'class="fixed-bottom"' : ""; ?>>
            <p class="text-center">
                Ave. Gustavo Mejía Ricart esq. Abrahm Lincoln, Torre Piantini, Suite 14-A, <br>
                Ens. Piantini, Santo Domingo, República Dominicana <br>
                www.gruponobe.com | RNC: 131057251
            </p>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Tamaño ideal para la plantilla -->
<style>
    @media all {
        div.saltopagina {
            display: none;
        }
    }

    @media print {
        div.saltopagina {
            display: block;
            page-break-before: always;
        }
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('js') ?>
<!-- Tiempo para que la pagina se imprima y luego se cierre -->
<script>
    document.title = "REGISTRO TUA" + <?= $tua->getFieldValue('TUA') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>