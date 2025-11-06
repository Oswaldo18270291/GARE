<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar mapa mental</title>
  <script src="https://unpkg.com/vis-network@9.1.2/standalone/umd/vis-network.min.js"></script>
</head>
<body style="background:#fff; margin:0;">

<!-- 🔹 Pantalla de carga -->
<div id="loader" style="
  position: fixed; inset: 0;
  background: rgba(255,255,255,0.95);
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  z-index: 9999; color: #0f4a75; font-family: Segoe UI;">
  <div style="
    border: 6px solid #f3f3f3;
    border-top: 6px solid #0f4a75;
    border-radius: 50%;
    width: 60px; height: 60px;
    animation: spin 1s linear infinite;">
  </div>
  <p style="margin-top: 20px; font-size: 18px; font-weight: 600;">Generando mapa mental...</p>
</div>

<style>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<!-- 🔹 Contenedor del mapa -->
<div id="network" style="width:1000px; height:700px; margin:auto; background:#fafafa;"></div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const container = document.getElementById('network');
    const loader = document.getElementById('loader');

    const nodos = @json($nodos ?? []);
    const relaciones = @json($relaciones ?? []);
    const fondo = @json($background_image ?? null);
    const opacidad = @json($background_opacity ?? 0.4);

    const nodes = new vis.DataSet(nodos);
    const edges = new vis.DataSet(relaciones);

    const options = {
        nodes: {
            shape: "circle",
            size: 40,
            borderWidth: 2,
            shadow: true,
            font: { color: "#111", face: "Arial", size: 14 },
        },
        edges: {
            color: { color: "#999" },
            smooth: true,
            width: 2,
        },
        physics: { enabled: true, stabilization: { iterations: 150 } },
        interaction: { dragView: true, zoomView: true, dragNodes: false, selectable: false }
    };

    const network = new vis.Network(container, { nodes, edges }, options);

    if (fondo) {
        container.style.backgroundImage = `url('${fondo}')`;
        container.style.backgroundSize = 'cover';
        container.style.opacity = opacidad;
    }

    // Esperar a que termine de dibujar
    await new Promise(resolve => setTimeout(resolve, 2000));

    // Convertir a imagen
    const dataUrl = network.canvas.frame.canvas.toDataURL("image/png");

    // Guardar imagen
    await fetch("{{ route('reporte.guardarMapa', $report->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ imagen: dataUrl })
    });

    loader.style.display = 'none';
    window.location.href = "{{ route('reporte.pdf', $report->id) }}";
});
</script>

</body>
</html>
