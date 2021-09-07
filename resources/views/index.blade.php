<x-portal>
    <h1 class="mt-4">Panel de Control</h1>

    <div class="alert alert-success" role="alert">
        <h3 class="alert-heading">¡Bienvenido al Insurance Tech de Grupo Nobe!</h3>
        <p>Desde su panel de control podrás ver la infomación necesaria para manejar sus pólizas y cotizaciones.</p>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <p>Pólizas emitidas este mes</p>
                    <p>{{ $polizas }}</p>
                </div>
                <!--
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Ver más</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            -->
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <p>Pólizas que vencen este mes</p>
                    <p>{{ $vencidas }}</p>
                </div>
                <!--
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Ver más</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            -->
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Pólizas emitidas
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Aseguradora</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Aseguradora</th>
                        <th>Cantidad</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($lista as $aseguradora => $cantidad)
                        <tr>
                            <td><?= $aseguradora ?></td>
                            <td><?= $cantidad ?></td>
                        </tr>
                    @empty
                        No existen emisiones
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-portal>
