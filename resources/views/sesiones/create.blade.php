<x-base>
    <div class="row justify-content-center">
        <div class="col-lg-5">
            @if (session()->has('alerta'))
                <div class="alert alert-danger" role="alert">{{ session('alerta') }} </div>
            @endif
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header d-flex justify-content-center">
                    <img src="{{ asset('img/it.png') }}" alt="Logo IT" width="200" height="200">
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/login') }}">
                        @csrf
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
</x-base>
