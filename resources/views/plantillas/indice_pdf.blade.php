<!DOCTYPE html>
<html>
<head>
  <title>Analisis y evaluación de riesgo</title>
  <style>
    @page {
      size: A4;
      margin: 0;
      counter-reset: page 2;
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

    footer {
      position: fixed;
      left: 0px;
      bottom: 0px;
      right: 30px;
      height: 50px;
      text-align: right;
      color: black; /* O el color que contraste con tu fondo */
    }

    footer .page:after {
        content: counter(page);
    }

    .page-content {
      page-break-after: always;
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
                <strong>ANÁLISIS Y EVALUACIÓN DE RIESGOS<br>DEL H. CONGRESO DE CHIAPAS</strong>
                </div>
            </td>
            <td style="border: 1px solid #aaa; padding: 4px;">
                <div style="opacity: 0.8;">
                <strong>CLASIFICACIÓN DEL DOCUMENTO</strong><br>
                <span style="color: #c44;"><strong>CONFIDENCIAL</strong></span>
                </div>
            </td>
            <td style="border: 1px solid #aaa; padding: 4px;">
                <div style="opacity: 0.8;">
                <strong>FECHA DE INICIO</strong><br>
                10/06/2025
                </div>
            </td>
            </tr>
        </table>
    </header>
  <!-- Contenido -->
  <div class="contenido">
    <h2 style="text-align: left; font-size: 16pt; margin-bottom: 20px;">ÍNDICE</h2>
    <table style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman', serif; font-size: 12pt;">
        <tr>
            <td style="padding: 8px; text-align: left;">1. Introducción</td>
            <td style="padding: 8px; text-align: right;">4</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">2. Objetivos</td>
            <td style="padding: 8px; text-align: right;">5</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">3. Alcance</td>
            <td style="padding: 8px; text-align: right;">6</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">4. Metodología</td>
            <td style="padding: 8px; text-align: right;">7</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">5. Identificación de Riesgos</td>
            <td style="padding: 8px; text-align: right;">10</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">6. Análisis de Riesgos</td>
            <td style="padding: 8px; text-align: right;">15</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">7. Evaluación de Riesgos</td>
            <td style="padding: 8px; text-align: right;">20</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">8. Plan de Tratamiento de Riesgos</td>
            <td style="padding: 8px; text-align: right;">25</td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;">9. Monitoreo y Revisión</td>
            <td style="padding: 8px; text-align: right;">30</td>
        </tr>
  </div>
  <!-- Pie de pagina -->
  <footer>
    <p style="font-size: 10pt;" class="page">
      Página 
    </p>
  </footer>
</body>
</html>
