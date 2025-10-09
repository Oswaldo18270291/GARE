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
