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
     <ul class="list-none space-y-4">
        @foreach ($reports->titles->sortBy('id') as $title)
              @foreach ( $title->content as $cont)
              {!!$cont->cont!!}
              @endforeach
            <li>
                {{-- Título --}}
                <div class="flex justify-between items-center border-b border-gray-500">
                    <h2 class="text-xl font-semibold py-2">
                        {{ $loop->iteration }}. {{ $title->title->nombre }}
                </div>

                {{-- Subtítulos dentro del título --}}
                <ul class="list-none ml-6 space-y-2">
                    @foreach ($title->subtitles->sortBy('id') as $subtitle)
                        <li>
                            <div class="flex justify-between items-center border-b border-gray-400">
                                <h3 class="text-lg font-medium py-2">
                                    {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $subtitle->subtitle->nombre }}
                                </h3>
                            </div>

                            {{-- Secciones dentro del subtítulo --}}
                            <ul class="list-none ml-8 space-y-1">
                                @foreach ($subtitle->sections->sortBy('id') as $section)
                                    <li>
                                        <div class="flex justify-between items-cente border-b border-gray-400">
                                            <p>
                                                {{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                                {{ $section->section->nombre }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
  <!-- Pie de pagina -->
  <footer>
    <p style="font-size: 10pt;" class="page">
      Página 
    </p>
  </footer>
</body>
</html>
