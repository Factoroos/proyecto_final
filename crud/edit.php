<?php
session_start();
include 'auth.php';

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}
$id = intval($_GET['id']);

// Obtener datos actuales del proyecto
$json = file_get_contents("https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/api/proyectos.php/$id");
$p = json_decode($json, true);

if (!$p) {
    die("Proyecto no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titulo' => $_POST['titulo'],
        'descripcion' => $_POST['descripcion'],
        'url_github' => $_POST['url_github'],
        'url_produccion' => $_POST['url_produccion']
    ];

    // Validar imagen si se cambia
    if (!empty($_FILES['imagen']['name'])) {
        $target_dir = "../uploads/";
        $img = basename($_FILES["imagen"]["name"]);
        $target_file = $target_dir . $img;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        if (
            in_array($_FILES["imagen"]["type"], $allowedTypes) &&
            in_array($ext, $allowedExts) &&
            $_FILES["imagen"]["size"] < 2 * 1024 * 1024
        ) {
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file);
            $data['imagen'] = $img;
        } else {
            die("Imagen no válida. Solo JPG, PNG o WEBP menores a 2MB.");
        }
    }

   // Enviar cambios a la API usando _method para PATCH simulado
$data['_method'] = 'PATCH';

$ch = curl_init("https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/api/proyectos.php/$id");
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => 'POST', // Se usa POST y se simula PATCH
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode($data)
]);
$response = curl_exec($ch);

if (!$response) {
    die("Error al llamar a la API: " . curl_error($ch));
}

curl_close($ch);
header("Location: index.php");
exit();

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Proyecto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Editar: <?= htmlspecialchars($p['titulo']) ?></h2>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input name="titulo" class="form-control" value="<?= htmlspecialchars($p['titulo']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($p['descripcion']) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">URL GitHub</label>
        <input name="url_github" type="url" class="form-control" value="<?= htmlspecialchars($p['url_github']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">URL Producción</label>
        <input name="url_produccion" type="url" class="form-control" value="<?= htmlspecialchars($p['url_produccion']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Imagen nueva (opcional)</label>
        <input type="file" name="imagen" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        <small class="text-muted">Imagen actual: <?= htmlspecialchars($p['imagen']) ?></small>
      </div>
      <button class="btn btn-primary">Actualizar</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>
