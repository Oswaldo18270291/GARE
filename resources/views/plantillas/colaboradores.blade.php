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
      font-family: "Times News Roman", serif;
      font-size: 12pt;
    }

    body {
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
      font-size: 12pt;
      margin-top: 100px;
    }
  </style>
</head>
<body>
  <!-- Encabezado -->
    <header>
        <table style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman', serif; font-size: 9pt; text-align: center; color: #555;">
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
    <p>{{auth()->user()->name}}</p>
    <p>{{$reports->colaborador1}}</p>
    <br><br><br><br><br><br><br><br>
    <p>INFORME DE EVALUACIÓN</p>
    <br>
    <p>SECRETARIA DE SEGURIDAD DEL PUEBLO DE CHIAPAS</p>
    <p>ANÁLISIS DE RIESGO DEL {{$reports->nombre_empresa}}</p>
  </div>
</body>
</html>