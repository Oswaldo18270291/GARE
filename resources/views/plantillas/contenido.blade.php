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
            text-indent: 2.5em;   /* sangría de la primera línea (~5 espacios) */
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
                    <a style="display: block; text-align: center; font-weight: bold; text-indent: 0;">
                        <span style="color: transparent; font-size: 0;">__MARKER_TITLE_{{ $title->id }}__</span>
                        {{ $loop->iteration }}. {{ title_case_except($title->title->nombre) }}
                    </a>

                    @foreach ($title->content as $cont)
                        @php
                            $path = storage_path('app/public/'.$cont->img1);
                            $orientation = 'vertical';

                            if (file_exists($path)) {
                                [$width, $height] = getimagesize($path);
                                $orientation = $width > $height ? 'horizontal' : 'vertical';
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
                        <a style="display: block; text-align: justify; font-weight: bold; text-indent: 0;">
                            <span style="color: transparent; font-size: 0;">__MARKER_SUBTITLE_{{ $subtitle->id }}__</span>
                            {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($subtitle->subtitle->nombre) }}
                        </a>
                        @foreach ($subtitle->content as $cont)
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
                                                <td style="border: 1px solid black; padding: 5px;">xd</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->f_ocurrencia }}</td>
                                            </tr>
                                        @endforeach
                                    </table>  
                                @endif   
                                @if($cont->reportTitleSubtitle()->where('subtitle_id',15))
                                    <table id="tabla">
                                        <!-- Encabezado Cibernéticos -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white;">
                                            <td colspan="2">Cibernéticos</td>
                                        </tr>
                                        <tbody id="ciberneticos" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'ciberneticos')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}">
                                                    <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                                                    <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <!-- Encabezado Naturales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white;">
                                            <td colspan="2">Naturales</td>
                                        </tr>
                                        <tbody id="naturales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'naturales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}">
                                                    <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                                                    <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Encabezado Sociales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color: white">
                                            <td colspan="2">Sociales (Personas)</td>
                                        </tr>
                                        <tbody id="sociales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'sociales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}">
                                                    <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                                                    <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
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
                                                <td style="border: 1px solid black; padding: 5px;">xd</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $diagram->f_ocurrencia }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                                @if($cont->reportTitleSubtitle()->where('subtitle_id',15))
                                    <table id="tabla">
                                        <!-- Encabezado Cibernéticos -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white;">
                                            <td colspan="2">Cibernéticos</td>
                                        </tr>
                                        <tbody id="ciberneticos" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'ciberneticos')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}">
                                                    <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                                                    <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <!-- Encabezado Naturales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color:white;">
                                            <td colspan="2">Naturales</td>
                                        </tr>
                                        <tbody id="naturales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'naturales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}">
                                                    <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                                                    <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Encabezado Sociales -->
                                        <tr style="background-color: #0f4a75ff; font-weight: bold; color: white">
                                            <td colspan="2">Sociales (Personas)</td>
                                        </tr>
                                        <tbody id="sociales" wire:ignore>
                                            @foreach ($diagrama->where('tipo_riesgo', 'sociales')->sortBy('orden') as $r)
                                                <tr data-id="{{ $r->id }}">
                                                    <td style="width: 40px; text-align: center;">{{ $r->orden }}</td>
                                                    <td>{{ $r->no }} - {{ $r->riesgo }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                @endif

                            @endif
                        @endforeach
                        {{-- Secciones dentro del subtítulo --}}
                        @foreach ($subtitle->sections->sortBy('id') as $section)
                            <a style="display: block; text-align: justify; font-weight: bold; font-style: italic; text-indent: 0;">
                                <span style="color: transparent; font-size: 0;">__MARKER_SECTION_{{ $section->id }}__</span>
                                {{ $loop->parent->parent->iteration }}.{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ title_case_except($section->section->nombre) }}
                            </a>

                            @foreach ($section->content as $cont)
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