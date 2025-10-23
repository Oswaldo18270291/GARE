<!DOCTYPE html>
<html>
<head>
  <title>Analisis y evaluación de riesgo</title>
  <style>
    @page {
      size: A4;
      margin: 0;
    }

    header { 
      position: fixed;
      left: 20px;
      right: 20px;
      top: 55px;
      text-align: center;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Arial Nova Light', Arial, sans-serif;
      font-size: 14pt;
    }

    body {
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      box-sizing: border-box;
      text-align: center;
      font-family: 'Arial Nova Light', Arial, sans-serif;
    }

    .contenido {
      text-align: center;
      z-index: 1;
      padding: 2.5cm; /* Espaciado interno */
      color: black; /* O el color que contraste con tu fondo */
      font-size: 12pt;
      margin-top: 50px;
    }
    @font-face {
        font-family: 'Arial Nova Light';
        src: url('/fonts/ArialNova-Light.ttf') format('truetype');
        font-weight: 300;
        font-style: normal;
    }

    .humanismo { 
      height: 6cm;
    }

    .estrella {
      height: 7cm;
    }

  </style>
</head>
<body>
  <!-- Encabezado -->
    <header>
        <table style="width: 100%; border-collapse: collapse; font-size: 9pt; text-align: center; color: #555;">
            <tr>
            <td style="border: 1px solid #aaa; padding: 4px;">
                <div style="opacity: 0.8;">
                <strong>ANÁLISIS Y EVALUACIÓN DE RIESGOS<br>DEL {{$reports->nombre_empresa}}</strong>
                </div>
            </td>
            <td style="border: 1px solid #aaa; padding: 4px;">
                <div style="opacity: 0.8;">
                <strong>CLASIFICACIÓN DEL DOCUMENTO</strong><br>
                @if ($reports->clasificacion=='Público')
                    <span style="color: rgb(17, 28, 180);"><strong>PÚBLICO</strong></span>
                @else
                    <span style="color: #c44;"><strong>CONFIDENCIAL</strong></span>
                @endif
                </div>
            </td>
            <td style="border: 1px solid #aaa; padding: 4px;">
                <div style="opacity: 0.8;">
                <strong>FECHA DE INICIO</strong><br>
                {{$reports->fecha_analisis}}
                </div>
            </td>
            </tr>
        </table>
    </header>
  <!-- Contenido -->
  <div class="contenido">
    <p>Informe de evaluación</p>
    <p>SECRETARIA DE SEGURIDAD DEL PUEBLO DE CHIAPAS</p>
    <div class="flex flex-col items-center w-full max-w-xs">
        <div>
            <img class="humanismo" style="display: block; margin: 0 auto;" src="img_portada/SSP_portada.png">
        </div>
        <div>
            <img class="estrella" style="display: block; margin: 0 auto;" src="img_portada/SSP_ESTRELLA.jpg">
        </div>
    </div>
    <p>Secretario de Seguridad</p>
    <p>{{auth()->user()->name}}</p>
    <p>{{$reports->colaborador1}}</p>
    
  </div>
</body>
</html>