<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card mb-3">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Cotización firmada</label>
            <input required type="file" name="cotizacion" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Aseguradora</label>
            <select class="form-select" name="aseguradora" required>
                <?php foreach ($cotizacion->getLineItems() as $lineItem) : ?>
                    <option value="<?= $lineItem->getId() ?>"><?= $lineItem->getDescription() ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
    </div>
</div>



<?= $this->endSection() ?>