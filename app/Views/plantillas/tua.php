<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

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
        <p style="text-align: right">
            <b>Fecha </b> <?= date('d/m/Y') ?> <br>
            <b>Estado </b> <?= $tua->getFieldValue("Stage") ?>
        </p>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h6>DETALLES INTERMEDIARIO</h6>
    <div class="row" style="font-size: small;">
        <div class="col-6">
            <table class="table table-sm table-bordered">
                <tbody>
                    <tr>
                        <th scope="col">Nombre</th>
                        <td><?= $tua->getFieldValue("Account_Name")->getLookupLabel() ?></td>
                    </tr>

                    <tr>
                        <th scope="col">RNC/Cédula</th>
                        <td><?= $corredor->getFieldValue("Identificaci_n") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Dirección</th>
                        <td><?= $corredor->getFieldValue("Billing_Street") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-6">
            <table class="table table-sm table-bordered">
                <tbody>
                    <tr>
                        <th scope="col">Código</th>
                        <td><?= $corredor->getFieldValue("Account_Number") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Contacto</th>
                        <td><?= ($tua->getFieldValue("Contact_Name")) ? $tua->getFieldValue("Contact_Name")->getLookupLabel() : ""; ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Teléfono</th>
                        <td><?= $corredor->getFieldValue("Phone") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h6>DETALLES TUA</h6>
    <div class="row" style="font-size: small;">
        <div class="col-6">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="col">Producto</th>
                        <td><?= $tua->getFieldValue("Type") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Vigencia Desde</th>
                        <td><?= date('d/m/Y', strtotime($tua->getFieldValue("Fecha_de_inicio"))) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-6">
            <table class="table table-sm table-bordered">
                <tbody>
                    <tr>
                        <th scope="col">Número TUA</th>
                        <td><?= $tua->getFieldValue("Deal_Name") ?></td>
                    </tr>

                    <tr>
                        <th scope="col">Vigencia Hasta</th>
                        <td><?= date('d/m/Y', strtotime($tua->getFieldValue("Closing_Date"))) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        &nbsp;
    </div>

    <h6>DETALLES BENEFICIARIO</h6>
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

    <div class="col-12">
        &nbsp;
    </div>

    <h6>VEHÍCULOS ACTIVOS</h6>
    <div class="col-12" style="font-size: small;">
        <table class="table table-sm table-bordered">
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
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                <?php $cont = 1 ?>
                <?php foreach ($vehiculos as $vehiculo) : ?>
                        <tr>
                            <td><?= $cont ?></td>
                            <td><?= $vehiculo->getFieldValue('Marca') ?></td>
                            <td><?= $vehiculo->getFieldValue('Modelo') ?></td>
                            <td><?= $vehiculo->getFieldValue('Tipo') ?></td>
                            <td><?= $vehiculo->getFieldValue('A_o') ?></td>
                            <td><?= $vehiculo->getFieldValue('Color') ?></td>
                            <td><?= $vehiculo->getFieldValue('Placa') ?></td>
                            <td><?= $vehiculo->getFieldValue('Name') ?></td>
                            <td><?= $vehiculo->getFieldValue('Estado') ?></td>
                        </tr>
                        <?php $cont++ ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<div class="col-12">
    &nbsp;
</div>

<h6>NOTA</h6>
<div class="card">
    <div class="card-body">
        <p class="card-text"><?= $tua->getFieldValue('Description') ?></p>
    </div>
</div>


<div class="row">
    <div <?= ($cont < 20) ? 'class="fixed-bottom"' : ""; ?>>
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
    @page {
        size: A3;
    }

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
    document.title = "REGISTRO TUA" + <?= $tua->getFieldValue('Deal_Name') ?>; // Cambiamos el título
    setTimeout(function() {
        window.print();
        window.close();
    }, 3000);
</script>
<?= $this->endSection() ?>