<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 aspect-video md:aspect-auto">
                <x-placeholder-pattern class="absolute inset-0 size-full dark:stroke-neutral-100/20" />
                <img src="inicio/definicion.png">
            </div>
            <div class="relative overflow-hidden rounded-xl border p-4" style="border-color:rgba(31, 89, 177, 1);">
                <x-placeholder-pattern class="absolute inset-0 size-full dark:stroke-neutral-100/20" />
                <p class="text-sm text-black relative z-10">
                    GARE (Gestión de Análisis de Riesgos Evaluados) es un sistema diseñado para la Secretaría de Seguridad Pública, cuyo propósito es facilitar la captura, organización y generación de informes de análisis de riesgos.
                    <br><br>
                    Este sistema permite a los usuarios:</p> 
                    <ul class="list-disc pl-6 mt-4 text-sm relative z-10 space-y-2">
                        <li>Registrar y estructurar de manera ordenada la información requerida.</li>
                        <li>Generar automáticamente un archivo PDF con el formato y la estructura oficial correspondiente.</li>
                        <li>Optimizar el tiempo de elaboración de informes, garantizando uniformidad, precisión y respaldo documental.</li>
                        <li>En esencia, GARE centraliza y estandariza el proceso de elaboración de reportes de análisis de riesgo, fortaleciendo la eficiencia y la transparencia institucional.</li>
                    </ul>
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
