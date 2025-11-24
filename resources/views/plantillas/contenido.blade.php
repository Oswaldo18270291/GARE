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
            margin-bottom: 3cm;
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

            p {
                margin: 0 0 6px 0; /* espaciado normal entre párrafos */
                line-height: 1.4; /* interlineado agradable */
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
                        <a style="display: block; font-weight: bold; margin-bottom: 4px;">
                            <span style="color: transparent; font-size: 0;">__MARKER_TITLE_{{ $title->id }}__</span>
                            {{ $loop->iteration }}. {{ title_case_except($title->title->nombre) }}
                        </a>
                        @foreach ($title->content as $cont)
                            @if (empty(trim($cont->cont)))
                            
@php
/*****************************************************************
    0) CONTADOR GLOBAL UNA SOLA VEZ
*****************************************************************/
if (!isset($globalImageNumber)) {
    $globalImageNumber = 1;
}

/*****************************************************************
    1) RECOLECTAR TODAS LAS IMÁGENES
*****************************************************************/
$imgs = [];

$addImage = function($src, $leyenda, $orden) use (&$imgs) {
    $full = storage_path("app/public/".$src);
    $size = @getimagesize($full);
    $w = $size[0] ?? 800;
    $h = $size[1] ?? 800;

    // orientación + factor de espacio
    $o = ($w > $h) ? "h" : "v";

    // "peso" según tamaño:
    // horizontal grande = 2 slots
    // vertical normal = 1 slot
    $slot = ($o === "h") ? 2 : 1;

    $imgs[] = [
        'src' => $src,
        'leyenda' => $leyenda,
        'orden_imagen' => $orden,
        'o' => $o,
        'w' => $w,
        'h' => $h,
        'slot' => $slot
    ];
};

// imágenes sueltas
foreach (['img1','img2','img3'] as $i) {
    if (!empty($cont->{$i})) {
        $addImage(
            $cont->{$i},
            $cont->{'leyenda'.substr($i,-1)} ?? '',
            99998
        );
    }
}

// imágenes json
if (!empty($cont->img_block) && is_array($cont->img_block)) {
    foreach ($cont->img_block as $bl) {
        if (!isset($bl['src'])) continue;
        $addImage(
            trim($bl['src']),
            $bl['leyenda'] ?? '',
            $bl['orden_imagen'] ?? 99999
        );
    }
}

/*****************************************************************
    2) ORDENAR
*****************************************************************/
usort($imgs, fn($a,$b)=>($a['orden_imagen']??99999) <=> ($b['orden_imagen']??99999));

/*****************************************************************
    3) AGRUPAR DINÁMICAMENTE POR FILA (SLOTS)
*****************************************************************/

// Una fila tiene capacidad máxima de: 4 slots
// Ejemplos:
// - H (2) + H (2) = fila llena
// - H (2) + V (1) + V (1) = fila llena
// - V (1)+V (1)+V (1) = 3 slots (válido)
// - V (1)+V (1)+V (1)+V (1)=4 slots
// - H (2) solo = válido

$rows = [];
$currentRow = [];
$currentSlots = 0;

foreach ($imgs as $img) {

    // Si no cabe en la fila actual => cerramos fila
    if ($currentSlots + $img['slot'] > 4) {
        $rows[] = $currentRow;
        $currentRow = [];
        $currentSlots = 0;
    }

    $currentRow[] = $img;
    $currentSlots += $img['slot'];
}

if (count($currentRow)) {
    $rows[] = $currentRow;
}

@endphp

@if (count($rows))
<div style="margin-top:10px; width:100%; overflow:hidden;">

@foreach ($rows as $row)
    @php
        $count = count($row);
    @endphp

    {{-- 🔥 FILA DE IMÁGENES --}}
    <div style="width:100%; text-align:center; margin-bottom:10px;">

        @foreach ($row as $img)
            @php
                /***************************************************************
                 * LÓGICA DE TAMAÑO SEGÚN ORIENTACIÓN Y CANTIDAD
                 ***************************************************************/
                $w = $img['w'] ?? 800;
                $h = $img['h'] ?? 800;
                $ratio = ($w > 0) ? ($h / $w) : 1;

                if ($count === 1) {
                    // 🟢 Una sola imagen
                    $isFullPage = true;

                    if (abs($ratio - 1) < 0.1) {
                        // 🔵 Cuadrada → ocupa toda la hoja
                        $imgStyle = 'max-width:95%; max-height:95vh;';
                    } elseif ($ratio > 1.1) {
                        // 🟣 Vertical → ocupa toda la altura
                        $imgStyle = 'max-height:95vh; width:auto;';
                    } else {
                        // 🟠 Horizontal → ocupa todo el ancho
                        $imgStyle = 'max-width:95%; height:auto;';
                    }

                    $containerStyle = '
                        display:flex;
                        flex-direction:column;
                        align-items:center;
                        justify-content:center;
                        height:100vh;
                        width:100%;
                        page-break-before:always;
                        text-align:center;
                        page-break-inside:avoid;
                    ';
                } else {
                    // 🔹 Varias imágenes
                    $isFullPage = false;
                    if ($count === 2)      $wImg = '48%';
                    elseif ($count === 3) $wImg = '31%';
                    else                  $wImg = '23%';
                    if (($img['o'] ?? 'h') === 'v') $wImg = '28%';

                    $imgStyle = 'max-width:100%; max-height:550px;';
                    $containerStyle = "
                        display:inline-block;
                        vertical-align:top;
                        width:$wImg;
                        margin:6px 0.6%;
                        text-align:center;
                        line-height:1.2;
                        page-break-inside:avoid;
                    ";
                }
            @endphp

            {{-- 🖼️ BLOQUE COMPLETO (IMAGEN + LEYENDA) --}}
            <div style="{{ $containerStyle }}">
                                {{-- 🔹 LEYENDA Y NÚMERO (siempre visible) --}}
                <div style="
                    margin-top:8px;
                    font-size:11pt;
                    padding-bottom:8px;
                    font-weight:bold;
                    text-align:center;
                    width:90%;
                    line-height:1.2;
                ">
                    Imagen {{ $globalImageNumber++ }}
                    <div style="font-weight:normal; font-style:italic;">
                        {{ $img['leyenda'] }}
                    </div>
                </div>
                {{-- 🖼️ IMAGEN --}}
                <img src="{{ storage_path('app/public/'.$img['src']) }}"
                    style="
                        display:block;
                        margin:0 auto;
                        object-fit:contain;
                        {{ $imgStyle }}
                    ">


            </div>
        @endforeach
    </div>
@endforeach
</div>
@endif







                                <br>
                                @if($cont->reportTitle->title_id==12)
                                    @if($cont->cotizaciones->count())
                                            <div style="page-break-before: always;">
                                                <table class="w-full border-collapse font-sans" style="border:1px solid #001a4d; border-collapse:collapse;">
                                                    <thead>
                                                        <tr class="border border-dotted border-white text-center">
                                                            <td colspan="5" class="border border-dotted border-white px-1 p-4 font-bold"
                                                                style="font-size: 10pt; background-color:#002060; color:white; text-align:center;">
                                                                COTIZACIONES DE SISTEMAS TECNOLÓGICOS
                                                            </td>
                                                        </tr>
                                                        <tr style="background-color:#002060; color:white; border:1px solid #001a4d; font-size:10pt;">
                                                            <th style="border:1px solid #ffffffff; padding:8px;">EMPRESA</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">CONCEPTO</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">CANT.</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">COSTO SIN IVA</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">COMENTARIOS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cont->cotizaciones as $empresa)
                                                            @foreach($empresa->detalles as $index => $detalle)
                                                                <tr style="background-color: {{ $empresa->color }};">
                                                                    @if($index === 0)
                                                                        <td rowspan="{{ $empresa->detalles->count() }}"
                                                                            class="border font-semibold text-left align-top"
                                                                            style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                            {{ $empresa->nombre }}
                                                                        </td>
                                                                    @endif
                                                                    <td class="border text-left"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->concepto }}
                                                                    </td>
                                                                    <td class="border text-center"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->cantidad }}
                                                                    </td>
                                                                    <td class="border text-center"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->costo }}
                                                                    </td>
                                                                    <td class="border text-center"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->comentarios }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    @endif

                                @endif
                            @else
                                <span style="color:white; font-size:1px;">__MARKER_CONTENT_{{ $cont->id }}__</span>

                                {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->cont))) !!}
