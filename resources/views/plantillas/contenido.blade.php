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
                @foreach ($reports->titles as $title)
                    <div>
                        {{-- Título --}}
                        <a style="display: block; font-weight: bold;">
                            <span style="color: transparent; font-size: 0;">__MARKER_TITLE_{{ $title->id }}__</span>
                            {{ $loop->iteration }}. {{ title_case_except($title->title->nombre) }}
                        </a>
                        <br>
                        @foreach ($title->content as $cont)
                            @if (empty(trim($cont->cont)))
                                @php
                                    $imgs = [];

                                    // Recolectar imágenes con su orientación
                                    foreach (['img1', 'img2', 'img3'] as $i) {
                                        if (!empty($cont->{$i})) {
                                            $path = storage_path('app/public/'.$cont->{$i});
                                            $size = @getimagesize($path);
                                            $ori = ($size && $size[0] > $size[1]) ? 'h' : 'v'; // horizontal o vertical

                                            $imgs[] = [
                                                'src' => $cont->{$i},
                                                'leyenda' => $cont->{'leyenda'.substr($i, -1)},
                                                'o' => $ori,
                                            ];
                                        }
                                    }

                                    // Determinar tipo general
                                    $count = count($imgs);
                                    $allV = $count && collect($imgs)->every(fn($x) => $x['o'] === 'v');
                                    $allH = $count && collect($imgs)->every(fn($x) => $x['o'] === 'h');
                                @endphp

                                @if ($count)
                                    <div style="margin-top:25px; text-align:center; overflow:hidden;">
                                        @foreach ($imgs as $i => $img)
                                            @php
                                                $style = '';

                                                if ($count === 1) {
                                                    // ✅ Solo una imagen → centrada
                                                    $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                } elseif ($allV) {
                                                    // ✅ Tres verticales → en una sola línea
                                                    $style = 'float:left; width:32%; margin:0 1% 12px;';
                                                } elseif ($allH) {
                                                    // ✅ Dos o tres horizontales
                                                    if ($count == 2) {
                                                        $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                    } elseif ($count == 3 && $loop->last) {
                                                        $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                    } else {
                                                        $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                    }
                                                } else {
                                                    // ✅ Mixtas (por si acaso)
                                                    $style = ($count == 3 && $loop->last)
                                                        ? 'float:none; display:block; width:70%; margin:0 auto 12px;'
                                                        : 'float:left; width:48%; margin:0 1% 12px;';
                                                }
                                            @endphp

                                            <div style="{{ $style }} text-align:center;">
                                                {{-- Leyenda arriba --}}
                                                <p style="margin:0 0 6px; line-height:1.2;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $img['leyenda'] }}</i>
                                                </p>

                                                {{-- Imagen --}}
                                                <img src="{{ storage_path('app/public/'.$img['src']) }}"
                                                    style="width:100%; height:auto; object-fit:contain;">
                                            </div>

                                            {{-- Limpiar flotantes después de cada par de horizontales --}}
                                            @if ($allH && (($loop->iteration % 2) == 0) && !($loop->last && $count == 3))
                                                <div style="clear:both;"></div>
                                            @endif
                                        @endforeach
                                        <div style="clear:both;"></div>
                                    </div>
                                @endif
                                <br>
                            @else
                                {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}
                                @php
                                    $imgs = [];

                                    // Recolectar imágenes con su orientación
                                    foreach (['img1', 'img2', 'img3'] as $i) {
                                        if (!empty($cont->{$i})) {
                                            $path = storage_path('app/public/'.$cont->{$i});
                                            $size = @getimagesize($path);
                                            $ori = ($size && $size[0] > $size[1]) ? 'h' : 'v'; // horizontal o vertical

                                            $imgs[] = [
                                                'src' => $cont->{$i},
                                                'leyenda' => $cont->{'leyenda'.substr($i, -1)},
                                                'o' => $ori,
                                            ];
                                        }
                                    }

                                    // Determinar tipo general
                                    $count = count($imgs);
                                    $allV = $count && collect($imgs)->every(fn($x) => $x['o'] === 'v');
                                    $allH = $count && collect($imgs)->every(fn($x) => $x['o'] === 'h');
                                @endphp

                                @if ($count)
                                    <div style="margin-top:25px; text-align:center; overflow:hidden;">
                                        @foreach ($imgs as $i => $img)
                                            @php
                                                $style = '';

                                                if ($count === 1) {
                                                    // ✅ Solo una imagen → centrada
                                                    $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                } elseif ($allV) {
                                                    // ✅ Tres verticales → en una sola línea
                                                    $style = 'float:left; width:32%; margin:0 1% 12px;';
                                                } elseif ($allH) {
                                                    // ✅ Dos o tres horizontales
                                                    if ($count == 2) {
                                                        $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                    } elseif ($count == 3 && $loop->last) {
                                                        $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                    } else {
                                                        $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                    }
                                                } else {
                                                    // ✅ Mixtas (por si acaso)
                                                    $style = ($count == 3 && $loop->last)
                                                        ? 'float:none; display:block; width:70%; margin:0 auto 12px;'
                                                        : 'float:left; width:48%; margin:0 1% 12px;';
                                                }
                                            @endphp

                                            <div style="{{ $style }} text-align:center;">
                                                {{-- Leyenda arriba --}}
                                                <p style="margin:0 0 6px; line-height:1.2;">
                                                    <b>Imagen {{ $imgNum++ }}</b><br>
                                                    <i>{{ $img['leyenda'] }}</i>
                                                </p>

                                                {{-- Imagen --}}
                                                <img src="{{ storage_path('app/public/'.$img['src']) }}"
                                                    style="width:100%; height:auto; object-fit:contain;">
                                            </div>

                                            {{-- Limpiar flotantes después de cada par de horizontales --}}
                                            @if ($allH && (($loop->iteration % 2) == 0) && !($loop->last && $count == 3))
                                                <div style="clear:both;"></div>
                                            @endif
                                        @endforeach
                                        <div style="clear:both;"></div>
                                    </div>
                                @endif
                                <br>
                            @endif
                        @endforeach

                        {{-- Subtítulos dentro del título --}}
                        @foreach ($title->subtitles as $subtitle)
                            <a style="display: block; text-align: justify; font-weight: bold;">
                                <span style="color: transparent; font-size: 0;">__MARKER_SUBTITLE_{{ $subtitle->id }}__</span>
                                {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($subtitle->subtitle->nombre) }}
                            </a>
                            <br>
                            @foreach ($subtitle->content as $cont)
                                @if (empty(trim($cont->cont)))
                                    @php
                                        $imgs = [];
                                        foreach (['img1','img2','img3'] as $i) {
                                            if (!empty($cont->{$i})) {
                                                $path = storage_path('app/public/'.$cont->{$i});
                                                $size = @getimagesize($path);
                                                $ori  = ($size && $size[0] > $size[1]) ? 'h' : 'v'; // h=horizontal, v=vertical
                                                $imgs[] = [
                                                    'src' => $cont->{$i},
                                                    'leyenda' => $cont->{'leyenda'.substr($i,-1)},
                                                    'o' => $ori,
                                                ];
                                            }
                                        }
                                        $allV = count($imgs) && collect($imgs)->every(fn($x)=>$x['o']==='v');
                                        $allH = count($imgs) && collect($imgs)->every(fn($x)=>$x['o']==='h');
                                    @endphp

                                    @if (count($imgs))
                                        <div style="margin-top:25px; text-align:center; overflow:hidden;">
                                            @foreach ($imgs as $i=>$img)
                                                @php
                                                    $styles = '';

                                                    if ($allV) {
                                                        // 3 en una línea
                                                        $styles = 'float:left; width:32%; margin:0 1% 12px;';   // 32*3 + 1%*4 = ~100%
                                                    } elseif ($allH) {
                                                        // 2 por fila; si hay 3, la 3ª centrada
                                                        if (count($imgs)==3 && $loop->last) {
                                                            $styles = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                        } else {
                                                            $styles = 'float:left; width:48%; margin:0 1% 12px;'; // 48 + 1 + 48 + 1 = 98%
                                                        }
                                                    } else {
                                                        // Mixtas: dos por fila, la última (si es 3) centrada
                                                        $styles = (count($imgs)==3 && $loop->last)
                                                            ? 'float:none; display:block; width:70%; margin:0 auto 12px;'
                                                            : 'float:left; width:48%; margin:0 1% 12px;';
                                                    }
                                                @endphp
                                                <div style="{{ $styles }} text-align:center;">
                                                    <p style="margin:0 0 6px; line-height:1.2;">
                                                        <b>Imagen {{ $imgNum++ }}</b><br>
                                                        <i>{{ $img['leyenda'] }}</i>
                                                    </p>
                                                    <img src="{{ storage_path('app/public/'.$img['src']) }}"
                                                        style="width:100%; height:auto; object-fit:contain;">
                                                </div>

                                                {{-- Clear después de cada pareja cuando son horizontales puras (evita que la 3ª intente subir) --}}
                                                @if ($allH && (($loop->iteration % 2) == 0) && !($loop->last && count($imgs)==3))
                                                    <div style="clear:both;"></div>
                                                @endif
                                            @endforeach
                                            <div style="clear:both;"></div>
                                        </div>
                                    @endif
                                    <br>
                                    {{--
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
                                                    {{-- RANGO NORMAL
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
                                                    --}}
                                                    {{-- 🔹 RANGO INTERMEDIO
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
                                                    --}}
                                                    {{-- 🔹 RANGO INMEDIATO
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
                                                --}}
                                                {{-- 🔸 Segunda fila: Descripción de cada rango
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
                                     --}}
                                    {{-- AQUI DEBE DE IR LA TABLA DE MATRIZ DE RIESGOS --}}
                                    @if ($cont->reportTitleSubtitle->subtitle_id==42)
                                        <table style="width: 100%; border-collapse: collapse; text-align: center; font-weight: bold;">
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Revisó:
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Elaboró:
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Director Jurídico H. Congreso de Chiapas
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Subsecretario de Servicios Estratégicos de Seguridad
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Lic. Roberto René Pinto Rojas
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Comisario Jefe Dr. Rafael Rincón Valencia
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Conforme:
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Autorizó:
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Presidenta del H. Congreso de Chiapas
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Secretario de Seguridad del Pueblo de Chiapas
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Diputada Presidenta<br>
                                                    <span>Dip. Alejandra Gómez Mendoza</span>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Dr. y P.A. Oscar Alberto Aparicio Avendaño
                                                </td>
                                            </tr>
                                        </table>
                                    @endif            
                                @else
                                    {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}

                                    @php
                                        $imgs = [];

                                        // Recolectar imágenes con su orientación
                                        foreach (['img1', 'img2', 'img3'] as $i) {
                                            if (!empty($cont->{$i})) {
                                                $path = storage_path('app/public/'.$cont->{$i});
                                                $size = @getimagesize($path);
                                                $ori = ($size && $size[0] > $size[1]) ? 'h' : 'v'; // horizontal o vertical

                                                $imgs[] = [
                                                    'src' => $cont->{$i},
                                                    'leyenda' => $cont->{'leyenda'.substr($i, -1)},
                                                    'o' => $ori,
                                                ];
                                            }
                                        }

                                        // Determinar tipo general
                                        $count = count($imgs);
                                        $allV = $count && collect($imgs)->every(fn($x) => $x['o'] === 'v');
                                        $allH = $count && collect($imgs)->every(fn($x) => $x['o'] === 'h');
                                    @endphp

                                    @if ($count)
                                        <div style="margin-top:25px; text-align:center; overflow:hidden;">
                                            @foreach ($imgs as $i => $img)
                                                @php
                                                    $style = '';

                                                    if ($count === 1) {
                                                        // ✅ Solo una imagen → centrada
                                                        $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                    } elseif ($allV) {
                                                        // ✅ Tres verticales → en una sola línea
                                                        $style = 'float:left; width:32%; margin:0 1% 12px;';
                                                    } elseif ($allH) {
                                                        // ✅ Dos o tres horizontales
                                                        if ($count == 2) {
                                                            $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                        } elseif ($count == 3 && $loop->last) {
                                                            $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                        } else {
                                                            $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                        }
                                                    } else {
                                                        // ✅ Mixtas (por si acaso)
                                                        $style = ($count == 3 && $loop->last)
                                                            ? 'float:none; display:block; width:70%; margin:0 auto 12px;'
                                                            : 'float:left; width:48%; margin:0 1% 12px;';
                                                    }
                                                @endphp

                                                <div style="{{ $style }} text-align:center;">
                                                    {{-- Leyenda arriba --}}
                                                    <p style="margin:0 0 6px; line-height:1.2;">
                                                        <b>Imagen {{ $imgNum++ }}</b><br>
                                                        <i>{{ $img['leyenda'] }}</i>
                                                    </p>

                                                    {{-- Imagen --}}
                                                    <img src="{{ storage_path('app/public/'.$img['src']) }}"
                                                        style="width:100%; height:auto; object-fit:contain;">
                                                </div>

                                                {{-- Limpiar flotantes después de cada par de horizontales --}}
                                                @if ($allH && (($loop->iteration % 2) == 0) && !($loop->last && $count == 3))
                                                    <div style="clear:both;"></div>
                                                @endif
                                            @endforeach
                                            <div style="clear:both;"></div>
                                        </div>
                                    @endif

                                    <br>

                                    {{-- Tabla de análisis y evaluación de riesgos --}}
                                    {{--
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
                                    --}}
                                    {{-- AQUI DEBE DE IR LA TABLA DE MATRIZ DE RIESGOS --}}
                                    @if ($cont->reportTitleSubtitle->subtitle_id==42)
                                        <table style="width: 100%; border-collapse: collapse; text-align: center; font-weight: bold;">
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Revisó:
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Elaboró:
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Director Jurídico H. Congreso de Chiapas
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Subsecretario de Servicios Estratégicos de Seguridad
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Lic. Roberto René Pinto Rojas
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Comisario Jefe Dr. Rafael Rincón Valencia
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Conforme:
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Autorizó:
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Presidenta del H. Congreso de Chiapas
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Secretario de Seguridad del Pueblo de Chiapas
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    <br><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Diputada Presidenta<br>
                                                    <span>Dip. Alejandra Gómez Mendoza</span>
                                                </td>
                                                <td style="border: 1px dashed #999; padding: 8px;">
                                                    Dr. y P.A. Oscar Alberto Aparicio Avendaño
                                                </td>
                                            </tr>
                                        </table>
                                    @endif
                                @endif               
                            @endforeach
                            {{-- Secciones dentro del subtítulo --}}
                            @foreach ($subtitle->sections as $section)
                                <a style="display: block; text-align: justify; font-weight: bold; text-indent: 0.88cm;">
                                    <span style="color: transparent; font-size: 0;">__MARKER_SECTION_{{ $section->id }}__</span>
                                    {{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($section->section->nombre) }}
                                </a>
                                <br>
                                @foreach ($section->content as $cont)
                                    @if (empty(trim($cont->cont)))
                                        @php
                                            $imgs = [];

                                            // Recolectar imágenes con su orientación
                                            foreach (['img1', 'img2', 'img3'] as $i) {
                                                if (!empty($cont->{$i})) {
                                                    $path = storage_path('app/public/'.$cont->{$i});
                                                    $size = @getimagesize($path);
                                                    $ori = ($size && $size[0] > $size[1]) ? 'h' : 'v'; // horizontal o vertical

                                                    $imgs[] = [
                                                        'src' => $cont->{$i},
                                                        'leyenda' => $cont->{'leyenda'.substr($i, -1)},
                                                        'o' => $ori,
                                                    ];
                                                }
                                            }

                                            // Determinar tipo general
                                            $count = count($imgs);
                                            $allV = $count && collect($imgs)->every(fn($x) => $x['o'] === 'v');
                                            $allH = $count && collect($imgs)->every(fn($x) => $x['o'] === 'h');
                                        @endphp

                                        @if ($count)
                                            <div style="margin-top:25px; text-align:center; overflow:hidden;">
                                                @foreach ($imgs as $i => $img)
                                                    @php
                                                        $style = '';

                                                        if ($count === 1) {
                                                            // ✅ Solo una imagen → centrada
                                                            $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                        } elseif ($allV) {
                                                            // ✅ Tres verticales → en una sola línea
                                                            $style = 'float:left; width:32%; margin:0 1% 12px;';
                                                        } elseif ($allH) {
                                                            // ✅ Dos o tres horizontales
                                                            if ($count == 2) {
                                                                $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                            } elseif ($count == 3 && $loop->last) {
                                                                $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                            } else {
                                                                $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                            }
                                                        } else {
                                                            // ✅ Mixtas (por si acaso)
                                                            $style = ($count == 3 && $loop->last)
                                                                ? 'float:none; display:block; width:70%; margin:0 auto 12px;'
                                                                : 'float:left; width:48%; margin:0 1% 12px;';
                                                        }
                                                    @endphp

                                                    <div style="{{ $style }} text-align:center;">
                                                        {{-- Leyenda arriba --}}
                                                        <p style="margin:0 0 6px; line-height:1.2;">
                                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                                            <i>{{ $img['leyenda'] }}</i>
                                                        </p>

                                                        {{-- Imagen --}}
                                                        <img src="{{ storage_path('app/public/'.$img['src']) }}"
                                                            style="width:100%; height:auto; object-fit:contain;">
                                                    </div>

                                                    {{-- Limpiar flotantes después de cada par de horizontales --}}
                                                    @if ($allH && (($loop->iteration % 2) == 0) && !($loop->last && $count == 3))
                                                        <div style="clear:both;"></div>
                                                    @endif
                                                @endforeach
                                                <div style="clear:both;"></div>
                                            </div>
                                        @endif
                                            <br>
                                    @else
                                        {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}

                                        @php
                                            $imgs = [];

                                            // Recolectar imágenes con su orientación
                                            foreach (['img1', 'img2', 'img3'] as $i) {
                                                if (!empty($cont->{$i})) {
                                                    $path = storage_path('app/public/'.$cont->{$i});
                                                    $size = @getimagesize($path);
                                                    $ori = ($size && $size[0] > $size[1]) ? 'h' : 'v'; // horizontal o vertical

                                                    $imgs[] = [
                                                        'src' => $cont->{$i},
                                                        'leyenda' => $cont->{'leyenda'.substr($i, -1)},
                                                        'o' => $ori,
                                                    ];
                                                }
                                            }

                                            // Determinar tipo general
                                            $count = count($imgs);
                                            $allV = $count && collect($imgs)->every(fn($x) => $x['o'] === 'v');
                                            $allH = $count && collect($imgs)->every(fn($x) => $x['o'] === 'h');
                                        @endphp

                                        @if ($count)
                                            <div style="margin-top:25px; text-align:center; overflow:hidden;">
                                                @foreach ($imgs as $i => $img)
                                                    @php
                                                        $style = '';

                                                        if ($count === 1) {
                                                            // ✅ Solo una imagen → centrada
                                                            $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                        } elseif ($allV) {
                                                            // ✅ Tres verticales → en una sola línea
                                                            $style = 'float:left; width:32%; margin:0 1% 12px;';
                                                        } elseif ($allH) {
                                                            // ✅ Dos o tres horizontales
                                                            if ($count == 2) {
                                                                $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                            } elseif ($count == 3 && $loop->last) {
                                                                $style = 'float:none; display:block; width:70%; margin:0 auto 12px;';
                                                            } else {
                                                                $style = 'float:left; width:48%; margin:0 1% 12px;';
                                                            }
                                                        } else {
                                                            // ✅ Mixtas (por si acaso)
                                                            $style = ($count == 3 && $loop->last)
                                                                ? 'float:none; display:block; width:70%; margin:0 auto 12px;'
                                                                : 'float:left; width:48%; margin:0 1% 12px;';
                                                        }
                                                    @endphp

                                                    <div style="{{ $style }} text-align:center;">
                                                        {{-- Leyenda arriba --}}
                                                        <p style="margin:0 0 6px; line-height:1.2;">
                                                            <b>Imagen {{ $imgNum++ }}</b><br>
                                                            <i>{{ $img['leyenda'] }}</i>
                                                        </p>

                                                        {{-- Imagen --}}
                                                        <img src="{{ storage_path('app/public/'.$img['src']) }}"
                                                            style="width:100%; height:auto; object-fit:contain;">
                                                    </div>

                                                    {{-- Limpiar flotantes después de cada par de horizontales --}}
                                                    @if ($allH && (($loop->iteration % 2) == 0) && !($loop->last && $count == 3))
                                                        <div style="clear:both;"></div>
                                                    @endif
                                                @endforeach
                                                <div style="clear:both;"></div>
                                            </div>
                                        @endif
                                        <br>
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