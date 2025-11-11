<?php

if (! function_exists('title_case_except')) {
    function title_case_except($string) {
        $excepciones = ['de', 'la', 'y', 'en', 'con', 'para', 'a', 'o', 'el', 'del', 'los', 'las', 'un'];
        
        $palabras = explode(' ', strtolower($string));
        foreach ($palabras as $i => $palabra) {
            // Primera palabra siempre en mayúscula
            if ($i === 0 || !in_array($palabra, $excepciones)) {
                $palabras[$i] = ucfirst($palabra);
            }
        }
        return implode(' ', $palabras);

    }
}

if (! function_exists('limpiarHtml')) {
    function limpiarHtml($string) {
        // Eliminar párrafos vacíos con &nbsp; o <br>
        return preg_replace('/<p[^>]*>(&nbsp;|\s|<br\s*\/?>)*<\/p>/', '', $string);
    }
}

/**
 * Corrige la numeración de listas <ol> generadas por Quill
 * (une las listas consecutivas y mantiene el orden)
 */
if (!function_exists('fix_quill_lists')) {
    function fix_quill_lists($html)
    {
        if (!$html) return $html;

        // Unir <ol> consecutivos
        $html = preg_replace('/<\/ol>\s*<ol[^>]*>/', '', $html);

        $style = '
            <style>
                /* ===== Listas ordenadas ===== */
                ol {
                    list-style: none;
                    padding-left: 2em;
                    margin: 0 0 0.5em 0;
                    counter-reset: item alpha roman;
                }

                ol > li {
                    position: relative;
                    padding-left: 1.8em;
                    text-align: justify;
                    margin-bottom: 4px;
                    counter-increment: item;
                }

                /* 🔹 Nivel 0 → decimal (1., 2., 3.) */
                ol > li::before {
                    content: counter(item, decimal) ".";
                    position: absolute;
                    left: 0;
                    width: 1.2em;
                    text-align: right;
                }

                /* 🔹 Nivel 1 → alfabético (a., b., c.) */
                ol > li.ql-indent-1 {
                    counter-increment: alpha;
                    padding-left: 2.2em;   /* más espacio entre número y texto */
                }
                ol > li.ql-indent-1::before {
                    content: counter(alpha, lower-alpha) ".";
                    left: 0.3em;           /* mueve ligeramente la letra */
                }

                /* 🔹 Nivel 2 → romano (i., ii., iii.) */
                ol > li.ql-indent-2 {
                    counter-increment: roman;
                    padding-left: 4.4em;   /* sangría adicional */
                }
                ol > li.ql-indent-2::before {
                    content: counter(roman, lower-roman) ".";
                    left: 2.5em;           /* mueve el número romano junto con el texto */
                }

                /* ===== Viñetas ===== */
                ul {
                    list-style-type: disc;
                    list-style-position: outside;
                    margin-left: 2em;
                    padding-left: 0.5em;
                    text-align: justify;
                }

                ul > li {
                    margin-bottom: 4px;
                }
            </style>
        ';

        return $style . $html;
    }
}

if (!function_exists('convert_quill_indents_to_nested_lists')) {
    function convert_quill_indents_to_nested_lists($html)
    {
        // Usa DOMDocument para manipular el HTML
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        $xpath = new DOMXPath($dom);
        $lis = $xpath->query('//li[contains(@class, "ql-indent-")]');

        foreach ($lis as $li) {
            preg_match('/ql-indent-(\d+)/', $li->getAttribute('class'), $matches);
            $level = isset($matches[1]) ? intval($matches[1]) : 0;

            // Subir el elemento en su nivel correspondiente
            $parent = $li->parentNode;
            $prev = $li->previousSibling;

            // Buscar la lista padre adecuada
            while ($level > 0) {
                $newOl = $dom->createElement('ol');
                $li->parentNode->removeChild($li);
                $newOl->appendChild($li);
                if ($prev && $prev->nodeName === 'li') {
                    $prev->appendChild($newOl);
                } else {
                    $parent->appendChild($newOl);
                }
                $level--;
            }
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        return $dom->saveHTML($body);
    }
}


