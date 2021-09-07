<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    <title>Resumen</title>
    <style>
        @page {
            size: A4;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-2">
                <img src="{{ asset('img/aseguradoras/' . $emision->getFieldValue('Aseguradora')->getEntityId() . '.png') }}"
                    width="170" height="50">
            </div>

            <div class="col-8">
                <h5 class="text-center">RESUMEN <br> SEGURO INCENDIO HIPOTECARIO</h5>
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

        <h5 class="d-flex justify-content-center bg-primary text-white">DATOS DEL CLIENTE</h5>
        <div class="row">
            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td style="width: 30%"><b>Nombre</b></td>
                            <td> {{ $emision->getFieldValue('Nombre') . ' ' . $emision->getFieldValue('Apellido') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Cédula/RNC</b></td>
                            <td>{{ $emision->getFieldValue('Identificaci_n') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Email</b></td>
                            <td>{{ $emision->getFieldValue('Correo_electr_nico') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><b>Fecha de Nacimiento</b></td>
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
                            <td> {{ $emision->getFieldValue('Tel_Residencia') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><b>Tel. Celular</b></td>
                            <td>{{ $emision->getFieldValue('Tel_Celular') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><b>Tel. Trabajo</b></td>
                            <td>{{ $emision->getFieldValue('Tel_Trabajo') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12">
            &nbsp;
        </div>

        <h5 class="card-title d-flex justify-content-center bg-primary text-white">COBERTURAS/PRIMA MENSUAL</h5>
        <table class="table table-borderless border table-sm">
            <tbody>
                <tr>
                    <td style="width: 50%"><b>Valor de la propiedad</b></td>
                    <td>RD${{ number_format($emision->getFieldValue('Suma_asegurada'), 2) }}</td>
                </tr>
                <tr>
                    <td style="width: 50%"><b>Valor del Préstamo</b></td>
                    <td>RD${{ number_format($emision->getFieldValue('Prestamo'), 2) }}</td>
                </tr>
                <tr>
                    <td style="width: 50%"><b>Plazo</b></td>
                    <td>{{ $emision->getFieldValue('Plazo') }} meses</td>
                </tr>
                <tr>
                    <td style="width: 50%"><b>Dirección</b></td>
                    <td>{{ $emision->getFieldValue('Direcci_n') }}</td>
                </tr>
                <tr>
                    <td style="width: 50%"><b>Tipo de Construcción</b></td>
                    <td>{{ $emision->getFieldValue('Tipo_de_Construcci_n') }}</td>
                </tr>
                <tr>
                    <td style="width: 50%"><b>Tipo de Riesgo</b></td>
                    <td>{{ $emision->getFieldValue('Tipo_de_Riesgo') }}</td>
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

    <script>
        setTimeout(function() {
            window.print();
            window.close();
        }, 2000);
    </script>
</body>

</html>
