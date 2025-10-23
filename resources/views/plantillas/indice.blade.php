@php
    function getPage($markers, $type, $id) {
        return $markers["{$type}_{$id}"] ?? '—';
    }
@endphp
<!DOCTYPE html>
<html>
<head>
  <title>Analisis y evaluación de riesgo</title>
  <style>
    @page {
      size: A4;
      margin: 1cm;  /* 👈 margen oficial de 2.5 cm */
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
      font-family: 'Arial Nova Light', Arial, sans-serif;
      font-size: 12pt;
    }

    body {
      margin-top: 120px; /* mismo alto aproximado que ocupa tu header */
      margin-left: 2.5cm;  /* 👈 margen oficial de 2.5 cm */
      margin-right: 2.5cm;
      margin-bottom: 2.5cm;
      font-family: 'Arial Nova Light', Arial, sans-serif;
    }

    .contenido {
      margin: 0;
      color: black; /* O el color que contraste con tu fondo */
      font-size: 12pt;
    }

    @font-face {
        font-family: 'Arial Nova Light';
        src: url('/fonts/ArialNova-Light.ttf') format('truetype');
        font-weight: 300;
        font-style: normal;
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
                <strong>ANÁLISIS Y EVALUACIÓN DE RIESGOS<br>{{$reports->nombre_empresa}}</strong>
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
    <h2 style="text-align: center; font-size: 16pt; margin-bottom: 20px;">ÍNDICE</h2>
    <table style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman', serif; font-size: 10pt;">
      @foreach ($reports->titles->sortBy('id') as $title)
        <tr>
          {{-- Título --}}
          <td style="text-align: justify; border-bottom: 1.5px dotted #000; display:flex">
            <strong><span>{{ $loop->iteration }}.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $title->title->nombre }}</span></strong>
            <td style="float: right;"><strong>{{ getPage($markers, 'TITLE', $title->id) }}</strong></td>
          </td>
        </tr>

        {{-- Subtítulos dentro del título --}}
        @foreach ($title->subtitles->sortBy('id') as $subtitle)
        <tr>
          <td style=" padding-left: 15px; text-align: justify; border-bottom: 1px dotted #000; display:flex">
              <span>{{ $loop->parent->iteration }}.{{ $loop->iteration }}.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subtitle->subtitle->nombre }}</span>
              <td style="float: right;">{{ getPage($markers, 'SUBTITLE', $subtitle->id) }}</td>
            </td>
          </tr>

            {{-- Secciones dentro del subtítulo --}}
          @foreach ($subtitle->sections->sortBy('id') as $section)
          <tr>
              <td style=" padding-left: 30px; ptext-align: justify;border-bottom: 1px dotted #000; display:flex">
                <span>{{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $section->section->nombre }}</span>
                <td style="float: right;">{{ getPage($markers, 'SECTION', $section->id) }}</td>
              </td>
            </tr>
          @endforeach
        @endforeach
      @endforeach
    </table>
  </div>
</body>
</html>
