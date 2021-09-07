<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    <title>Resumen</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-2">
                <img src="{{ asset('img/aseguradoras/' . $emision->getFieldValue('Aseguradora')->getEntityId() . '.png') }}"
                    width="170" height="50">
            </div>

            <div class="col-8">
                <h5 class="text-center">CERTIFICADO <br> PLAN VIDA</h5>
            </div>

            <div class="col-2">
                <p class="small">
                    <b>Código: </b>{{ $emision->getFieldValue('TUA') }}<br>
                    <b>Desde:</b>{{ date('d/m/Y', strtotime($emision->getCreatedTime())) }} <br>
                    <b>Hasta:</b>{{ date('d/m/Y', strtotime($emision->getFieldValue('Closing_Date'))) }} <br>
                </p>
            </div>
        </div>

        <div class="col-12">
            &nbsp;
        </div>

        <div class="row border">
            <h5 class="d-flex justify-content-center bg-primary text-white">DEUDOR</h5>

            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td style="width: 20%"><b>Nombre</b></td>
                            <td>{{ $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%"><b>Cédula/RNC</b></td>
                            <td>{{ $emision->getFieldValue('Identificaci_n') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20%"><b>Email</b></td>
                            <td> {{ $emision->getFieldValue('Correo_electr_nico') }} </td>
                        </tr>
                        <tr>
                            <td style="width: 20%"><b>Dirección</b></td>
                            <td>{{ $emision->getFieldValue('Fecha_de_nacimiento') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td style="width: 50%"><b>Tel. Residencia</b></td>
                            <td>{{ $emision->getFieldValue('Tel_Residencia') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><b>Tel. Celular</b></td>
                            <td>{{ $emision->getFieldValue('Tel_Celular') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><b>Tel. Trabajo</b></td>
                            <td> {{ $emision->getFieldValue('Tel_Trabajo') }} </td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><b>Fecha de Nacimiento</b></td>
                            <td>{{ $emision->getFieldValue('Fecha_de_nacimiento') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if (!empty($emision->getFieldValue('Nombre_codeudor')))
            <div class="col-12">
                &nbsp;
            </div>

            <div class="row border">
                <h5 class="d-flex justify-content-center bg-primary text-white">CODEUDOR</h5>

                <div class="col-6">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td style="width: 20%"><b>Nombre</b></td>
                                <td>{{ $emision->getFieldValue('Nombre_codeudor') . ' ' . $emision->getFieldValue('Apellido_codeudor') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 20%"><b>Cédula/RNC</b></td>
                                <td>{{ $emision->getFieldValue('Identificaci_n_codeudor') }}</td>
                            </tr>
                            <tr>
                                <td style="width: 20%"><b>Email</b></td>
                                <td> {{ $emision->getFieldValue('Correo_electr_nico_codeudor') }} </td>
                            </tr>
                            <tr>
                                <td style="width: 20%"><b>Dirección</b></td>
                                <td>{{ $emision->getFieldValue('Fecha_de_nacimiento_codeudor') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-6">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td style="width: 50%"><b>Tel. Residencia</b></td>
                                <td>{{ $emision->getFieldValue('Tel_Residencia_codeudor') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%"><b>Tel. Celular</b></td>
                                <td>{{ $emision->getFieldValue('Tel_Celular_codeudor') }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%"><b>Tel. Trabajo</b></td>
                                <td> {{ $emision->getFieldValue('Tel_Trabajo_codeudor') }} </td>
                            </tr>
                            <tr>
                                <td style="width: 50%"><b>Fecha de Nacimiento</b></td>
                                <td>{{ $emision->getFieldValue('Fecha_de_nacimiento_codeudor') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="col-12">
            &nbsp;
        </div>

        <div class="row border">
            <h5 class="d-flex justify-content-center bg-primary text-white">COBERTURAS/PRIMA MENSUAL</h5>

            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <td style="width: 50%"><b>Suma Asegurada</b></td>
                        <td>RD${{ number_format($emision->getFieldValue('Suma_asegurada'), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%"><b>Plazo</b></td>
                        <td>{{ $emision->getFieldValue('Plazo') }} meses</td>
                    </tr>
                    <tr>
                        <td class="border-dark border-top" style="width: 50%"><b>Prima Neta</b></td>
                        <td class="border-dark border-top">
                            RD${{ number_format($emision->getFieldValue('Amount') - $emision->getFieldValue('Amount') * 0.16, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%"><b>ISC</b></td>
                        <td>RD${{ number_format($emision->getFieldValue('Amount') * 0.16, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%"><b>Prima Mensual</b></td>
                        <td>RD${{ number_format($emision->getFieldValue('Amount'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12">
            &nbsp;
        </div>

        <div class="row">
            <div class="col-6 border">
                <h6 class="text-center">REQUISITOS DEL DEUDOR</h6>
                @foreach ($requisitos as $requisito)
                    <ul>
                        <li>{{ $requisito }}</li>
                    </ul>
                @endforeach
            </div>

            @if (!empty($emision->getFieldValue('Nombre_codeudor')))
                <div class="col-6 border">
                    <h6 class="card-title text-center">REQUISITOS DEL CODEUDOR</h6>
                    @foreach ($corequisitos as $requisito)
                        <ul>
                            <li>{{ $requisito }}</li>
                        </ul>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        setTimeout(function() {
            window.print();
            window.close();
        }, 2000);
    </script>
</body>

</html>
