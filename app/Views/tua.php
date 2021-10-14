<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<div class="row">
    <!-- encabezado -->
    <div class="row">
        <div class="col-4">
            <img src="<?= base_url("img/tua.png") ?>" width="200" height="200">
        </div>

        <div class="col-4">
            <h4 class="text-center text-uppercase">
                REGISTRO TUA ASISTENCIA
            </h4>
        </div>

        <div class="col-4">
            &nbsp;
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h5>DATOS CLIENTE</h5>
    <div class="card-group" style="font-size: small;">
        <div class="card border-0">
            <div class="card-body">
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
        </div>

        <div class="card border-0">
            <div class="card-body">
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
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h5>DATOS INTERMEDIARIO</h5>
    <div class="card-group" style="font-size: small;">
        <div class="card border-0">
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Nombre</th>
                            <td><?= $tua->getFieldValue("Account_Name")->getLookupLabel() ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body">
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
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h5>DATOS TUA</h5>
    <div class="card-group" style="font-size: small;">
        <div class="card border-0">
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Vigencia Desde</th>
                            <td><?= $tua->getFieldValue("Fecha_de_inicio") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Creado por</th>
                            <td><?= $creado_por->getName() ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Número</th>
                            <td><?= $tua->getFieldValue("TUA") ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Vigencia Hasta</th>
                            <td><?= $tua->getFieldValue("Closing_Date") ?></td>
                        </tr>

                        <tr>
                            <th scope="col">Hora de creación</th>
                            <td><?= $tua->getCreatedTime(); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h5>VEHÍCULOS</h5>
    <table class="table table-bordered">
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

    <div class="col-12">
        &nbsp;
    </div>

    <?php if ($cont > 11) : ?>
        <div class="saltopagina"></div>
    <?php endif ?>

    <h5>NOTA</h5>
    <div class="card">
        <div class="card-body">
            <p class="card-text"><?= $tua->getFieldValue('Description') ?></p>
        </div>
    </div>

    <div class="divFooter">
        <p class="text-center">
            Ave. Gustavo Mejía Ricart esq. Abrahm Lincoln, Torre Piantini, Suite 14-A, <br>
            Ens. Piantini, Santo Domingo, República Dominicana <br>
            www.gruponobe.com | RNC: 131057251
        </p>
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

    @media screen {
        div.divFooter {
            display: none;
        }
    }

    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        }
    }

    @page {
        size: A3;
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