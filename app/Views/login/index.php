<?= $this->extend('layouts/simple') ?>

<?= $this->section('content') ?>

<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <?php if (session()->getFlashdata('alerta')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashdata('alerta') ?>
                            </div>
                        <?php endif ?>

                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header d-flex justify-content-center">
                                <img src="<?= base_url('img/it.png') ?>" alt="Logo IT" width="200" height="200">
                            </div>

                            <div class="card-body">
                                <form method="POST" action="<?= site_url('/login') ?>">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="inputEmail" type="email" name="correo" />
                                        <label for="inputEmail">Usuario</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="inputPassword" type="password" name="pass" />
                                        <label for="inputPassword">Contraseña</label>
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <button type="submit" class="btn btn-primary">Ingresar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Grupo Nobe <?= date('Y') ?></div>
                </div>
            </div>
        </footer>
    </div>
</div>

<?= $this->endSection() ?>