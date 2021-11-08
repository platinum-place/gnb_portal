<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/editar/" . $cotizacion->getEntityId()) ?>">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    Datos del cliente
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?= $cotizacion->getFieldValue("Nombre") ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="apellido" value="<?= $cotizacion->getFieldValue("Apellido") ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">RNC/Cédula</label>
                                <input type="text" class="form-control" name="rnc_cedula" value="<?= $cotizacion->getFieldValue("RNC_C_dula") ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <?php if (empty($cotizacion->getFieldValue("Fecha_de_nacimiento"))) : ?>
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="fecha" value="<?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>">
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="correo" value="<?= $cotizacion->getFieldValue("Correo_electr_nico") ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Tel. Celular</label>
                                <input type="tel" class="form-control" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Celular") ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" value="<?= $cotizacion->getFieldValue("Direcci_n") ?>">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Tel. Residencial</label>
                                <input type="tel" class="form-control" name="tel_residencia" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Residencia") ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Tel. Trabajo</label>
                                <input type="tel" class="form-control" name="tel_trabajo" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Trabajo") ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) : ?>
                <div class="card mb-3">
                    <div class="card-header">
                        Datos del Codeudor
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" value="<?= $cotizacion->getFieldValue("Nombre_codeudor") ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" value="<?= $cotizacion->getFieldValue("Apellido_codeudor") ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">RNC/Cédula</label>
                                    <input type="text" class="form-control" name="rnc_cedula" value="<?= $cotizacion->getFieldValue("RNC_C_dula_codeudor") ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="correo" value="<?= $cotizacion->getFieldValue("Correo_electr_nico_codeudor") ?>">
                                </div>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" value="<?= $cotizacion->getFieldValue("Direcci_n_codeudor") ?>">
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Tel. Celular</label>
                                    <input type="tel" class="form-control" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Celular_codeudor") ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Tel. Residencial</label>
                                    <input type="tel" class="form-control" name="tel_residencia" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Residencia_codeudor") ?>">
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Tel. Trabajo</label>
                                    <input type="tel" class="form-control" name="tel_trabajo" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Trabajo_codeudor") ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if (!empty($cotizacion->getFieldValue("Marca"))) : ?>
                <div class="card">
                    <div class="card-header">
                        Datos del vehículo
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Chasis</label>
                                    <input type="text" class="form-control" name="chasis" value="<?= $cotizacion->getFieldValue("Chasis") ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Placa</label>
                                    <input type="text" class="form-control" name="placa" value="<?= $cotizacion->getFieldValue("Placa") ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Color</label>
                                    <input type="text" class="form-control" name="color" value="<?= $cotizacion->getFieldValue("Color") ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>


            <div class="mt-4 mb-0">
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-block">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>