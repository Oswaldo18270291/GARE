<!DOCTYPE html>
<html>
<head>
@php
    $path = public_path($reports->img_portada);
@endphp
  <title>Analisis y evaluación de riesgo</title>
  <style>
    @page {
      size: A4;
      margin: 0;
    }

    .logo_humanismo {
      width: auto; /* Ajusta el tamaño según sea necesario */
      height: 2cm;
      position: absolute;
      left: 50px;
    }

    .logo_empresa {
      width: auto; /* Ajusta el tamaño según sea necesario */
      height: 2cm;
      position: absolute;
      right: 50px;
    }

    header { 
      position: fixed;
      left: 0px;
      top: 55px;
      right: 0px;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: "Times News Roman", serif;
      font-size: 12pt;
    }


    body {
      background-image: url('file://{{ $path }}');      
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      box-sizing: border-box;
      text-align: center;
    }

    .contenido {
      text-align: center;
      z-index: 1;
      padding: 2.5cm; /* Espaciado interno */
      color: black; /* O el color que contraste con tu fondo */
      font-size: 18pt;
      margin-top: 100px;
    }

    .ilustrativa {
      margin-top: 20px;
      max-width: 100%; 
      max-height: 10cm;
    }

    footer {
      position: fixed;
      left: 0px;
      bottom: 22px;
      right: 40px;
      height: 50px;
      text-align: right;
      color: black; /* O el color que contraste con tu fondo */
    }
  </style>
</head>
<body>
  <!-- Encabezado -->
  <header>
    <img src="img_portada/SSP.png" class="logo_humanismo">
    <img src="storage/{{ $reports->logo }}" class="logo_empresa" />
  </header>
  <!-- Contenido -->
  <div class="contenido">
    <p>ANÁLISIS Y EVALUACIÓN DE RIESGOS</p>
    <p>{{$reports->nombre_empresa}}</p>
    <p>SECRETARÍA DE SEGURIDAD DEL PUEBLO</p>
    <p>SUBSECRETARÍA DE SERVICIOS ESTRATÉGICOS DE SEGURIDAD</p>
    <p>DIVISIÓN DE LA GUARDIA DE SERVICIOS</p>
    <img src="storage/{{ $reports->img }}" class="ilustrativa" />
  </div>
  <!-- Pie de pagina -->
  @php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es_ES.UTF-8'); // Para que los meses salgan en español
    \Carbon\Carbon::setLocale('es');
  @endphp
  <footer>
    <p>TUXTLA GUTIÉRREZ, CHIAPAS A {{ Str::upper(Carbon::parse($reports->fecha_analisis)->translatedFormat('d \d\e F \d\e Y')) }}</p>
  </footer>
</body>
</html>
