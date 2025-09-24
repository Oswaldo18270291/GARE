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
      top: 55px; 
      left: 30px; 
      right: 30px;
      height: 80px; /* define altura fija del header */
      text-align: center;
    }

    html, body {
      margin: 0;
      font-family: "Times News Roman", serif;
      font-size: 12pt;
    }

    body {
      margin-top: 120px; /* mismo alto aproximado que ocupa tu header */
      margin-left: 30px;
      margin-right: 30px;
      margin-bottom: 40px;
    }

    .contenido {
      text-align: center;
      z-index: 1;
      padding: 2.5cm; /* Espaciado interno */
      color: black; /* O el color que contraste con tu fondo */
      font-size: 12pt;
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
                <strong>ANÁLISIS Y EVALUACIÓN DE RIESGOS<br>{{$reports->nombre_empresa}}</strong>
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
                    {{$reports->fecha_analisis}}
                </div>
            </td>
            </tr>
        </table>
    </header>

  <!-- Contenido -->
  <div class="contenido">
    <h2 style="text-align: left; font-size: 16pt; margin-bottom: 20px;">ÍNDICE</h2>
    <table style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman', serif; font-size: 12pt;">
      @foreach ($reports->titles->sortBy('id') as $title)
        <tr>
          {{-- Título --}}
          <td style="padding: 8px; text-align: left;">{{ $loop->iteration }}. {{ $title->title->nombre }}</td>
          <td style="padding: 8px; text-align: right;">15</td>
        </tr>

        {{-- Subtítulos dentro del título --}}
        @foreach ($title->subtitles->sortBy('id') as $subtitle)
          <tr>
            <td style="padding: 8px; text-align: left;">{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $subtitle->subtitle->nombre }}</td>
            <td style="padding: 8px; text-align: right;">20</td>
          </tr>

            {{-- Secciones dentro del subtítulo --}}
          @foreach ($subtitle->sections->sortBy('id') as $section)
            <tr>
              <td style="padding: 8px; text-align: left;">{{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $section->section->nombre }}</td>
              <td style="padding: 8px; text-align: right;">20</td>
            </tr>
          @endforeach
        @endforeach
      @endforeach
    </table>
  </div>
</body>
</html>
