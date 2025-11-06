<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar mapa mental</title>
  <script src="https://unpkg.com/vis-network@9.1.2/standalone/umd/vis-network.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
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

<!-- 🔹 CONTENEDOR FUERA DE PANTALLA -->
<div id="network-wrapper" style="
  position:absolute;
  top:-2000px; left:0; /* 👈 esto lo oculta visualmente */
  width:1000px;
  height:780px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:flex-start;
  background:#fff;
  overflow:hidden;
  border-radius:10px;
  box-shadow:0 0 8px rgba(0,0,0,0.1);
">

  <!-- 🔹 Título principal -->
  <div id="titulo-mapa" style="
    width:100%;
    text-align:center;
    font-family:'Segoe UI', sans-serif;
    color:rgba(116, 11, 11, 1);
    padding-top:5px;
    font-weight:700;
    font-size:22px;
    letter-spacing:1px;
    z-index:3;
  ">
    Mapa Mental - Interacción de Riesgos en Instalaciones <br>
    {{ $report->nombre_empresa }}
  </div>

  <!-- 🔹 Fondo -->
  <div id="network-bg" style="
    position:absolute;
    top:80px;
    left:0; right:0; bottom:0;
    z-index:0;
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    opacity:0.4;">
  </div>

  <!-- 🔹 Canvas del mapa -->
  <div id="network" style="
    position:absolute;
    top:120px;
    left:0; right:0; bottom:0;
    z-index:2;
    background:transparent;">
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
  const wrapper = document.getElementById('network-wrapper');
  const bgLayer = document.getElementById('network-bg');
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

  if (fondo) {
    bgLayer.style.backgroundImage = `url('${fondo}')`;
    bgLayer.style.opacity = opacidad;
  }

  const network = new vis.Network(container, { nodes, edges }, options);

  // Esperar a que se renderice bien el mapa
  await new Promise(resolve => setTimeout(resolve, 2000));

  // 📸 Capturar todo el contenido (aunque esté fuera de pantalla)
  const canvasImage = await html2canvas(wrapper, {
    useCORS: true,
    backgroundColor: null,
    scale: 2,
    logging: false
  });

  const dataUrl = canvasImage.toDataURL("image/png");

  // 💾 Guardar imagen
  await fetch("{{ route('reporte.guardarMapa', $report->id) }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({ imagen: dataUrl })
  });

  // ✅ Ir directamente al PDF
  window.location.href = "{{ route('reporte.pdf', $report->id) }}";
});
</script>

</body>
</html>
