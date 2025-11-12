<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar gráficas</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <style>
  @keyframes spin {0% {transform:rotate(0deg);}100% {transform:rotate(360deg);}}
  </style>
</head>
<body style="background:#fff; margin:0;">

<!-- 🔹 Pantalla de carga -->
<div id="loader" style="
  position: fixed; inset: 0;
  background: rgba(255,255,255,0.95);
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  z-index: 9999; color: #0f4a75; font-family: Segoe UI;">
  <div style="border:6px solid #f3f3f3; border-top:6px solid #0f4a75; border-radius:50%; width:60px; height:60px; animation:spin 1s linear infinite;"></div>
  <p id="progress-text" style="margin-top:20px; font-size:18px; font-weight:600;">Generando gráficas...</p>
</div>

<!-- 🔹 Canvases invisibles -->
@foreach($graficas as $g)
  <canvas id="grafica_{{ $g['subtitleId'] }}" width="500" height="300" style="visibility:hidden; position:absolute; top:-9999px;"></canvas>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', async function () {
  const defs = @json($graficas);
  const progress = document.getElementById('progress-text');

  function coloresByValue(ocurrencias) {
    return ocurrencias.map(v => {
            if (v >= 84) return "#ff0000";
            if (v >= 64) return "#ff6600";
            if (v >= 44) return "#ffc000";
            if (v >= 1) return "#75d5ecff";
            return "rgba(102, 209, 98, 0.9)";
    });
  }

  async function renderAndSave(def, index, total) {
    progress.innerText = `Generando gráfica ${index + 1} de ${total}...`;

    const canvas = document.getElementById('grafica_' + def.subtitleId);
    const ctx = canvas.getContext('2d');

    const riesgos = def.risks.map(r => r.no + " - " + r.riesgo);
    const riesg = def.risks.map(r => r.no);
    const ocurrencias = def.risks.map(r => r.factor_oc);
    const colores = coloresByValue(ocurrencias);
    const tipo = def.tipo;
    const esCircular = ['pie','doughnut','polarArea'].includes(tipo);

    if (esCircular) {
      canvas.width = 480;
      canvas.height = 480;
    } else {
      canvas.width = 600;
      canvas.height = 350;
    }

    await new Promise(resolve => {
      const chart = new Chart(ctx, {
        type: tipo,
        data: esCircular
          ? { labels: riesgos, labe: riesg, datasets: [{ data: ocurrencias, backgroundColor: colores }] }
          : { labels: ['Factor de ocurrencia'], datasets: riesgos.map((n, i) => ({ label: n, data: [ocurrencias[i]], backgroundColor: colores[i], numero: riesg[i] })) },
        options: {
          responsive: false,
          maintainAspectRatio: false,
          animation: {
            duration: 800,
            onComplete: async () => {
              await new Promise(r => setTimeout(r, 500)); // Esperar un poco más tras render
              const base64 = canvas.toDataURL('image/png');

              await fetch("{{ route('guardar.imagen.grafica', $report->id) }}", {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({ imagen: base64, subtitleId: def.subtitleId })
              });

              chart.destroy();
              resolve();
            }
          },
          plugins: {
            title: { display: true, text: 'Gráfica de exposición general', color: '#000', font: { size: 14, family: 'Segoe UI' } },
            legend: { display: true, position: 'bottom', labels: { color: '#000', font: { size: 10 } } },
            datalabels: {
              display: true,
              color: '#000',
              anchor: tipo === 'bar' ? 'end' : 'center',
              align:  tipo === 'bar' ? 'end' : 'center',
              font: { size: esCircular ? 11 : 9 },
              formatter: (v, ctx) => esCircular
                ? `${ctx.chart.data.labe[ctx.dataIndex]}\n(${v})`
                : `${ctx.chart.data.datasets[ctx.datasetIndex].numero}[${v}]`
            }
          },
          scales: esCircular ? {} : {
            y: { beginAtZero: true, max: 100, ticks: { color: '#000' }, grid: { color: '#ddd' } }
          }
        },
        plugins: [ChartDataLabels]
      });
    });
  }

  // 🔹 Ejecutar todas las gráficas secuencialmente
  for (let i = 0; i < defs.length; i++) {
    await renderAndSave(defs[i], i, defs.length);
  }

  progress.innerText = "Cargando...";
  window.location.href = "{{ route('reporte.generarMapa', $report->id) }}";});
</script>

</body>
</html>