@php
/*****************************************************************
    0) CONTADOR GLOBAL UNA SOLA VEZ
*****************************************************************/
if (!isset($globalImageNumber)) {
    $globalImageNumber = 1;
}

/*****************************************************************
    1) RECOLECTAR TODAS LAS IMÁGENES
*****************************************************************/
$imgs = [];

$addImage = function($src, $leyenda, $orden) use (&$imgs) {
    $full = storage_path("app/public/".$src);
    $size = @getimagesize($full);
    $w = $size[0] ?? 800;
    $h = $size[1] ?? 800;

    // orientación + factor de espacio
    $o = ($w > $h) ? "h" : "v";

    // "peso" según tamaño:
    // horizontal grande = 2 slots
    // vertical normal = 1 slot
    $slot = ($o === "h") ? 2 : 1;

    $imgs[] = [
        'src' => $src,
        'leyenda' => $leyenda,
        'orden_imagen' => $orden,
        'o' => $o,
        'w' => $w,
        'h' => $h,
        'slot' => $slot
    ];
};

// imágenes sueltas
foreach (['img1','img2','img3'] as $i) {
    if (!empty($cont->{$i})) {
        $addImage(
            $cont->{$i},
            $cont->{'leyenda'.substr($i,-1)} ?? '',
            99998
        );
    }
}

// imágenes json
if (!empty($cont->img_block) && is_array($cont->img_block)) {
    foreach ($cont->img_block as $bl) {
        if (!isset($bl['src'])) continue;
        $addImage(
            trim($bl['src']),
            $bl['leyenda'] ?? '',
            $bl['orden_imagen'] ?? 99999
        );
    }
}

/*****************************************************************
    2) ORDENAR
*****************************************************************/
usort($imgs, fn($a,$b)=>($a['orden_imagen']??99999) <=> ($b['orden_imagen']??99999));

/*****************************************************************
    3) AGRUPAR DINÁMICAMENTE POR FILA (SLOTS)
*****************************************************************/

// Una fila tiene capacidad máxima de: 4 slots
// Ejemplos:
// - H (2) + H (2) = fila llena
// - H (2) + V (1) + V (1) = fila llena
// - V (1)+V (1)+V (1) = 3 slots (válido)
// - V (1)+V (1)+V (1)+V (1)=4 slots
// - H (2) solo = válido

$rows = [];
$currentRow = [];
$currentSlots = 0;

foreach ($imgs as $img) {

    // Si no cabe en la fila actual => cerramos fila
    if ($currentSlots + $img['slot'] > 4) {
        $rows[] = $currentRow;
        $currentRow = [];
        $currentSlots = 0;
    }

    $currentRow[] = $img;
    $currentSlots += $img['slot'];
}

if (count($currentRow)) {
    $rows[] = $currentRow;
}

@endphp

@if (count($rows))
<div style="margin-top:10px; width:100%; overflow:hidden;">

