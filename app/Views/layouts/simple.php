<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>IT - Insurance Tech</title>
    <link rel="icon" type="image/png" href="<?= base_url('img/favicon.png') ?>">
    <link href="<?= base_url('css/styles.css') ?>" rel="stylesheet" />
    <!-- Aqui se cargaran el css que se utilicen en las vistas -->
    <?= $this->renderSection('css') ?>
</head>

<body>
    <?= $this->renderSection('content') ?>

    <!-- Aqui se cargaran el js que se utilicen en las vistas -->
    <?= $this->renderSection('js') ?>
</body>

</html>