<x-portal>
    <h1 class="mt-4">Reportes</h1>
    <hr>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            @if (session()->has('alerta'))
                <div class="alert alert-danger" role="alert">{{ session('alerta') }} </div>
            @endif
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Reporte de Pólizas emitidas</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= url('/reportes') ?>">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input type="date" class="form-control" id="desde" name="desde" required>
                                    <label for="desde">Desde</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="hasta" id="hasta" required>
                                    <label for="hasta">Hasta</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="" disabled selected>Tipo de reporte</option>
                                <option value="Auto">Auto</option>
                                <option value="Vida">Vida</option>
                                <option value="Desempleo">Vida/Desempleo</option>
                                <option value="Incendio">Incendio Hipotecario</option>
                            </select>
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid">
                                <button class="btn btn-primary btn-block" type="submit">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
</x-portal>
