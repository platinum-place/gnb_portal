<x-portal>
    <h1 class="mt-4">Buscar Emisión</h1>

    @if (session()->has('alerta'))
        <div class="alert alert-success" role="alert">{{ session('alerta') }} </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Pólizas Emitidas
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Cédula</th>
                        <th>Aseguradora</th>
                        <th>Plan</th>
                        <th>Suma Aseguradora</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Fecha</th>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Cédula</th>
                        <th>Aseguradora</th>
                        <th>Plan</th>
                        <th>Suma Aseguradora</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($emisiones as $emision)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($emision->getCreatedTime())) }}</td>
                            <td>{{ $emision->getFieldValue('TUA') }}</td>
                            <td>
                                {{ $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido') }}
                            </td>
                            <td>{{ $emision->getFieldValue('Identificaci_n') }}</td>
                            <td>{{ $emision->getFieldValue('Aseguradora')->getLookupLabel() }}</td>
                            <td>{{ $emision->getFieldValue('Plan') }}</td>
                            <td>RD${{ number_format($emision->getFieldValue('Suma_asegurada'), 2) }}</td>
                            <td>{{ $emision->getFieldValue('Stage') }}</td>
                            <td>
                                <a href="{{ url('/emision', ['id' => $emision->getEntityId()]) }}"
                                    target="_blank" title="Descargar emisión">
                                    <i class="fas fa-download"></i>
                                </a>
                                |
                                <a href="{{ url('/condicionado', ['id' => $emision->getFieldValue('Coberturas')->getEntityId()]) }}"
                                    title="Descargar documentos">
                                    <i class="fas fa-file-download"></i>
                                </a>
                                |
                                <a href="{{ url('/adjuntar', ['id' => $emision->getEntityId()]) }}"
                                    title="Adjuntar documentos">
                                    <i class="fas fa-upload"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-portal>
