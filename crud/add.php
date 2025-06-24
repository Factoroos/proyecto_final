<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $url_github = $_POST['url_github'];
  $url_produccion = $_POST['url_produccion'];

  // Validación y guardado de imagen
  $target_dir = "../uploads/";
  $nombre_img = basename($_FILES['imagen']['name']);
  $target_file = $target_dir . $nombre_img;

  // ✅ Declarar las variables que estaban dando error
  $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
  $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
  $ext = strtolower(pathinfo($nombre_img, PATHINFO_EXTENSION));

  if (
    in_array($_FILES["imagen"]["type"], $allowedTypes) &&
    in_array($ext, $allowedExts) &&
    $_FILES["imagen"]["size"] < 2 * 1024 * 1024
  ) {
    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
      die("Error al mover el archivo de imagen al directorio.");
    }
  } else {
    die("Imagen no válida. Solo JPG, PNG o WEBP menores a 2MB.");
  }

  // Datos del proyecto
  $data = [
    'titulo' => $titulo,
    'descripcion' => $descripcion,
    'url_github' => $url_github,
    'url_produccion' => $url_produccion,
    'imagen' => $nombre_img
  ];

  // Enviar a la API
  $ch = curl_init('https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/api/proyectos.php');
  curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode($data)
  ]);

  $response = curl_exec($ch);

if (!$response) {
  die("❌ Error al llamar a la API: " . curl_error($ch));
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

curl_close($ch);

// Redirigir a la página principal después de guardar
header("Location: index.php");
exit();


}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Proyecto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4">Agregar Proyecto</h1>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título del Proyecto</label>
        <input type="text" class="form-control" id="titulo" name="titulo" required>
      </div>

      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
      </div>

      <div class="mb-3">
        <label for="url_github" class="form-label">URL de GitHub</label>
        <input type="url" class="form-control" id="url_github" name="url_github">
      </div>

      <div class="mb-3">
        <label for="url_produccion" class="form-label">URL del Proyecto en Producción</label>
        <input type="url" class="form-control" id="url_produccion" name="url_produccion">
      </div>

      <div class="mb-3">
        <label for="imagen" class="form-label">Imagen del Proyecto (JPG, PNG, WEBP - Máx 2MB)</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp" required>
      </div>

      <button type="submit" class="btn btn-success">Guardar Proyecto</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>



