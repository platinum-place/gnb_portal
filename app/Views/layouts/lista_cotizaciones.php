<?php $cont = 1 ?>
<?php foreach ((array)$cotizaciones as $cotizacion) : ?>
    <?php if ($cotizacion->getFieldValue('Quote_Stage') == $filtro) : ?>
        <tr>
            <td><?= $cont ?></td>
            <td><?= date('d/m/Y', strtotime($cotizacion->getCreatedTime())) ?></td>
            <td><?= date('d/m/Y', strtotime($cotizacion->getFieldValue('Valid_Till'))) ?></td>
            <td>
                <?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?>
            </td>
            <td><?= $cotizacion->getFieldValue('RNC_C_dula') ?></td>
            <td><?= (!empty($cotizacion->getFieldValue('Nombre_codeudor'))) ? "Aplica" : "No aplica"; ?> </td>
            <td><?= $cotizacion->getFieldValue('Plan') ?> </td>
            <td><?= $cotizacion->getFieldValue('Contact_Name')->getLookupLabel() ?></td>
            <td>
                <?php if ($cotizacion->getFieldValue('Quote_Stage') == "Cotizando") : ?>
                    <a href="#" title="Emitir" data-bs-toggle="modal" data-bs-target="#emitir_cotizacion<?= $cont ?>">
                        <i class="far fa-user"></i>
                    </a>
                    |
                    <a href="#" title="Editar" data-bs-toggle="modal" data-bs-target="#editar_cotizacion<?= $cont ?>">
                        <i class="fas fa-edit"></i>
                    </a>
                    |
                    <a href="<?= site_url("plantillas/cotizacion/" . $cotizacion->getEntityId()) ?>" title="Descargar" target="__blank">
                        <i class="fas fa-download"></i>
                    </a>

                    <!-- Formulario para editar cotizacion -->
                    <form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/emitir/" . $cotizacion->getEntityId()) ?>">
                        <div class="modal fade" id="emitir_cotizacion<?= $cont ?>" tabindex="-1" aria-labelledby="emitir_cotizacion<?= $cont ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="emitir_cotizacion<?= $cont ?>">
                                            Emitir cotización, a nombre de <?= $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido') ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Aseguradora</label>
                                                    <select class="form-select" name="planid" required onchange="botonDescargar(this)">
                                                        <option value="" selected disabled>Selecciona una aseguradora</option>
                                                        <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
                                                            <?php if ($lineItem->getNetTotal() > 0) : ?>
                                                                <option value="<?= $lineItem->getProduct()->getEntityId() ?>"><?= $lineItem->getProduct()->getLookupLabel() ?></option>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Condicionado</label>
                                                    <div id="boton"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Adjuntar documentos</label>
                                                    <input required multiple type="file" name="documentos[]" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3 mb-md-0">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="acuerdo" required>
                                                        <label class="form-check-label" for="exampleCheck1">
                                                            Estoy de acuerdo que quiero emitir la cotización no. <b><?= $cotizacion->getFieldValue('Quote_Number') ?></b>
                                                            , a nombre de <b><?= $cotizacion->getFieldValue('Nombre') . ' ' . $cotizacion->getFieldValue('Apellido') ?></b>,
                                                            RNC/Cédula <b><?= $cotizacion->getFieldValue('RNC_C_dula') ?></b> .
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Formulario para editar cotizacion -->
                    <form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/editar/" . $cotizacion->getEntityId()) ?>">
                        <div class="modal fade" id="editar_cotizacion<?= $cont ?>" tabindex="-1" aria-labelledby="editar_cotizacion<?= $cont ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editar_cotizacion<?= $cont ?>">
                                            Editar cotización, a nombre de <?= $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido') ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <h6>Datos del cliente</h6>
                                        <hr>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" name="nombre" value="<?= $cotizacion->getFieldValue("Nombre") ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Apellido</label>
                                                    <input type="text" class="form-control" name="apellido" value="<?= $cotizacion->getFieldValue("Apellido") ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">RNC/Cédula</label>
                                                    <input type="text" class="form-control" name="rnc_cedula" value="<?= $cotizacion->getFieldValue("RNC_C_dula") ?>">
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
                                                    <?php if (empty($cotizacion->getFieldValue("Fecha_de_nacimiento"))) : ?>
                                                        <label class="form-label">Fecha de Nacimiento</label>
                                                        <input type="date" class="form-control" name="fecha" value="<?= $cotizacion->getFieldValue("Fecha_de_nacimiento") ?>">
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" class="form-control" name="direccion" value="<?= $cotizacion->getFieldValue("Direcci_n") ?>">
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Tel. Celular</label>
                                                    <input type="tel" class="form-control" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Celular") ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Tel. Residencial</label>
                                                    <input type="tel" class="form-control" name="tel_residencia" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Residencia") ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3 mb-md-0">
                                                    <label class="form-label">Tel. Trabajo</label>
                                                    <input type="tel" class="form-control" name="tel_trabajo" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Trabajo") ?>">
                                                </div>
                                            </div>
                                        </div>


                                        <?php if (!empty($cotizacion->getFieldValue("Nombre_codeudor"))) : ?>
                                            <br>
                                            <h6>Datos del Codeudor</h6>
                                            <hr>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" name="nombre" value="<?= $cotizacion->getFieldValue("Nombre_codeudor") ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Apellido</label>
                                                        <input type="text" class="form-control" name="apellido" value="<?= $cotizacion->getFieldValue("Apellido_codeudor") ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">RNC/Cédula</label>
                                                        <input type="text" class="form-control" name="rnc_cedula" value="<?= $cotizacion->getFieldValue("RNC_C_dula_codeudor") ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Dirección</label>
                                                        <input type="text" class="form-control" name="direccion" value="<?= $cotizacion->getFieldValue("Direcci_n_codeudor") ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Correo Electrónico</label>
                                                        <input type="email" class="form-control" name="correo" value="<?= $cotizacion->getFieldValue("Correo_electr_nico_codeudor") ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Tel. Trabajo</label>
                                                        <input type="tel" class="form-control" name="tel_trabajo" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Trabajo_codeudor") ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Tel. Celular</label>
                                                        <input type="tel" class="form-control" name="telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Celular_codeudor") ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label">Tel. Residencial</label>
                                                        <input type="tel" class="form-control" name="tel_residencia" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $cotizacion->getFieldValue("Tel_Residencia_codeudor") ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif ?>


                                        <?php if (!empty($cotizacion->getFieldValue("Marca"))) : ?>
                                            <br>
                                            <h6>Datos del vehículo</h6>
                                            <hr>
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
                                        <?php endif ?>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <a href="#" title="Adjuntar" data-bs-toggle="modal" data-bs-target="#adjuntar_documentos<?= $cont ?>">
                        <i class="fas fa-upload"></i>
                    </a>
                    |
                    <a href="<?= site_url("plantillas/emision/" . $cotizacion->getEntityId()) ?>" title="Descargar" target="__blank">
                        <i class="fas fa-download"></i>
                    </a>

                    <!-- Formulario para adjuntar documentos -->
                    <form enctype="multipart/form-data" method="POST" action="<?= site_url("cotizaciones/adjuntar/" . $cotizacion->getEntityId()) ?>">
                        <div class="modal fade" id="adjuntar_documentos<?= $cont ?>" tabindex="-1" aria-labelledby="adjuntar_documentos<?= $cont ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="adjuntar_documentos<?= $cont ?>">
                                            Adjuntar documentos a emisión, a nombre de <?= $cotizacion->getFieldValue('Nombre') . " " . $cotizacion->getFieldValue('Apellido') ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Adjuntar documentos</label>
                                            <input required type="file" name="documentos[]" multiple class="form-control">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif ?>
            </td>
        </tr>
        <?php $cont++ ?>
    <?php endif ?>
<?php endforeach ?>