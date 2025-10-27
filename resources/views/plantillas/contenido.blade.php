<!DOCTYPE html>
<html>
    <head>
        <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Plugin 3D -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d"></script>
<!-- ECharts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
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
            /*text-indent: 2.5em;   sangría de la primera línea (~5 espacios) */
            text-align: justify; /* texto justificado*/
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
    @php
        $tabNum = 1;
        $imgNum = 1;
    @endphp
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
        <div style="width: 100%; border-collapse: collapse; font-size: 12pt;">
            @foreach ($reports->titles->sortBy('id') as $title)
                <div>
                    {{-- Título --}}
                    <a style="display: block; font-weight: bold;">
                        <span style="color: transparent; font-size: 0;">__MARKER_TITLE_{{ $title->id }}__</span>
                        {{ $loop->iteration }}. {{ title_case_except($title->title->nombre) }}
                    </a>
                    <br>
                    @foreach ($title->content as $cont)
                        @php
                            $orientation = 'horizontal';

                            if (!empty($cont->img1) && !empty($cont->img2) && !empty($cont->img3)) {
                                $path = storage_path('app/public/'.$cont->img1);
                                $orientation = 'vertical';

                                if (file_exists($path)) {
                                    [$width, $height] = getimagesize($path);
                                    $orientation = $width > $height ? 'horizontal' : 'vertical';
                                }
                            }
                        @endphp
                        @if (empty(trim($cont->cont)))
                                @if (!empty($cont->img1))
                                    <div style="display: flex; justify-content: center; align-items: center; text-align: center;">
                                        <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                            <i>{{ $cont->leyenda1 }}</i>
                                        </p>
                                        <img src="{{ storage_path('app/public/'.$cont->img1) }}" 
                                            style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                    max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                    object-fit: contain;"
                                        >
                                    </div>
                                    @if (!empty($cont->img2))
                                        <div style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                            <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                <b>Imagen {{ $imgNum++ }}</b><br>
                                                <i>{{ $cont->leyenda2 }}</i>
                                            </p>
                                            <img src="{{ storage_path('app/public/'.$cont->img2) }}" 
                                                style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                        max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                        object-fit: contain;"
                                            >
                                        </div>
                                        @if (!empty($cont->img3))
                                            <div style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                                <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $cont->leyenda3 }}</i>
                                                </p>
                                                <img src="{{ storage_path('app/public/'.$cont->img3) }}" 
                                                    style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                            max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                            object-fit: contain;"
                                                >
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            <br>
                        @else
                            {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}

                            @if (!empty($cont->img1))
                                <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; text-align: center;">
                                    <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                        <b>Imagen {{ $imgNum++ }}</b><br>
                                        <i>{{ $cont->leyenda1 }}</i>
                                    </p>
                                    <img src="{{ storage_path('app/public/'.$cont->img1) }}" 
                                        style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                object-fit: contain;"
                                    >
                                </div>
                                @if (!empty($cont->img2))
                                    <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                        <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                            <i>{{ $cont->leyenda2 }}</i>
                                        </p>
                                        <img src="{{ storage_path('app/public/'.$cont->img2) }}" 
                                            style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                    max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                    object-fit: contain;"
                                        >
                                    </div>
                                    @if (!empty($cont->img3))
                                        <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                            <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                <b>Imagen {{ $imgNum++ }}</b><br>
                                                <i>{{ $cont->leyenda3 }}</i>
                                            </p>
                                            <img src="{{ storage_path('app/public/'.$cont->img3) }}" 
                                                style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                        max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                        object-fit: contain;"
                                            >
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endforeach

                    {{-- Subtítulos dentro del título --}}
                    @foreach ($title->subtitles->sortBy('id') as $subtitle)
                        <a style="display: block; text-align: justify; font-weight: bold;">
                            <span style="color: transparent; font-size: 0;">__MARKER_SUBTITLE_{{ $subtitle->id }}__</span>
                            {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($subtitle->subtitle->nombre) }}
                        </a>
                        <br>
                        @foreach ($subtitle->content as $cont)
                        @php
                            $orientation = 'horizontal';

                            if (!empty($cont->img1) && !empty($cont->img2) && !empty($cont->img3)) {
                                $path = storage_path('app/public/'.$cont->img1);
                                $orientation = 'vertical';

                                if (file_exists($path)) {
                                    [$width, $height] = getimagesize($path);
                                    $orientation = $width > $height ? 'horizontal' : 'vertical';
                                }
                            }
                        @endphp
                            @if (empty(trim($cont->cont)))
                                @if (!empty($cont->img1))
                                    <div style="display: flex; justify-content: center; align-items: center; text-align: center;">
                                        <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                            <i>{{ $cont->leyenda1 }}</i>
                                        </p>
                                        <img src="{{ storage_path('app/public/'.$cont->img1) }}" 
                                            style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                    max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                    object-fit: contain;"
                                        >
                                    </div>
                                    @if (!empty($cont->img2))
                                        <div style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                            <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                <b>Imagen {{ $imgNum++ }}</b><br>
                                                <i>{{ $cont->leyenda2 }}</i>
                                            </p>
                                            <img src="{{ storage_path('app/public/'.$cont->img2) }}" 
                                                style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                        max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                        object-fit: contain;"
                                            >
                                        </div>
                                        @if (!empty($cont->img3))
                                            <div style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                                <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $cont->leyenda3 }}</i>
                                                </p>
                                                <img src="{{ storage_path('app/public/'.$cont->img3) }}" 
                                                    style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                            max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                            object-fit: contain;"
                                                >
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                <br>
                                @if ($cont->analysisDiagrams()->exists())
                                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: center;">
                                        <tr style="background-color: #0f4a75ff; color: white; font-weight: bold;">
                                        <td style="border: 1px solid black; padding: 5px;">No.</td>
                                        <td style="border: 1px solid black; padding: 5px;">Riesgo</td>
                                        <td style="border: 1px solid black; padding: 5px;">F</td>
                                        <td style="border: 1px solid black; padding: 5px;">S</td>
                                        <td style="border: 1px solid black; padding: 5px;">P</td>
                                        <td style="border: 1px solid black; padding: 5px;">E</td>
                                        <td style="border: 1px solid black; padding: 5px;">PB</td>
                                        <td style="border: 1px solid black; padding: 5px;">If</td>
                                        <td style="border: 1px solid black; padding: 5px;">Clase del Riesgo</td>
                                        <td style="border: 1px solid black; padding: 5px;">Factor de ocurrencia</td>
                                        </tr>

                                        @foreach ($cont->analysisDiagrams as $diagram)
                                            <tr class="border" style="border: 1px solid black;">
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->no }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{$diagram->riesgo}}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->f }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->s }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->p }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->e }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->pb }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->if }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">PENDIENTE</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->f_ocurrencia }}</td>
                                            </tr>
                                        @endforeach
                                    </table>  
                                @endif   
                                @if($cont->reportTitleSubtitle->subtitle_id==15)
                                    <table id="tabla" style=" border-collapse: collapse;">
                                        <!-- Encabezado Cibernéticos -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white; border: 1px solid #000000ff;">
                                            <td colspan="2" style="padding: 4px;">Cibernéticos</td>
                                        </tr>
                                        <tbody id="ciberneticos" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'ciberneticos')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}" style="border-bottom: 1px solid #000000ff; border-right: 1px solid #000000ff;">
                                                    <td style="width: 40px; text-align: center; border-right: 1px solid #000000ff; border-left: 1px solid #000000ff; padding: 4px;">{{ $r->orden }}</td>
                                                    <td style="padding: 4px;">{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Encabezado Naturales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white; border: 1px solid #000000ff;">
                                            <td colspan="2" style="padding: 4px;">Naturales</td>
                                        </tr>
                                        <tbody id="naturales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'naturales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}" style="border-bottom: 1px solid #000000ff; border-right: 1px solid #000000ff;">
                                                    <td style="width: 40px; text-align: center; border-right: 1px solid #000000ff; border-left: 1px solid #000000ff; padding: 4px;">{{ $r->orden }}</td>
                                                    <td style="padding: 4px;">{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Encabezado Sociales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color: white; border: 1px solid #000000ff;">
                                            <td colspan="2" style="padding: 4px;">Sociales (Personas)</td>
                                        </tr>
                                        <tbody id="sociales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'sociales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}" style="border-bottom: 1px solid #000000ff; border-right: 1px solid #000000ff;">
                                                    <td style="width: 40px; text-align: center; border-right: 1px solid #000000ff; border-left: 1px solid #000000ff; padding: 4px;"">{{ $r->orden }}</td>
                                                    <td style="padding: 4px;">{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if($cont->reportTitleSubtitle->subtitle_id==16)
                                <img src="storage/{{ $cont->img_grafica }}" style="page-break-before: always; margin-top: 1.5cm;"/>

                                <style>
                                    .bg-green { background-color: #15803d; }  /* Verde oscuro */
                                    .bg-yellow { background-color: #facc15; color: black; } /* Amarillo */
                                    .bg-red { background-color: #dc2626; }   /* Rojo */

                                    /* Colores de fondo de celdas */
                                    .cell-green { background-color: #bbf7d0; }   /* Verde claro */
                                    .cell-yellow { background-color: #fef9c3; }  /* Amarillo claro */
                                    .cell-red { background-color: #fecaca; }     /* Rojo claro */
                                    ul {
                                    margin: 0;
                                    padding-left: 18px;
                                    }

                                    li {
                                    margin-bottom: 3px;
                                    }

                                    p {
                                    margin: 0;
                                    }
                                    .bg-green { background-color: #15803d; }  /* Verde oscuro */
                                    .bg-yellow { background-color: #facc15; color: black; } /* Amarillo */
                                    .bg-red { background-color: #dc2626; }   /* Rojo */

                                    /* Colores de fondo de celdas */
                                    .cell-green { background-color: #bbf7d0; }   /* Verde claro */
                                    .cell-yellow { background-color: #fef9c3; }  /* Amarillo claro */
                                    .cell-red { background-color: #fecaca; }     /* Rojo claro */
                                </style>
                                    <table style ="font-size: 11pt;">
                                        <thead>
                                        <tr>
                                            <th class="bg-green">Rango Normal<br>(Zona de Seguridad)</th>
                                            <th class="bg-yellow">Rango Intermedio<br>(Zona de Atención)</th>
                                            <th class="bg-red">Rango de atención inmediata<br>(Zona intolerable)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                {{-- 🔹 RANGO NORMAL --}}
                                                <td class="cell-green">
                                                @php
                                                    $riesgosNormales = $diagrama->where('c_riesgo', 'normal')->sortBy('orden2');
                                                @endphp
                                                @if ($riesgosNormales->count() > 0)
                                                    <ul>
                                                    @foreach ($riesgosNormales as $r)
                                                        <li style="text-align: justify; line-height: 1.4em;">{{ $r->no }} - {{ $r->riesgo }}</li>
                                                    @endforeach
                                                    </ul>
                                                @endif
                                                </td>

                                                {{-- 🔹 RANGO INTERMEDIO --}}
                                                <td class="cell-yellow">
                                                @php
                                                    $riesgosIntermedios = $diagrama->where('c_riesgo', 'intermedio')->sortBy('orden2');
                                                @endphp
                                                @if ($riesgosIntermedios->count() > 0)
                                                    <ul>
                                                    @foreach ($riesgosIntermedios as $r)
                                                        <li style="text-align: justify; line-height: 1.4em;">{{ $r->no }} - {{ $r->riesgo }}</li>
                                                    @endforeach
                                                    </ul>
                                                @endif
                                                </td>

                                                {{-- 🔹 RANGO INMEDIATO --}}
                                                <td class="cell-red">
                                                @php
                                                    $riesgosInmediatos = $diagrama->where('c_riesgo', 'inmediato')->sortBy('orden2');
                                                @endphp
                                                @if ($riesgosInmediatos->count() > 0)
                                                    <ul>
                                                    @foreach ($riesgosInmediatos as $r)
                                                        <li style="text-align: justify; line-height: 1.4em;">{{ $r->no }} - {{ $r->riesgo }}</li>
                                                    @endforeach
                                                    </ul>
                                                @endif
                                                </td>
                                            </tr>

                                            {{-- 🔸 Segunda fila: Descripción de cada rango --}}
                                            <tr>
                                                <td class="bg-green" style="line-height: 1.4em;">
                                                <p>
                                                    Este rango representa riesgos de baja probabilidad y bajo impacto. Los eventos situados
                                                    en este rango normalmente se consideran aceptables y dentro de los límites normales de
                                                    operación. Las consecuencias, si ocurren, serían bajas y fácilmente controladas por la
                                                    organización. Normalmente, no se necesita ninguna acción correctiva inmediata, pero se
                                                    deben mantener los controles actuales y monitorear continuamente los riesgos para
                                                    garantizar que permanezcan dentro de esta zona de seguridad.
                                                </p>
                                                </td>

                                                <td class="bg-yellow" style="line-height: 1.4em;">
                                                <p>
                                                    En este rango, los riesgos presentan una probabilidad y/o impactos moderados. Los eventos
                                                    en el área intermedia requieren atención, ya que pueden causar perturbaciones
                                                    significativas en la operación, aunque no de manera catastrófica. Se recomiendan medidas
                                                    preventivas o correctivas para mitigar el impacto o la probabilidad de ocurrencia, con un
                                                    monitoreo constante para evitar que migren al área de riesgo intolerable.
                                                </p>
                                                </td>

                                                <td class="bg-red" style="line-height: 1.4em;">
                                                <p>
                                                    Este rango representa riesgos de alta probabilidad y/o alto impacto, siendo considerados
                                                    inaceptables y requieren intervención inmediata. Cualquier evento en este rango puede
                                                    causar graves consecuencias para la organización, comprometiendo seriamente sus objetivos
                                                    y/o procesos. La mitigación de estos riesgos debe ser la máxima prioridad y se requieren
                                                    acciones inmediatas para reducir el impacto y/o la probabilidad de ocurrencia.
                                                </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                                                               
                            @else
                                {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}
                                
                                @if (!empty($cont->img1))
                                    <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; text-align: center;">
                                        <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                            <i>{{ $cont->leyenda1 }}</i>
                                        </p>
                                        <img src="{{ storage_path('app/public/'.$cont->img1) }}" 
                                            style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                    max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                    object-fit: contain;"
                                        >
                                    </div>
                                    @if (!empty($cont->img2))
                                        <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                            <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                <b>Imagen {{ $imgNum++ }}</b><br>
                                                <i>{{ $cont->leyenda2 }}</i>
                                            </p>
                                            <img src="{{ storage_path('app/public/'.$cont->img2) }}" 
                                                style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                        max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                        object-fit: contain;"
                                            >
                                        </div>
                                        @if (!empty($cont->img3))
                                            <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                                <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $cont->leyenda3 }}</i>
                                                </p>
                                                <img src="{{ storage_path('app/public/'.$cont->img3) }}" 
                                                    style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                            max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                            object-fit: contain;"
                                                >
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                {{-- Tabla de análisis y evaluación de riesgos --}}
                                @if ($cont->analysisDiagrams()->exists())
                                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; text-align: center;">
                                        <tr style="background-color: #0f4a75ff; color: white; font-weight: bold;">
                                        <td style="border: 1px solid black; padding: 5px;">No.</td>
                                        <td style="border: 1px solid black; padding: 5px;">Riesgo</td>
                                        <td style="border: 1px solid black; padding: 5px;">F</td>
                                        <td style="border: 1px solid black; padding: 5px;">S</td>
                                        <td style="border: 1px solid black; padding: 5px;">P</td>
                                        <td style="border: 1px solid black; padding: 5px;">E</td>
                                        <td style="border: 1px solid black; padding: 5px;">PB</td>
                                        <td style="border: 1px solid black; padding: 5px;">If</td>
                                        <td style="border: 1px solid black; padding: 5px;">Clase del Riesgo</td>
                                        <td style="border: 1px solid black; padding: 5px;">Factor de ocurrencia</td>
                                        </tr>

                                        @foreach ($cont->analysisDiagrams as $diagram)
                                            <tr class="border" style="border: 1px solid black;">
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->no }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{$diagram->riesgo}}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->f }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->s }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->p }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->e }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->pb }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->if }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">PENDIENTE</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->f_ocurrencia }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                               @if($cont->reportTitleSubtitle->subtitle_id==15)
                                    <table id="tabla" style=" border-collapse: collapse;">
                                        <!-- Encabezado Cibernéticos -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white; border: 1px solid #000000ff;">
                                            <td colspan="2" style="padding: 4px;">Cibernéticos</td>
                                        </tr>
                                        <tbody id="ciberneticos" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'ciberneticos')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}" style="border-bottom: 1px solid #000000ff; border-right: 1px solid #000000ff;">
                                                    <td style="width: 40px; text-align: center; border-right: 1px solid #000000ff; border-left: 1px solid #000000ff; padding: 4px;">{{ $r->orden }}</td>
                                                    <td style="padding: 4px;">{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Encabezado Naturales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white; border: 1px solid #000000ff;">
                                            <td colspan="2" style="padding: 4px;">Naturales</td>
                                        </tr>
                                        <tbody id="naturales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'naturales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}" style="border-bottom: 1px solid #000000ff; border-right: 1px solid #000000ff;">
                                                    <td style="width: 40px; text-align: center; border-right: 1px solid #000000ff; border-left: 1px solid #000000ff; padding: 4px;">{{ $r->orden }}</td>
                                                    <td style="padding: 4px;">{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Encabezado Sociales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color: white; border: 1px solid #000000ff;">
                                            <td colspan="2" style="padding: 4px;">Sociales (Personas)</td>
                                        </tr>
                                        <tbody id="sociales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'sociales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}" style="border-bottom: 1px solid #000000ff; border-right: 1px solid #000000ff;">
                                                    <td style="width: 40px; text-align: center; border-right: 1px solid #000000ff; border-left: 1px solid #000000ff; padding: 4px;"">{{ $r->orden }}</td>
                                                    <td style="padding: 4px;">{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif               
                            @endif
                        @endforeach
                        {{-- Secciones dentro del subtítulo --}}
                        @foreach ($subtitle->sections->sortBy('id') as $section)
                            <a style="display: block; text-align: justify; font-weight: bold; text-indent: 0.88cm;">
                                <span style="color: transparent; font-size: 0;">__MARKER_SECTION_{{ $section->id }}__</span>
                                {{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($section->section->nombre) }}
                            </a>
                            <br>
                            @foreach ($section->content as $cont)
                            @php
                                $orientation = 'horizontal';

                                if (!empty($cont->img1) && !empty($cont->img2) && !empty($cont->img3)) {
                                    $path = storage_path('app/public/'.$cont->img1);
                                    $orientation = 'vertical';

                                    if (file_exists($path)) {
                                        [$width, $height] = getimagesize($path);
                                        $orientation = $width > $height ? 'horizontal' : 'vertical';
                                    }
                                }
                            @endphp
                            @if (empty(trim($cont->cont)))
                                @if (!empty($cont->img1))
                                    <div style="display: flex; justify-content: center; align-items: center; text-align: center;">
                                        <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                            <i>{{ $cont->leyenda1 }}</i>
                                        </p>
                                        <img src="{{ storage_path('app/public/'.$cont->img1) }}" 
                                            style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                    max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                    object-fit: contain;"
                                        >
                                    </div>
                                    @if (!empty($cont->img2))
                                        <div style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                            <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                <b>Imagen {{ $imgNum++ }}</b><br>
                                                <i>{{ $cont->leyenda2 }}</i>
                                            </p>
                                            <img src="{{ storage_path('app/public/'.$cont->img2) }}" 
                                                style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                        max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                        object-fit: contain;"
                                            >
                                        </div>
                                        @if (!empty($cont->img3))
                                            <div style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                                <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $cont->leyenda3 }}</i>
                                                </p>
                                                <img src="{{ storage_path('app/public/'.$cont->img3) }}" 
                                                    style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                            max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                            object-fit: contain;"
                                                >
                                            </div>
                                        @endif
                                    @endif
                                <br>
                                @endif
                                @else
                                    {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}

                                    @if (!empty($cont->img1))
                                        <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; text-align: center;">
                                            <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                <b>Imagen {{ $imgNum++ }}</b><br>
                                                <i>{{ $cont->leyenda1 }}</i>
                                            </p>
                                            <img src="{{ storage_path('app/public/'.$cont->img1) }}" 
                                                style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                        max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                        object-fit: contain;"
                                            >
                                        </div>
                                        @if (!empty($cont->img2))
                                            <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                                <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $cont->leyenda2 }}</i>
                                                </p>
                                                <img src="{{ storage_path('app/public/'.$cont->img2) }}" 
                                                    style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                            max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                            object-fit: contain;"
                                                >
                                            </div>
                                            @if (!empty($cont->img3))
                                                <div style="page-break-before: always; display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">
                                                    <p style="margin: 0; text-align: center; line-height: 1; text-indent: 0;">
                                                        <b>Imagen {{ $imgNum++ }}</b><br>
                                                        <i>{{ $cont->leyenda3 }}</i>
                                                    </p>
                                                    <img src="{{ storage_path('app/public/'.$cont->img3) }}" 
                                                        style= "max-width: {{ $orientation == 'horizontal' ? '100%' : '80%' }};
                                                                max-height: {{ $orientation == 'horizontal' ? '80%' : '100%' }};
                                                                object-fit: contain;"
                                                    >
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>