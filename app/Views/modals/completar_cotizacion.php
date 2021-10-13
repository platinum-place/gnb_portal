<form method="POST" action="<?= site_url("cotizaciones/completar") ?>">

    <div class="modal fade" id="completar_cotizacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="completar_cotizacion" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completar_cotizacion">Completar cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <h6>Datos del cliente</h6>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">RNC/Cédula</label>
                                <input type="text" class="form-control" name="rnc_cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <?php if (!$fecha_deudor) : ?>
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="fecha" id="fecha">
                                <?php endif ?>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" name="correo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Tel. Celular</label>
                                <input type="tel" class="form-control" name="telefono" required placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                            </div>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Tel. Residencial</label>
                                <input type="tel" class="form-control" name="tel_residencia" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">Tel. Trabajo</label>
                                <input type="tel" class="form-control" name="tel_trabajo" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                            </div>
                        </div>
                    </div>



                    <?php if ($direccion) : ?>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion">
                        </div>
                    <?php endif ?>



                    <?php if ($marcaid) : ?>
                        <h6>Datos del vehículo</h6>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Chasis</label>
                                    <input type="text" class="form-control" name="chasis" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Placa</label>
                                    <input type="text" class="form-control" name="placa">
                                </div>
                            </div>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" class="form-control" name="color">
                        </div>
                    <?php endif ?>

                    <!-- Formulario codeudor, en caso de plan vida -->
                    <?php if ($fecha_codeudor) : ?>
                        <h6>Datos del Codeudor</h6>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre_codeudor" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" name="apellido_codeudor" required>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">RNC/Cédula</label>
                                    <input type="text" class="form-control" name="rnc_cedula_codeudor" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="correo_codeudor">
                                </div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Tel. Celular</label>
                                    <input type="tel" class="form-control" name="telefono_codeudor" required placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Tel. Residencial</label>
                                    <input type="tel" class="form-control" name="tel_residencia_codeudor" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                                </div>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label">Tel. Trabajo</label>
                                    <input type="tel" class="form-control" name="tel_trabajo_codeudor" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                                </div>
                            </div>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion_codeudor">
                        </div>

                    <?php endif ?>

                    <!-- datos en general -->
                    <input type="text" hidden name="plan" value="<?= $plan ?>">
                    <input type="number" hidden name="suma" value="<?= $suma ?>">
                    <input type="text" hidden name="cotizaciones" value='<?= json_encode($cotizaciones)  ?>'>
                    <input type="text" hidden name="cuota" value="<?= $cuota ?>">
                    <input type="text" hidden name="plazo" value="<?= $plazo ?>">
                    <input type="text" hidden name="fecha_codeudor" value="<?= $fecha_codeudor ?>">
                    <input type="text" hidden name="fecha" value="<?= $fecha_deudor ?>">
                    <input type="text" hidden name="marcaid" value="<?= $marcaid ?>">
                    <input type="text" hidden name="uso" value="<?= $uso ?>">
                    <input type="text" hidden name="ano" value="<?= $ano ?>">
                    <input type="text" hidden name="modeloid" value="<?= $modeloid ?>">
                    <input type="text" hidden name="modelotipo" value="<?= $modelotipo ?>">
                    <input type="text" hidden name="estado" value="<?= $estado ?>">
                    <input type="text" hidden name="prestamo" value="<?= $prestamo ?>">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Completar</button>
                </div>
            </div>
        </div>
    </div>
</form>