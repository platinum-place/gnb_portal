<div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" class="form-control" name="nombre" required>
</div>

<div class="mb-3">
    <label class="form-label">Apellido</label>
    <input type="text" class="form-control" name="apellido" required>
</div>

<div class="mb-3">
    <label class="form-label">RNC/Cédula</label>
    <input type="text" class="form-control" name="rnc_cedula" required>
</div>

<?php if (empty($cotizacion->fecha_deudor)) : ?>
    <div class="mb-3">
        <label class="form-label">Fecha de Nacimiento</label>
        <input type="date" class="form-control" name="fecha">
    </div>
<?php else : ?>
    <input type="date" hidden name="fecha" value="<?= $cotizacion->fecha_deudor ?>">
<?php endif ?>

<div class="mb-3">
    <label class="form-label">Correo Electrónico</label>
    <input type="email" class="form-control" name="correo">
</div>

<div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" class="form-control" name="direccion" value="<?= (!empty($cotizacion->direccion)) ? $cotizacion->direccion : ""; ?>">
</div>

<div class="mb-3">
    <label class="form-label">Tel. Celular</label>
    <input type="tel" class="form-control" name="telefono" required placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
</div>

<div class="mb-3">
    <label class="form-label">Tel. Residencial</label>
    <input type="tel" class="form-control" name="tel_residencia" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
</div>

<div class="mb-3">
    <label class="form-label">Tel. Trabajo</label>
    <input type="tel" class="form-control" name="tel_trabajo" placeholder="809-457-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
</div>