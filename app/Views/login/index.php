<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.87.0">
    <title>IT - Insurance Tech</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

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
    <link href="<?= base_url("css/signin.css") ?>" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin">
        <?php if (session()->getFlashdata('alerta')) : ?>
            <div class="alert alert-info" role="alert">
                <?= session()->getFlashdata('alerta') ?>
            </div>
        <?php endif ?>

        <form method="POST" action="<?= site_url("login") ?>">
            <img class="mb-4" src="<?= base_url("img/it.png") ?>" alt="" width="150" height="150">
            <h1 class="h3 mb-3 fw-normal">Iniciar sesión</h1>

            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" placeholder="Ingresar correo electrónico" name="user" required>
                <label for="floatingInput">Usuario</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Ingresar contraseña" name="pass" required>
                <label for="floatingPassword">Contraseña</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>
            <p class="mt-5 mb-3 text-muted">Grupo Nobe &copy;<?= date("Y") ?></p>
        </form>
    </main>
</body>

</html>