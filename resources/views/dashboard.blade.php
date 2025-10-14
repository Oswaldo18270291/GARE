<x-layouts.app :title="__('Dashboard')">
    <div x-data="{            
    // Sets the time between each slides in milliseconds
    autoplayIntervalTime: 6000,
    slides: [                
        {                    
            imgSrc: '{{ asset('carrusel/SSPC.png') }}',                  
            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',  
        },
        {                    
            imgSrc: '{{ asset('carrusel/definicion.png') }}',                  
            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',  
        },   
        {
            imgSrc: '{{ asset('carrusel/vision.png') }}',
            imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',  
        },
        {                    
            imgSrc: '{{ asset('carrusel/mision.png') }}',                  
            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',  
        },             
                    
    ],            
    currentSlideIndex: 1,
    isPaused: false,
    autoplayInterval: null,
    previous() {                
        if (this.currentSlideIndex > 1) {                    
            this.currentSlideIndex = this.currentSlideIndex - 1                
        } else {   
            // If it's the first slide, go to the last slide           
            this.currentSlideIndex = this.slides.length                
        }            
    },            
    next() {                
        if (this.currentSlideIndex < this.slides.length) {                    
            this.currentSlideIndex = this.currentSlideIndex + 1                
        } else {                 
            // If it's the last slide, go to the first slide    
            this.currentSlideIndex = 1                
        }            
    },    
    autoplay() {
        this.autoplayInterval = setInterval(() => {
            if (! this.isPaused) {
                this.next()
            }
        }, this.autoplayIntervalTime)
    },
    // Updates interval time   
    setAutoplayInterval(newIntervalTime) {
        clearInterval(this.autoplayInterval)
        this.autoplayIntervalTime = newIntervalTime
        this.autoplay()
    },    
}" x-init="autoplay" class="relative w-full overflow-hidden rounded-xl border border-border bg-surface shadow-lg dark:border-border-dark dark:bg-surface-dark">
   
    <!-- previous button -->
    <button type="button" class="absolute left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-surface/40 p-2 text-on-surface transition hover:bg-surface/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:bg-surface-dark/40 dark:text-on-surface-dark dark:hover:bg-surface-dark/60 dark:focus-visible:outline-primary-dark" aria-label="previous slide" x-on:click="previous()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pr-0.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </button>

    <!-- next button -->
    <button type="button" class="absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-surface/40 p-2 text-on-surface transition hover:bg-surface/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:bg-surface-dark/40 dark:text-on-surface-dark dark:hover:bg-surface-dark/60 dark:focus-visible:outline-primary-dark" aria-label="next slide" x-on:click="next()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pl-0.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>    

    <!-- slides -->
    <!-- Change min-h-[50svh] to your preferred height size -->
    <div class="relative w-full aspect-[16/9] sm:aspect-[4/3] md:aspect-[16/9] lg:aspect-[21/9]">
        <template x-for="(slide, index) in slides">
            <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                
                <!-- Title and description -->
                <div class="lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 px-16 py-12 text-center">
                    <h3 class="w-full lg:w-[80%] text-balance text-2xl lg:text-3xl font-bold text-zinc-50" x-text="slide.title" x-bind:aria-describedby="'slide' + (index + 1) + 'Description'"></h3>
                    <p class="lg:w-1/2 w-full text-pretty text-sm text-zinc-200" x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                </div>

                <img class="absolute inset-0 h-full w-full object-cover object-center" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />

            </div>
        </template>
    </div>
    
    <!-- Pause/Play Button -->
    <button type="button" class="absolute bottom-5 right-5 z-20 rounded-full text-zinc-200 opacity-50 transition hover:opacity-80 focus-visible:opacity-80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 active:outline-offset-0" aria-label="pause carousel" x-on:click="(isPaused = !isPaused), setAutoplayInterval(autoplayIntervalTime)" x-bind:aria-pressed="isPaused">
        <svg x-cloak x-show="isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-7">
            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z" clip-rule="evenodd">
        </svg>
        <svg x-cloak x-show="!isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-7">
            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm5-2.25A.75.75 0 0 1 7.75 7h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Zm4 0a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Z" clip-rule="evenodd">
        </svg>
    </button>
    
    <!-- indicators -->
    <div class="absolute rounded-sm bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" role="group" aria-label="slides" >
        <template x-for="(slide, index) in slides">
            <button class="size-2 rounded-full transition" x-on:click="(currentSlideIndex = index + 1), setAutoplayInterval(autoplayIntervalTime)" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-zinc-200' : 'bg-zinc-200/50']" x-bind:aria-label="'slide ' + (index + 1)"></button>
        </template>
    </div>
