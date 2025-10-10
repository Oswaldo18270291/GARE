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

        // 🔹 Unir <ol> consecutivos
        $html = preg_replace('/<\/ol>\s*<ol[^>]*>/', '', $html);

        // 🔹 Estilos que respetan jerarquía (1., a., i.)
        $style = '
            <style>
                /* Nivel 1 */
                ol {
                    counter-reset: item;
                    list-style-type: none;
                    padding-left: 1.5em;
                }
                ol > li {
                    counter-increment: item;
                    margin-bottom: 4px;
                    text-align: justify;
                }
                ol > li::before {
                    content: counter(item, decimal) ". ";
                    font-weight: normal;
                }

                /* Nivel 2 */
                ol ol {
                    counter-reset: subitem;
                    list-style-type: none;
                    margin-left: 1.5em;
                }
                ol ol > li {
                    counter-increment: subitem;
                }
                ol ol > li::before {
                    content: counter(subitem, lower-alpha) ". ";
                }

                /* Nivel 3 */
                ol ol ol {
                    counter-reset: subsubitem;
                    margin-left: 1.5em;
                }
                ol ol ol > li {
                    counter-increment: subsubitem;
                }
                ol ol ol > li::before {
                    content: counter(subsubitem, lower-roman) ". ";
                }

                /* 🔹 Sublistas <ul> */
                ul {
                    list-style-type: disc;
                    margin-left: 2em;
                    padding-left: 0.5em;
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


