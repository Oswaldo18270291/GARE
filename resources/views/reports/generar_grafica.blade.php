<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar gráfica</title>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Plugins -->
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
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

  <style>
  @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }
  </style>

  <!-- 🔹 Canvas -->
  <canvas id="grafica" width="500" height="300" style="display:none;"></canvas>

  <script>
document.addEventListener('DOMContentLoaded', async function () {
    const loader = document.getElementById('loader');
    const canvas = document.getElementById('grafica');
    const ctx = canvas.getContext('2d');

    const riesg = @json($risks->sortBy('no')->map(fn($r) => $r->no)->values());
    const riesgos = @json($risks->sortBy('no')->map(fn($r) => $r->no . ' - ' . $r->riesgo)->values());
    const ocurrencias = @json($risks->sortBy('no')->pluck('factor_oc')->values());
    const tipo = @json($grafica);

    const colores = ocurrencias.map(v => {
        if (v >= 80) return "rgba(206, 0, 0, 0.9)";
        if (v >= 60) return "rgba(235, 231, 0, 0.9)";
        if (v >= 40) return "rgba(4, 121, 0, 0.9)";
        return "rgba(102, 209, 98, 0.9)";
    });

    const esCircular = ['pie', 'doughnut', 'polarArea'].includes(tipo);
    const esHorizontal = tipo === 'bar' && false;

    // 🟢 Aumentar tamaño del canvas para gráficas circulares
    if (esCircular) {
        canvas.width = 480;
        canvas.height = 480;
    } else {
        canvas.width = 500;
        canvas.height = 300;
    }

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
            layout: { padding: { top: 20 } },
            responsive: false,
            maintainAspectRatio: false,
            animation: false,
            indexAxis: esHorizontal ? 'y' : 'x',
            plugins: {
                title: {
                    display: true,
                    text: 'Gráfica de exposición general',
                    color: '#000',
                    font: { size: 14, family: 'Segoe UI' },
                    padding: { top: 2, bottom: 20 }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { color: '#000', font: { size: 10 }, boxWidth: 15, padding: 8 }
                },
                datalabels: {
                    display: true,
                    color: '#000',
                    anchor: tipo === 'bar' ? 'end' : 'center',
                    align: tipo === 'bar' ? 'end' : 'center',
                    font: { size: esCircular ? 11 : 9 }, // 🟢 Etiquetas más grandes en circular
                    formatter: (value, ctx) => {
                        if (esCircular) {
                            return `${ctx.chart.data.labe[ctx.dataIndex]}\n(${value})`;
                        } else {
                            const dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                            return `${dataset.numero}[${value}]`;
                        }
                    }
                }
            },
            scales: esCircular ? {} : esHorizontal
              ? {
                  x: { beginAtZero: true, max: 100, grace: '10%', ticks: { color: '#000', font: { size: 9 } }, grid: { color: '#ddd' } },
                  y: { ticks: { color: '#000', font: { size: 9 } }, grid: { display: false } }
              }
              : {
                  x: { ticks: { color: '#000', font: { size: 9 } }, grid: { display: false } },
                  y: { beginAtZero: true, max: 100, grace: '10%', ticks: { color: '#000', font: { size: 9 } }, grid: { color: '#ddd' } }
              }
        },
        plugins: [ChartDataLabels]
    });

    await new Promise(resolve => setTimeout(resolve, 1000));

    const base64 = canvas.toDataURL('image/png');

    await fetch("{{ route('guardar.imagen.grafica', $report->id) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ imagen: base64 })
    });

    loader.style.display = 'none';
    window.location.href = "{{ route('reporte.pdf', $report->id) }}";
});
</script>

</body>
</html>