</div>
@role('admin')
<br>
<article class="group flex rounded-radius p-4  flex-col overflow-hidden border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">

        <div class="mb-6 flex items-center justify-center p-4 rounded-lg" style="background-color: rgba(39, 68, 112, 1);">
            <h1 class=" text-white font-sans font-bond text-lg">DASHBOARD</h1>
        </div>
<div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
    <article class="flex items-center gap-4 rounded-lg border border-blue-500 bg-white p-2">
        <span class="rounded-full bg-blue-100 p-3 text-blue-600">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
            >
            <path strokeLinecap="round" strokeLinejoin="round" 
            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />

            </svg>
        </span>

        <div>
            <p class="text-2xl font-medium text-gray-900">{{$totalAnual}} </p>

            <p class="text-sm text-gray-700">Total de informes en {{$anioActual}}</p>
        </div>
    </article>
        <article class="flex items-center gap-4 rounded-lg border border-blue-500 bg-white p-2">
        <span class="rounded-full bg-blue-100 p-3 text-blue-600">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
            >
            <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />

            </svg>
        </span>

        <div>
            <p class="text-2xl font-medium text-gray-900">{{$totalPublicos}}</p>

            <p class="text-sm text-gray-700">Total de informes públicos</p>
        </div>
    </article>
        <article class="flex items-center gap-4 rounded-lg border border-blue-500 bg-white p-2">
        <span class="rounded-full bg-blue-100 p-3 text-blue-600">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
            >
            <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />


            </svg>
        </span>

        <div>
            <p class="text-2xl font-medium text-gray-900">{{$total}}</p>

            <p class="text-sm text-gray-700">Total de informes</p>
        </div>
    </article>
        <article class="flex items-center gap-4 rounded-lg border border-blue-500 bg-white p-2">
        <span class="rounded-full bg-blue-100 p-3 text-blue-600">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
            >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
            />
            </svg>
        </span>

        <div>
            <p class="text-2xl font-medium text-gray-900">{{$totalInformesFinalizados}}</p>

            <p class="text-sm text-gray-700">Total de informes finalizados</p>
        </div>
    </article>
</div>

<div class="mt-10 bg-white p-4 rounded-lg shadow border border-blue-500">
    <h2 class="text-center text-lg font-bold mb-4 text-gray-800">
        Informes generados en {{ $anioActual }}
    </h2>
    <canvas id="lineChart" width="800" height="400"></canvas>
</div>

<script>
function renderLineChart() {
    const canvas = document.getElementById('lineChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const meses = @json($mesesEspanol);
    const totales = @json($totales);
    const anio = @json($anioActual);

    // Destruye el gráfico anterior si existe
    if (window.lineChartInstance) {
        window.lineChartInstance.destroy();
    }

    window.lineChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Reportes creados',
                data: totales,
                borderColor: 'rgba(39, 68, 112, 1)',
                backgroundColor: 'rgba(39, 68, 112, 0.2)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: 'rgba(39, 68, 112, 1)',
                pointBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
                title: {
                    display: true,
                    text: `Cantidad de informes creados por mes (${anio})`,
                    font: { size: 16 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Total de informes' },
                    ticks: { precision: 0 }
                },
                x: {
                    title: { display: true, text: 'Meses' }
                }
            }
        }
    });
}

// 🔹 Ejecutar al cargar la página normal
document.addEventListener('DOMContentLoaded', renderLineChart);

// 🔹 Ejecutar al navegar con Livewire
document.addEventListener('livewire:navigated', () => setTimeout(renderLineChart, 100));

// 🔹 (opcional) Ejecutar también cuando Livewire actualiza el DOM
if (window.Livewire) {
    Livewire.hook('morph.updated', () => setTimeout(renderLineChart, 100));
}
</script>


</article>
@endrole
</x-layouts.app>
