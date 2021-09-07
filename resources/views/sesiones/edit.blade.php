<x-portal>
    <h1 class="mt-4">Editar usuario</h1>
    <hr>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            @if (session()->has('alerta'))
                <div class="alert alert-danger" role="alert">{{ session('alerta') }} </div>
            @endif
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Cambiar Contraseña</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/editar') }}">
                        @csrf
                        <div class="form-floating mb-3 mb-md-0">
                            <input class="form-control" id="inputPassword" type="password" name="pass"
                                placeholder="Nueva Contraseña" />
                            <label for="inputPassword">Nueva Contraseña</label>
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid">
                                <button class="btn btn-primary btn-block" type="submit">Actualizar</button>
                            </div>
                        </div>
                        <div class="card-footer text-center py-3">
                            &nbsp;
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-portal>
