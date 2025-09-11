<!DOCTYPE html>
<html>
<head>
  <title>Analisis y evaluación de riesgo</title>
  <style>
    @page {
      size: A4;
      margin: 0;
    }

    .logo_humanismo {
      width: 10cm; /* Ajusta el tamaño según sea necesario */
      height: auto;
    }

    header { 
      position: fixed;
      left: 0px;
      top: 55px;
      right: 0px;
      text-align: center;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: "Times News Roman", serif;
      font-size: 12pt;
    }

    body {
      background-image: url('img_portada/fondo_portada.png'); /* Imagen de fondo */
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
      width: 12cm; /* Ajusta el tamaño según sea necesario */
      height: auto;
      margin-top: 20px;
    }

    flooter {
      position: fixed;
      left: 0px;
      bottom: 0px;
      right: 30px;
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
  </header>
  <!-- Contenido -->
  <div class="contenido">
    <p>ANÁLISIS Y EVALUACIÓN DE RIESGOS</p>
    <p>{{$reports->nombre_empresa}}</p>
    <p>SECRETARÍA DE SEGURIDAD DEL PUEBLO</p>
    <p>SUBSECRETARÍA DE SERVICIOS ESTRATÉGICOS DE SEGURIDAD</p>
    <p>DIVISIÓN DE LA GUARDIA DE SERVICIOS</p>
    <img src="img_portada/CONGRESO.png" class="ilustrativa">
  </div>
  <!-- Pie de pagina -->
  <flooter>
    <p style="font-size: 10pt;">
      TUXTLA GUTIÉRREZ, CHIAPAS A 01 DE JUNIO DE 2025
    </p>
  </flooter>
</body>
</html>
