<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.87.0">
    <title>IT - Insurance Tech</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url("assets/dist/css/bootstrap.min.css") ?>" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="<?= base_url("css/dashboard.css") ?>" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-primary flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?= site_url() ?>"><?= session("usuario")->getFieldValue("Account_Name")->getLookupLabel() ?></a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="<?= site_url("login/salir") ?>" onclick="return confirm('¿Estas seguro de salir?')">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <img src="<?= base_url("img/it.png") ?>" alt="150" width="150">
                            </a>
                        </li>

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Herramientas</span>
                        </h6>

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?= site_url() ?>">
                                <span data-feather="home"></span>
                                Panel de control
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url("emisiones") ?>">
                                <span data-feather="search"></span>
                                Buscar emisión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url("cotizaciones") ?>">
                                <span data-feather="file-text"></span>
                                Cotizaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url("login/editar") ?>">
                                <span data-feather="users"></span>
                                Cambiar contraseña
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url("reportes") ?>">
                                <span data-feather="bar-chart-2"></span>
                                Reportes de emisiones
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= base_url("assets/dist/js/bootstrap.bundle.min.js") ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script src="<?= base_url("js/dashboard.js") ?>"></script>
    <script src="<?= base_url("js/form-validation.js") ?>"></script>

</body>

</html>