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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <!-- Aqui se cargaran el css que se utilicen en las vistas -->
    <?= $this->renderSection('css') ?>
</head>

<body class="sb-nav-fixed">
    <!-- Aqui se cargara el navbar -->
    <?= $this->include('layouts/navbar') ?>

    <div id="layoutSidenav">
        <!-- Aqui se cargara el sidebar -->
        <?= $this->include('layouts/sidebar') ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- Titulo de cada vista -->
                    <h1 class="mt-4"><?= $titulo ?></h1>
                    <hr>

                    <!-- Alerta de las vistas -->
                    <?php if (session()->getFlashdata('alerta')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('alerta') ?>
                        </div>
                    <?php endif ?>

                    <!-- Aqui se cargara el contenido de las vistas -->
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; <?= date('Y') ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Aqui se cargaran los modal que se utilicen en las vistas -->
    <?= $this->renderSection('modal') ?>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('js/scripts.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="<?= base_url('js/datatables-simple-demo.js') ?>"></script>

    <!-- Aqui se cargaran el js que se utilicen en las vistas -->
    <?= $this->renderSection('js') ?>
</body>

</html>