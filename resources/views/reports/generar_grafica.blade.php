<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar gráfica</title>
  <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Plugin 3D -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d"></script>
<!-- ECharts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

</head>
<body style="background:#fff;">
<!-- 🔹 Pantalla de carga -->
<div id="loader" style="
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(255,255,255,0.95);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    font-family: 'Segoe UI', sans-serif;
    color: #0f4a75;
">
    <div class="spinner" style="
        border: 6px solid #f3f3f3;
        border-top: 6px solid #0f4a75;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    "></div>
    <p style="margin-top: 20px; font-size: 18px; font-weight: 600;">
        Generando gráfica, por favor espere...
    </p>
</div>

<!-- 🔹 Animación del spinner -->
<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<!-- 🔹 Tu canvas (oculto) -->


<canvas id="grafica" width="600" height="300" style="display:none;"></canvas>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const loader = document.getElementById('loader'); // referencia al loader
    const ctx = document.getElementById('grafica').getContext('2d');
    
    const riesg = @json($risks->sortBy('no')->map(fn($r) => $r->no)->values());
    const riesgos = @json($risks->sortBy('no')->map(fn($r) => $r->no . ' - ' . $r->riesgo)->values());
    const ocurrencias = @json($risks->sortBy('no')->pluck('f_ocurrencia')->values());
    const tipo = @json($grafica);

    const colores = ocurrencias.map(v => {
        if (v >= 80) return "rgba(206, 0, 0, 0.9)";
        if (v >= 60) return "rgba(235, 231, 0, 0.9)";
        if (v >= 40) return "rgba(4, 121, 0, 0.9)";
        return "rgba(102, 209, 98, 0.9)";
    });

    const esCircular = ['pie', 'doughnut', 'polarArea'].includes(tipo);

    const dataConfig = esCircular
      ? {
          labels: riesgos,
          labe: riesg,
          datasets: [{
            label: 'Factor de ocurrencia',
            data: ocurrencias,
            backgroundColor: colores
          }]
        }
      : {
          labels: ['Factor de ocurrencia'],
          datasets: riesgos.map((nombre, i) => ({
            label: nombre,
            data: [ocurrencias[i]],
            backgroundColor: colores[i],
            numero: riesg[i],   
          }))
        };

    const chart = new Chart(ctx, {
        type: tipo,
        data: dataConfig,
        options: {
            responsive: false,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#000',
                        font: { size: 12, weight: 'bold' },
                        boxWidth: 15,
                        padding: 8
                    }
                },
                datalabels: {
                    display: true,
                    color: '#000',
                    anchor: tipo === 'bar' ? 'end' : 'center',
                    align: tipo === 'bar' ? 'end' : 'center',
                    font: { weight: 'bold', size: 10 },
                    formatter: (value, ctx) => {
                        if (esCircular) {
                            return `${ctx.chart.data.labe[ctx.dataIndex]}\n(${value})`;
                        } else {
                            const dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                            return `${dataset.numero} (${value})`;
                        }
                    }
                }
            },
            scales: esCircular ? {} : {
                x: {
                    ticks: { color: '#000', font: { size: 11 } },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { color: '#000', font: { size: 11 } },
                    grid: { color: '#ddd' }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Espera que se renderice todo
    await new Promise(resolve => setTimeout(resolve, 1000));

    // 🔹 Redimensionar la imagen antes de exportar
    const originalCanvas = document.getElementById('grafica');
    const scaledCanvas = document.createElement('canvas');
    const scale = 0.7; // reduce al 70%
    scaledCanvas.width = originalCanvas.width * scale;
    scaledCanvas.height = originalCanvas.height * scale;

    const scaledCtx = scaledCanvas.getContext('2d');
    scaledCtx.scale(scale, scale);
    scaledCtx.drawImage(originalCanvas, 0, 0);

    // Convierte la gráfica a imagen base64
    const base64 = document.getElementById('grafica').toDataURL('image/png');
    console.log("⚙️ Generando gráfica...");
    console.log("Base64 length:", base64.length);

    // Envía la imagen al backend para guardarla
    await fetch("{{ route('guardar.imagen.grafica', $report->id) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ imagen: base64 })
    });

    console.log("✅ Imagen enviada al servidor");

    // 🔹 Oculta el loader y redirige al PDF actualizado
    loader.style.display = 'none';
    window.location.href = "{{ route('reporte.pdf', $report->id) }}";
});
</script>



</body>
</html>
