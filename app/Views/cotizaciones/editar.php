<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/editar/" . $cotizacion->getEntityId()) ?>">
                    <h6>Datos del cliente</h6>
                    <hr>
                    <!-- datos del cliente -->
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?= $cotizacion->getFieldValue("Nombre") ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" name="apellido" value="<?= $cotizacion->getFieldValue("Apellido") ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">RNC/Cédula</label>
                        <input type="text" class="form-control" name="rnc_cedula" value="<?= $cotizacion->getFieldValue("RNC_C_dula") ?>">
                    </div>

                    <?php if (empty($cotizacion->getFieldValue("Fecha_de_nacimiento"))) : ?>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="fecha" value="<?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>">
                        </div>
                    <?php endif ?>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo" value="<?= $cotizacion->getFieldValue("Correo_electr_nico") ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" value="<?= $cotizacion->getFieldValue("Direcci_n") ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tel. Celular</label>
                        <input type="tel" class="form-control" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Celular") ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tel. Residencial</label>
                        <input type="tel" class="form-control" name="tel_residencia" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Residencia") ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tel. Trabajo</label>
                        <input type="tel" class="form-control" name="tel_trabajo" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Trabajo") ?>">
                    </div>

                    <!-- Formulario codeudor, en caso de plan vida -->
                    <?php if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) : ?>
                        <h6>Datos del Codeudor</h6>
                        <hr>
                        <!-- datos del codeudor -->
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?= $cotizacion->getFieldValue("Nombre_codeudor") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" value="<?= $cotizacion->getFieldValue("Apellido_codeudor") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RNC/Cédula</label>
                            <input type="text" class="form-control" name="rnc_cedula" value="<?= $cotizacion->getFieldValue("RNC_C_dula_codeudor") ?>">
                        </div>

                        <?php if (empty($cotizacion->getFieldValue("Fecha_de_nacimiento"))) : ?>
                            <div class="mb-3">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="fecha" value="<?= $cotizacion->getFieldValue("Fecha_de_nacimiento_codeudor") ?>">
                            </div>
                        <?php endif ?>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo" value="<?= $cotizacion->getFieldValue("Correo_electr_nico_codeudor") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" value="<?= $cotizacion->getFieldValue("Direcci_n_codeudor") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tel. Celular</label>
                            <input type="tel" class="form-control" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Celular_codeudor") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tel. Residencial</label>
                            <input type="tel" class="form-control" name="tel_residencia" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Residencia_codeudor") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tel. Trabajo</label>
                            <input type="tel" class="form-control" name="tel_trabajo" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Trabajo_codeudor") ?>">
                        </div>
                    <?php endif ?>

                    <?php if (!empty($cotizacion->getFieldValue("Marca"))) : ?>
                        <h6>Datos del vehículo</h6>
                        <hr>
                        <!-- datos del vehiculo -->
                        <div class="mb-3">
                            <label class="form-label">Chasis</label>
                            <input type="text" class="form-control" name="chasis" value="<?= $cotizacion->getFieldValue("Chasis") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Placa</label>
                            <input type="text" class="form-control" name="placa" value="<?= $cotizacion->getFieldValue("Placa") ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" class="form-control" name="color" value="<?= $cotizacion->getFieldValue("Color") ?>">
                        </div>
                    <?php endif ?>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>