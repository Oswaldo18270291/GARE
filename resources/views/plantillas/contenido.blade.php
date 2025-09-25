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
      font-family: "Times News Roman", serif;
      font-size: 12pt;
    }

    body {
      margin-top: 120px; /* mismo alto aproximado que ocupa tu header */
      margin-left: 2.5cm;  /* 👈 margen oficial de 2.5 cm */
      margin-right: 2.5cm;
      margin-bottom: 2.5cm;
    }

    .contenido {
      margin: 0;
      color: black; /* O el color que contraste con tu fondo */
      font-size: 12pt;
      line-height: 2;       /* interlineado doble */
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
    <div style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman', serif; font-size: 12pt;">
      @foreach ($reports->titles->sortBy('id') as $title)
        <div>
          {{-- Título --}}
          <a style="padding: 8px; text-align: left;">{{ $loop->iteration }}. {{ $title->title->nombre }}</a>
            @foreach ( $title->content as $cont)
            {!!$cont->cont!!}
            @endforeach
            {{-- Subtítulos dentro del título --}}
            @foreach ($title->subtitles->sortBy('id') as $subtitle)
                <a style="padding: 8px; padding-left: 48px; text-align: justify;">{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $subtitle->subtitle->nombre }}</a>
                @foreach ( $subtitle->content as $cont)
                {!!$cont->cont!!}
                @endforeach
                {{-- Secciones dentro del subtítulo --}}
                @foreach ($subtitle->sections->sortBy('id') as $section)
                    <a style="padding: 8px; padding-left: 96px; text-align: justify;">{{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $section->section->nombre }}</a>
                    @foreach ( $section->content as $cont)
                    {!!$cont->cont!!}
                    @endforeach
                @endforeach
            @endforeach
        </div>
      @endforeach
    </div>
  </div>
</body>
</html>