@foreach ($rows as $row)
    @php
        $count = count($row);
    @endphp

    {{-- 🔥 FILA DE IMÁGENES --}}
    <div style="width:100%; text-align:center; margin-bottom:10px;">

        @foreach ($row as $img)
            @php
                /***************************************************************
                 * LÓGICA DE TAMAÑO SEGÚN ORIENTACIÓN Y CANTIDAD
                 ***************************************************************/
                $w = $img['w'] ?? 800;
                $h = $img['h'] ?? 800;
                $ratio = ($w > 0) ? ($h / $w) : 1;

                if ($count === 1) {
                    // 🟢 Una sola imagen
                    $isFullPage = true;

                    if (abs($ratio - 1) < 0.1) {
                        // 🔵 Cuadrada → ocupa toda la hoja
                        $imgStyle = 'max-width:95%; max-height:95vh;';
                    } elseif ($ratio > 1.1) {
                        // 🟣 Vertical → ocupa toda la altura
                        $imgStyle = 'max-height:95vh; width:auto;';
                    } else {
                        // 🟠 Horizontal → ocupa todo el ancho
                        $imgStyle = 'max-width:95%; height:auto;';
                    }

                    $containerStyle = '
                        display:flex;
                        flex-direction:column;
                        align-items:center;
                        justify-content:center;
                        height:100vh;
                        width:100%;
                        page-break-before:always;
                        text-align:center;
                        page-break-inside:avoid;
                    ';
                } else {
                    // 🔹 Varias imágenes
                    $isFullPage = false;
                    if ($count === 2)      $wImg = '48%';
                    elseif ($count === 3) $wImg = '31%';
                    else                  $wImg = '23%';
                    if (($img['o'] ?? 'h') === 'v') $wImg = '28%';

                    $imgStyle = 'max-width:100%; max-height:550px;';
                    $containerStyle = "
                        display:inline-block;
                        vertical-align:top;
                        width:$wImg;
                        margin:6px 0.6%;
                        text-align:center;
                        line-height:1.2;
                        page-break-inside:avoid;
                    ";
                }
            @endphp

            {{-- 🖼️ BLOQUE COMPLETO (IMAGEN + LEYENDA) --}}
            <div style="{{ $containerStyle }}">
                                {{-- 🔹 LEYENDA Y NÚMERO (siempre visible) --}}
                <div style="
                    margin-top:8px;
                    padding-bottom:8px;
                    font-size:11pt;
                    font-weight:bold;
                    text-align:center;
                    width:90%;
                    line-height:1.2;
                ">
                    Imagen {{ $globalImageNumber++ }}
                    <div style="font-weight:normal; font-style:italic;">
                        {{ $img['leyenda'] }}
                    </div>
                </div>
                {{-- 🖼️ IMAGEN --}}
                <img src="{{ storage_path('app/public/'.$img['src']) }}"
                    style="
                        display:block;
                        margin:0 auto;
                        object-fit:contain;
                        {{ $imgStyle }}
                    ">

            </div>
        @endforeach
    </div>
