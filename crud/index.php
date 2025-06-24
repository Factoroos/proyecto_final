<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$apiUrl = 'https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/api/proyectos.php';
$proyectos = json_decode(file_get_contents($apiUrl), true);

// Validación de respuesta de la API
if (!is_array($proyectos)) {
    $proyectos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Proyectos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="m-0">Proyectos</h1>
     <a href="../logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>

    <a href="add.php" class="btn btn-success mb-3">Agregar Proyecto</a>

    <div class="row">
      <?php foreach ($proyectos as $proyecto): ?>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="../uploads/<?php echo htmlspecialchars($proyecto['imagen']); ?>" class="card-img-top" alt="Imagen del proyecto">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($proyecto['titulo']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($proyecto['descripcion']); ?></p>
              <div class="d-grid gap-2">
                <?php if (!empty($proyecto['url_github'])): ?>
                  <a href="<?php echo htmlspecialchars($proyecto['url_github']); ?>" target="_blank" class="btn btn-dark">GitHub</a>
                <?php endif; ?>
                <?php if (!empty($proyecto['url_produccion'])): ?>
                  <a href="<?php echo htmlspecialchars($proyecto['url_produccion']); ?>" target="_blank" class="btn btn-primary">Ver en Línea</a>
                <?php endif; ?>
                <a href="edit.php?id=<?php echo $proyecto['id']; ?>" class="btn btn-warning">Editar</a>
                <a href="delete.php?id=<?php echo $proyecto['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">Eliminar</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>


