<!DOCTYPE html>
<html>
<head>
  <style>
    @page {
      size: A4;
      margin: 0;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    body {
      background-image: url('fondo_portada/portada.png'); /* Reemplaza con tu imagen */
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
    }

    .contenido {
      position: relative;
      z-index: 1;
      padding: 2cm; /* Espaciado interno */
      color: rgb(102, 123, 216); /* O el color que contraste con tu fondo */
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div class="contenido">
    <h1>Hola, este es mi PDF con fondo</h1>
    <p>Contenido sobre la imagen de fondo.</p>
  </div>
</body>
</html>