@endforeach
</div>
@endif




                                <br>
                                 @if($cont->reportTitle->title_id==12)
                                    @if($cont->cotizaciones->count())
                                            <div style="page-break-before: always;">
                                                <table class="w-full border-collapse font-sans" style="border:1px solid #001a4d; border-collapse:collapse;">
                                                    <thead>
                                                        <tr class="border border-dotted border-white text-center">
                                                            <td colspan="5" class="border border-dotted border-white px-1 p-4 font-bold"
                                                                style="font-size: 10pt; background-color:#002060; color:white; text-align:center;">
                                                                COTIZACIONES DE SISTEMAS TECNOLÓGICOS
                                                            </td>
                                                        </tr>
                                                        <tr style="background-color:#002060; color:white; border:1px solid #001a4d; font-size:10pt;">
                                                            <th style="border:1px solid #ffffffff; padding:8px;">EMPRESA</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">CONCEPTO</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">CANT.</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">COSTO SIN IVA</th>
                                                            <th style="border:1px solid #ffffffff; padding:8px;">COMENTARIOS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cont->cotizaciones as $empresa)
                                                            @foreach($empresa->detalles as $index => $detalle)
                                                                <tr style="background-color: {{ $empresa->color }};">
                                                                    @if($index === 0)
                                                                        <td rowspan="{{ $empresa->detalles->count() }}"
                                                                            class="border font-semibold text-left align-top"
                                                                            style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                            {{ $empresa->nombre }}
                                                                        </td>
                                                                    @endif
                                                                    <td class="border text-left"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->concepto }}
                                                                    </td>
                                                                    <td class="border text-center"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->cantidad }}
                                                                    </td>
                                                                    <td class="border text-center"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->costo }}
                                                                    </td>
                                                                    <td class="border text-center"
                                                                        style="border:1px solid black; padding:8px; font-size:8pt;">
                                                                        {{ $detalle->comentarios }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    @endif

                                @endif
                            @endif
                        @endforeach

                        {{-- Subtítulos dentro del título --}}
                        @foreach ($title->subtitles as $subtitle)
                            <a style="display: block; text-align: justify; font-weight: bold; margin-bottom: 4px;">
                                <span style="color: transparent; font-size: 0;">__MARKER_SUBTITLE_{{ $subtitle->id }}__</span>
                                {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($subtitle->subtitle->nombre) }}
                            </a>
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
                                    @if($cont->reportTitleSubtitle->subtitle_id==32)
                                        <div>
                                            <table class="w-full text-center font-sans" style="border-collapse: collapse; width: 100%; font-size: 10pt; ">
                                                <thead>
                                                    <tr style="background-color:#002060; color:white;">
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">No.</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Tipo de Riesgo</th>
                                                        <th colspan="5" style="border:1px solid #ffffffff;" class="border p-2">Criterios de Evaluación</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Total<br>Posible</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Cal.</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Clase de Riesgo</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Factor de ocurrencia<br>del riesgo</th>
                                                    </tr>
                                                    <tr style="background-color:#002060; color:white;">
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/funciones.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/organizacion.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/ext_daño.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/materializacion.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/financiero.png" width="30">
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($diagrama->sortBy('no') as $diag)
                                                        <tr>
                                                            <td style="border: 1px solid #000; padding: 4px;">{{ $diag->no }}</td>
                                                            <td style="border: 1px solid #000; padding: 4px;">{{ $diag->riesgo }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->impacto_f }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->impacto_o }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->extension_d }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->probabilidad_m }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->impacto_fin }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">25</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->cal }}</td>
                                                            <td
                                                                style=" border: 1px solid #000; text-align: center;
                                                                background-color:
                                                                    {{ ($diag->clase_riesgo ?? '') == 'MUY ALTO' ? '#ff0000' :
                                                                    (($diag->clase_riesgo ?? '') == 'ALTO' ? '#ff6600' :
                                                                    (($diag->clase_riesgo ?? '') == 'MEDIO' ? '#ffc000' :
                                                                    (($diag->clase_riesgo ?? '') == 'BAJO' ? '#75d5ecff' : 'transparent'))) }}">
                                                                {{ $diag->clase_riesgo ?? '' }}
                                                            </td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ number_format((float) ( $diag->factor_oc ?? 0), 2) . '%' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        @if(!empty($cont->img_mapa))
                                            <div class="justify-center items-center bg-blue-100 place-items-center">
                                                <img src="storage/{{ $cont->img_mapa }}" style="page-break-before: always; margin-top: 0.5cm;width: 16.5cm; height: auto;"/>
                                            </div>
                                        @endif
                                        @if(!empty($cont->img_grafica))
                                            <div class="justify-center items-center bg-blue-100 place-items-center">
                                                <img src="storage/{{ $cont->img_grafica }}" style="page-break-before: always; margin-top: 1.5cm;"/>
                                            </div>
                                        @endif
                                        
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
                                     {{--ACA VA LA OTRA GRAFICAAAAA --}}
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
      @if($cont->reportTitleSubtitle->subtitle_id==17)
                                    <!-- BLOQUE ENTERO: INSEPARABLE -->
                                <div style="border: 1px dotted black">
                                    <div style="
                                        width:100%;
                                        position:relative;
                                        min-height:20px;
                                        page-break-inside: avoid !important;
                                        border-bottom: 1px dotted black
                                    ">
                                        <div style="
                                            width:24%;
                                            background:#002060;
                                            color:white;
                                            font-weight:bold;
                                            padding:8px;
                                            position:absolute;
                                            font-size:12px;
                                            text-align:center;
                                            font-weight:bold;
                                            border-right: 1px dotted rgb(255, 255, 255)
                                        ">
                                            ACCIONES DIVERSAS
                                        </div>

                                        <div style="
                                            margin-left:25%;
                                            padding:8px;
                                            overflow-wrap: break-word;
                                            font-size:12px;
                                            background:#002060;
                                            color:white;
                                            text-align:center;
                                            font-weight:bold;
                                            
                                        ">
                                            TRATAMIENTO GENERAL DE LOS RIESGOS IDENTIFICADOS
                                        </div>
                                    </div>

                                        <!-- ===================== FILA 1 ===================== -->
                                    <div style="
                                        width:100%;
                                        position:relative;
                                        page-break-inside:auto !important;
                                    ">
                                        <!-- 🟦 CONTENIDO A LA DERECHA -->
                                        <div style="
                                            border-left:160px solid #002060;
                                            padding:8px;
                                            text-align:justify;
                                            overflow-wrap:break-word;
                                            font-size:10px;
                                            line-height:1.3;
                                            position: relative;
                                            border-bottom: 1px dotted black

                                        ">
                                            <div style="
                                                position: absolute;
                                                top: 15%;
                                                left: -110px;
                                                transform: translate(-50%, -50%);
                                                color: rgb(255, 255, 255);
                                                align-items: center;  
                                                font-size:15px;
                                                font-weight:bold;
                                                width: 20%;
                                            ">
                                                Medidas preventivas actuales
                                            </div>
                                        {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->contenido_m_p_a))) !!}
                                        </div>

                                    </div>

                                        <!-- ===================== FILA 2 ===================== -->
                                    <div style="
                                        width:100%;
                                        position:relative;
                                        page-break-inside:auto !important;
                                    ">
                                        <!-- 🟢 CONTENIDO CON “BANDA” IZQUIERDA SIMULADA -->
                                        <div style="
                                            border-left:160px solid #002060;
                                            padding:10px;
                                            text-align:justify;
                                            overflow-wrap:break-word;
                                            font-size:10px;
                                            line-height:1.3;
                                            position: relative;
                                        ">
                                            <div style="
                                                position: absolute;
                                                top: 50%;
                                                left: -110px;
                                                transform: translate(-50%, -50%);
                                                color: white;
                                                align-items: center;  
                                                font-size:15px;
                                                font-weight:bold;
                                                width: 20%;
                                            ">
                                                Acciones / Planes por realizar
                                            </div>
                                            {!! fix_quill_lists(convert_quill_indents_to_nested_lists(limpiarHtml($cont->contenido_a_p))) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                                    @if ($cont->reportTitleSubtitle->subtitle_id==18)
                                    <table class="w-full border-collapse text-center text-sm font-sans" 
                                            style="border:1px solid #ffffffff; border-collapse:collapse;">
                                        <thead>
                                            <tr class="bg-[#002060] font-bold text-center border border-dotted border-white">
                                                <td colspan="5" class="border border-dotted border-white px-1 p-4 font-bold text-white">ORGANIGRAMA DE CONTROLES GENERALES DE ACTUACIÓN</td>
                                            </tr>
                                            <tr style="background-color:#002060; color:white; border:1px solid #001a4d;">
                                            <th style="border:1px solid #ffffffff; padding:8px; width:5%;">No.</th>
                                            <th style="border:1px solid #ffffffff; padding:8px; width:15%;">Tipo de Riesgo</th>
                                            <th style="border:1px solid #ffffffff; padding:8px; width:25%;">Medidas preventivas actuales</th>
                                            <th style="border:1px solid #ffffffff; padding:8px; width:40%;">Acciones / Planes por realizar</th>
                                            <th style="border:1px solid #ffffffff; padding:8px; width:15%;">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cont->organigramaControls as $organigrama)
                                            <tr>
                                                <td class="border p-1" style="border:1px solid #000000ff;">{{ $organigrama->no }}</td>
                                                <td class="border p-1 text-left" style="border:1px solid #000000ff;">{{ $organigrama->riesgo}}</td>

                                                <td style="border:1px solid #000000ff; padding:6px;">
                                                    {{ $organigrama->medidas_p }}
                                                </td>

                                                <td style="border:1px solid #000000ff; padding:6px;">
                                                    {{ $organigrama->acciones_planes }}
                                                </td>
                                                <td style="border:1px solid #000000ff; padding:6px;"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif

                                    @if ($cont->reportTitleSubtitle->subtitle_id==33)
                                        <style>
                                        .foda-wrapper {
                                            position: relative;
                                            width: 100%;
                                            display: block;           /* ✅ fuerza al bloque a ocupar su propio espacio vertical */
                                            height: auto;             /* ✅ se ajusta al contenido */
                                            page-break-inside: avoid; /* ✅ evita que se divida en páginas */
                                            text-align: center;
                                            margin-top: 40px;   
                                            margin-bottom: 40px;      /* ✅ agrega espacio antes para no invadir texto anterior */
                                        }

                                        /* Contenedor general */
                                        .foda-container {
                                            position: relative;
                                            width: 450px;
                                            height: 450px;
                                            margin: 0 auto;
                                            page-break-inside: avoid; /* ✅ No separar en páginas */
                                        }

                                        /* Círculo principal */
                                        .foda-circle {
                                            position: relative;
                                            width:  450px;
                                            height: 450px;
                                            border-radius: 50%;
                                            overflow: hidden;
                                            box-shadow: 0 0 10px rgba(0,0,0,0.15);
                                        }

                                        /* Cuadrantes */
                                        .fortalezas, .debilidades, .oportunidades, .amenazas {
                                            position: absolute;
                                            width: 50%;
                                            height: 50%;
                                            color: white;
                                            font-weight: bold;
                                            display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            text-align: center;
                                            font-size: 18px;
                                            letter-spacing: 1px;
                                        }

                                        .fortalezas { background: #F47B20; top: -2; left: -2; border-top-left-radius: 100%; }
                                        .debilidades { background: #808285; top: -2; right: -2; border-top-right-radius: 100%; }
                                        .oportunidades { background: #0072BC; bottom: -2; left: -2; border-bottom-left-radius: 100%; }
                                        .amenazas { background: #FDB913; bottom: -2; right: -2; border-bottom-right-radius: 100%; }

                                        /* Flecha central */
                                        .center {
                                            position: absolute;
                                            top: 50%; left: 50%;
                                            transform: translate(-50%, -50%);
                                            z-index: 10;
                                        }

                                        .center img {
                                            width: 80px;
                                            height: 80px;
                                        }

                                        /* Cuadros de texto */
                                        .box {
                                            position: absolute;
                                            width: 230px;
                                            background: rgba(255, 255, 255, 0.38);
                                            border: 2px solid;
                                            border-radius: 12px;
                                            padding: 10px 15px;
                                            text-align: left;
                                            font-size: 13px;
                                            /* line-height: 1.4;*/
                                            z-index: 20;
                                            flex-direction: column-reverse;
                                        }

                                        /* Posiciones ancladas al círculo */
                                        .fort-box { top: -22px; left: -120px; border-color: #F47B20; }
                                        .deb-box  { top: -22px; right: -120px; border-color: #808285; }
                                        .opo-box  { bottom: -24px; left: -120px; border-color: #0072BC; }
                                        .ame-box  { bottom: -24px; right: -120px; border-color: #FDB913; }

                                        .box ul {
                                            margin: 0;
                                            padding-left: 18px;
                                            color: #002060;
                                        }

                                        .box li {
                                            margin-bottom: 5px;
                                        }

                                        </style>

                                        <div class="foda-wrapper">

                                <div style="page-break-inside: avoid; display:inline-block; text-align:center; width:100%;">
                                    <div class="foda-container">

                                        <!-- 🔵 Círculo -->
                                        <div class="foda-circle">
                                            <div class="fortalezas"><br><br><br><br><br><br>FORTALEZAS</div>
                                            <div class="debilidades"><br><br><br><br><br><br>DEBILIDADES</div>
                                            <div class="oportunidades"><br><br><br>OPORTUNIDADES</div>
                                            <div class="amenazas"><br><br><br>AMENAZAS</div>

                                            <div class="center">
                                                <img style="display: flex; " src="contenido/ciclo.png">
                                            </div>
                                        </div>

                                        <!-- 📋 Cuadros transparentes anclados -->
                                        <div class="box fort-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->fortalezas ?? 'Sin información')))) !!}
                                        </div>
                                        <div class="box deb-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->debilidades ?? 'Sin información')))) !!}
                                        </div>
                                        <div class="box opo-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->oportunidades ?? 'Sin información')))) !!}
                                        </div>
                                        <div class="box ame-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->amenazas ?? 'Sin información')))) !!}
                                        </div>
                                    </div>
                                </div>
                                 </div>
                                    @endif

                                        @if ($cont->reportTitleSubtitle->subtitle_id == 38)
                                        <style>
                                            body { font-family: Helvetica, Arial, sans-serif; }
                                            table { page-break-inside: avoid; }
                                            tr { page-break-inside: avoid; }
                                        </style>

                                        {{-- 🔹 Encabezado --}}
                                        <table style="width:100%; border-collapse:collapse; text-align:center; font-size:13px;">
                                            <thead>
                                                <tr style="background-color:#002060; color:white; font-weight:bold;">
                                                    <th colspan="9" style="border:1px dotted white; padding:5px;">
                                                        RECOMENDACIONES Y ACCIONES DE SEGURIDAD FÍSICA
                                                    </th>
                                                </tr>
                                                <tr style="color:white; font-weight:bold;">
                                                    <th style="background-color:#002060; border:1px dotted white; padding:4px;">ALTO /<br>URGENTE<br>LO ANTES POSIBLE</th>
                                                    <th style="background-color:#C00000; border:1px dotted white; padding:4px;">URGENTE</th>
                                                    <th style="background-color:#002060; border:1px dotted white;"> </th>
                                                    <th style="background-color:#002060; border:1px dotted white; padding:4px;">MEDIANO /<br>IMPORTANTE<br>EN EL CORTO TIEMPO</th>
                                                    <th style="background-color:#FFFF00; color:black; border:1px dotted white; padding:4px;">MEDIO</th>
                                                    <th style="background-color:#002060; border:1px dotted white;"> </th>
                                                    <th style="background-color:#002060; border:1px dotted white; padding:4px;">BAJO /<br>OBLIGATORIA<br>REALIZAR A MEDIANO PLAZO</th>
                                                    <th style="background-color:#00B0F0; border:1px dotted white; padding:4px;">BAJO</th>
                                                    <th style="background-color:#002060; border:1px dotted white;"> </th>
                                                </tr>
                                            </thead>
                                        </table>

                                        {{-- 🔹 Contenido --}}
                                        <table style="width:100%; border-collapse:collapse; text-align:center; font-size:12px; margin-top:2px;">
                                            <thead>
                                                <tr style="background-color:#002060; color:white;">
                                                    <th style="border:1px dotted white; padding:4px; width:7%;">NO.</th>
                                                    <th style="border:1px dotted white; padding:4px; width:20%;">TEMA</th>
                                                    <th style="border:1px dotted white; padding:4px; width:50%;">ACCIÓN</th>
                                                    <th style="border:1px dotted white; padding:4px; width:10%;">TIENE<br>COSTO</th>
                                                    <th style="border:1px dotted white; padding:4px; width:13%;">NIVEL DE<br>PRIORIDAD</th>
                                                </tr>
                                            </thead>

                                        <tbody>
                                            @if (!empty($cont->accionSeguridad) && count($cont->accionSeguridad) > 0)
                                                @foreach ($cont->accionSeguridad->keys() as $titulo)
                                                    @php $temas = $cont->accionSeguridad[$titulo]; @endphp
                                                    <tr style="background-color:#FDE9D9; font-weight:bold;">
                                                        <td colspan="5" style="border:1px dotted black; padding:5px;">
                                                            {{ strtoupper($titulo ?? 'SIN TÍTULO') }}
                                                        </td>
                                                    </tr>

                                                    @foreach ($temas->sortBy('no') as $r)
                                                        @php
                                                            $bgColor = '';
                                                            $txtColor = 'black';
                                                            switch ($r->nivel_p) {
                                                                case 'urgente': $bgColor = '#C00000'; $txtColor = 'white'; break;
                                                                case 'medio': $bgColor = '#FFFF00'; $txtColor = 'black'; break;
                                                                case 'bajo': $bgColor = '#00B0F0'; $txtColor = 'white'; break;
                                                                case 'N/A': $bgColor = '#ffffffff'; $txtColor = 'white'; break;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td style="border:1px dotted black; padding:4px;"></td>
                                                            <td style="border:1px dotted black; padding:4px; text-align:left;">{{ $r->tema }}</td>
                                                            <td style="border:1px dotted black; padding:4px; text-align:left;">{!! $r->accion !!}</td>
                                                            <td style="border:1px dotted black; padding:4px; font-weight:bold;">{{ strtoupper($r->t_costo ?? '-') }}</td>
                                                            <td style="border:1px dotted black; padding:4px; font-weight:bold; background-color:{{ $bgColor }}; color:{{ $txtColor }};">
                                                                {{ strtoupper($r->nivel_p ?? '-') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>

                                        </table>
                                        @endif




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
                                    <span style="color:white; font-size:1px;">__MARKER_CONTENT_{{ $cont->id }}__</span>
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
                                    @if($cont->reportTitleSubtitle->subtitle_id==32)
                                        <div>
                                            <table class="w-full text-center font-sans" style="border-collapse: collapse; width: 100%; font-size: 10pt; ">
                                                <thead>
                                                    <tr style="background-color:#002060; color:white;">
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">No.</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Tipo de Riesgo</th>
                                                        <th colspan="5" style="border:1px solid #ffffffff;" class="border p-2">Criterios de Evaluación</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Total<br>Posible</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Cal.</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Clase de Riesgo</th>
                                                        <th rowspan="2" style="border:1px solid #ffffffff;" class="border p-2">Factor de ocurrencia<br>del riesgo</th>
                                                    </tr>
                                                    <tr style="background-color:#002060; color:white;">
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/funciones.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/organizacion.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/ext_daño.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/materializacion.png" width="30">
                                                        </th>
                                                        <th style="border:1px solid #ffffffff;">
                                                            <img src="contenido/img_matriz/financiero.png" width="30">
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($diagrama->sortBy('no') as $diag)
                                                        <tr>
                                                            <td style="border: 1px solid #000; padding: 4px;">{{ $diag->no }}</td>
                                                            <td style="border: 1px solid #000; padding: 4px;">{{ $diag->riesgo }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->impacto_f }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->impacto_o }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->extension_d }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->probabilidad_m }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->impacto_fin }}</td>
                                                            <td style="border: 1px solid #000; text-align: center">25</td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ $diag->cal }}</td>
                                                            <td
                                                                style=" border: 1px solid #000; text-align: center;
                                                                background-color:
                                                                    {{ ($diag->clase_riesgo ?? '') == 'MUY ALTO' ? '#ff0000' :
                                                                    (($diag->clase_riesgo ?? '') == 'ALTO' ? '#ff6600' :
                                                                    (($diag->clase_riesgo ?? '') == 'MEDIO' ? '#ffc000' :
                                                                    (($diag->clase_riesgo ?? '') == 'BAJO' ? '#75d5ecff' : 'transparent'))) }}">
                                                                {{ $diag->clase_riesgo ?? '' }}
                                                            </td>
                                                            <td style="border: 1px solid #000; text-align: center">{{ number_format((float) ( $diag->factor_oc ?? 0), 2) . '%' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        @if(!empty($cont->img_mapa))
                                            <div class="justify-center items-center bg-blue-100 place-items-center">
                                                <img src="storage/{{ $cont->img_mapa }}" style="page-break-before: always; margin-top: 0.5cm;width: 16.5cm; height: auto;"/>
                                            </div>
                                        @endif
                                        @if(!empty($cont->img_grafica))
                                            <div class="justify-center items-center bg-blue-100 place-items-center">
                                                <img src="storage/{{ $cont->img_grafica }}" style="page-break-before: always; margin-top: 1.5cm;"/>
                                            </div>
                                        @endif
                                        
                                    @endif
                                    @if ($cont->reportTitleSubtitle->subtitle_id==33)
                                        <style>
                                        .foda-wrapper {
                                            position: relative;
                                            width: 100%;
                                            display: block;           /* ✅ fuerza al bloque a ocupar su propio espacio vertical */
                                            height: auto;             /* ✅ se ajusta al contenido */
                                            page-break-inside: avoid; /* ✅ evita que se divida en páginas */
                                            text-align: center;
                                            margin-top: 40px;   
                                            margin-bottom: 40px;      /* ✅ agrega espacio antes para no invadir texto anterior */
                                        }

                                        /* Contenedor general */
                                        .foda-container {
                                            position: relative;
                                            width: 450px;
                                            height: 450px;
                                            margin: 0 auto;
                                            page-break-inside: avoid; /* ✅ No separar en páginas */
                                        }

                                        /* Círculo principal */
                                        .foda-circle {
                                            position: relative;
                                            width:  450px;
                                            height: 450px;
                                            border-radius: 50%;
                                            overflow: hidden;
                                            box-shadow: 0 0 10px rgba(0,0,0,0.15);
                                        }

                                        /* Cuadrantes */
                                        .fortalezas, .debilidades, .oportunidades, .amenazas {
                                            position: absolute;
                                            width: 50%;
                                            height: 50%;
                                            color: white;
                                            font-weight: bold;
                                            display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            text-align: center;
                                            font-size: 18px;
                                            letter-spacing: 1px;
                                        }

                                        .fortalezas { background: #F47B20; top: -2; left: -2; border-top-left-radius: 100%; }
                                        .debilidades { background: #808285; top: -2; right: -2; border-top-right-radius: 100%; }
                                        .oportunidades { background: #0072BC; bottom: -2; left: -2; border-bottom-left-radius: 100%; }
                                        .amenazas { background: #FDB913; bottom: -2; right: -2; border-bottom-right-radius: 100%; }

                                        /* Flecha central */
                                        .center {
                                            position: absolute;
                                            top: 50%; left: 50%;
                                            transform: translate(-50%, -50%);
                                            z-index: 10;
                                        }

                                        .center img {
                                            width: 80px;
                                            height: 80px;
                                        }

                                        /* Cuadros de texto */
                                        .box {
                                            position: absolute;
                                            width: 230px;
                                            background: rgba(255, 255, 255, 0.38);
                                            border: 2px solid;
                                            border-radius: 12px;
                                            padding: 10px 15px;
                                            text-align: left;
                                            font-size: 13px;
                                            /* line-height: 1.4;*/
                                            z-index: 20;
                                            flex-direction: column-reverse;
                                        }

                                        /* Posiciones ancladas al círculo */
                                        .fort-box { top: -50px; left: -120px; border-color: #F47B20; }
                                        .deb-box  { top: -50px; right: -120px; border-color: #808285; }
                                        .opo-box  { bottom: -50px; left: -120px; border-color: #0072BC; }
                                        .ame-box  { bottom: -50px; right: -120px; border-color: #FDB913; }

                                        .box ul {
                                            margin: 0;
                                            padding-left: 18px;
                                            color: #002060;
                                        }

                                        .box li {
                                            margin-bottom: 5px;
                                        }

                                        </style>

                                        <div class="foda-wrapper">

                                <div style="page-break-inside: avoid; display:inline-block; text-align:center; width:100%;">
                                    <div class="foda-container">

                                        <!-- 🔵 Círculo -->
                                        <div class="foda-circle">
                                            <div class="fortalezas"><br><br><br><br><br><br>FORTALEZAS</div>
                                            <div class="debilidades"><br><br><br><br><br><br>DEBILIDADES</div>
                                            <div class="oportunidades"><br><br><br>OPORTUNIDADES</div>
                                            <div class="amenazas"><br><br><br>AMENAZAS</div>

                                            <div class="center">
                                                <img style="display: flex; " src="contenido/ciclo.png">
                                            </div>
                                        </div>

                                        <!-- 📋 Cuadros transparentes anclados -->
                                        <div class="box fort-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->fortalezas ?? 'Sin información')))) !!}
                                        </div>
                                        <div class="box deb-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->debilidades ?? 'Sin información')))) !!}
                                        </div>
                                        <div class="box opo-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->oportunidades ?? 'Sin información')))) !!}
                                        </div>
                                        <div class="box ame-box">
                                            {!! nl2br(e(str_replace("\n", "\n• ", "• " . ($cont->fodas->first()?->amenazas ?? 'Sin información')))) !!}
                                        </div>
                                    </div>
                                </div>
                                 </div>
                                    @endif

                                    @if ($cont->reportTitleSubtitle->subtitle_id == 38)
                                        <!-- 🔒 BLOQUE COMPLETAMENTE INSEPARABLE -->
<div style="
    page-break-inside: avoid;
    break-inside: avoid;
    width:100%;
    margin-bottom:15px;
">

    <!-- 🔵 TABLA DE COLORES (SIN CAMBIOS) -->
    <table style="width:100%; border-collapse:collapse; text-align:center; font-size:13px;">
        <thead>
            <tr style="background-color:#002060; color:white; font-weight:bold;">
                <th colspan="9" style="border:1px dotted white; padding:5px;">
                    RECOMENDACIONES Y ACCIONES DE SEGURIDAD FÍSICA
                </th>
            </tr>
            <tr style="color:white; font-weight:bold;">
                <th style="background-color:#002060; border:1px dotted white; padding:4px;">ALTO /<br>URGENTE<br>LO ANTES POSIBLE</th>
                <th style="background-color:#C00000; border:1px dotted white; padding:4px;">URGENTE</th>
                <th style="background-color:#002060; border:1px dotted white;"></th>
                <th style="background-color:#002060; border:1px dotted white; padding:4px;">MEDIANO /<br>IMPORTANTE<br>EN EL CORTO TIEMPO</th>
                <th style="background-color:#FFFF00; color:black; border:1px dotted white; padding:4px;">MEDIO</th>
                <th style="background-color:#002060; border:1px dotted white;"></th>
                <th style="background-color:#002060; border:1px dotted white; padding:4px;">BAJO /<br>OBLIGATORIA<br>REALIZAR A MEDIANO PLAZO</th>
                <th style="background-color:#00B0F0; border:1px dotted white; padding:4px;">BAJO</th>
                <th style="background-color:#002060; border:1px dotted white;"></th>
            </tr>
        </thead>
    </table>


    <!-- 🔵 TABLA DE CONTENIDO (SIN CAMBIOS) -->
    <table style="width:100%; border-collapse:collapse; text-align:center; font-size:12px; margin-top:2px;">
        <thead>
            <tr style="background-color:#002060; color:white;">
                <th style="border:1px dotted white; padding:4px; width:7%;">NO.</th>
                <th style="border:1px dotted white; padding:4px; width:20%;">TEMA</th>
                <th style="border:1px dotted white; padding:4px; width:50%;">ACCIÓN</th>
                <th style="border:1px dotted white; padding:4px; width:10%;">TIENE<br>COSTO</th>
                <th style="border:1px dotted white; padding:4px; width:13%;">NIVEL DE<br>PRIORIDAD</th>
            </tr>
        </thead>

        <tbody>
            @if (!empty($cont->accionSeguridad) && count($cont->accionSeguridad) > 0)
                @foreach ($cont->accionSeguridad->keys() as $titulo)
                    @php $temas = $cont->accionSeguridad[$titulo]; @endphp

                    <tr style="background-color:#FDE9D9; font-weight:bold;">
                        <td colspan="5" style="border:1px dotted black; padding:5px;">
                            {{ strtoupper($titulo ?? 'SIN TÍTULO') }}
                        </td>
                    </tr>

                    @foreach ($temas->sortBy('no') as $r)
                        @php
                            $bgColor = '';
                            $txtColor = 'black';
                            switch ($r->nivel_p) {
                                case 'urgente': $bgColor = '#C00000'; $txtColor = 'white'; break;
                                case 'medio': $bgColor = '#FFFF00'; $txtColor = 'black'; break;
                                case 'bajo':  $bgColor = '#00B0F0'; $txtColor = 'white'; break;
                            }
                        @endphp

                        <tr>
                            <td style="border:1px dotted black; padding:4px;"></td>
                            <td style="border:1px dotted black; padding:4px; text-align:left;">{{ $r->tema }}</td>
                            <td style="border:1px dotted black; padding:4px; text-align:left;">{!! fix_quill_lists(limpiarHtml($r->accion)) !!}</td>
                            <td style="border:1px dotted black; padding:4px; font-weight:bold;">{{ strtoupper($r->t_costo ?? '-') }}</td>
                            <td style="border:1px dotted black; padding:4px; font-weight:bold; background-color:{{ $bgColor }}; color:{{ $txtColor }};">
                                {{ strtoupper($r->nivel_p ?? '-') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>

</div> <!-- FIN DEL BLOQUE INSEPARABLE -->

                                        @endif

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
                                @endif               
                            @endforeach
                            {{-- Secciones dentro del subtítulo --}}
                            @foreach ($subtitle->sections as $section)
                                <a style="display: block; text-align: justify; font-weight: bold; text-indent: 0.88cm; margin-bottom: 4px;">
                                    <span style="color: transparent; font-size: 0;">__MARKER_SECTION_{{ $section->id }}__</span>
                                    {{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($section->section->nombre) }}
                                </a>
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
                                        <span style="color:white; font-size:1px;">__MARKER_CONTENT_{{ $cont->id }}__</span>
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