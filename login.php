<?php
// Mostrar errores en pantalla (útil para depuración en entorno de desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Iniciar la sesión para poder usar variables de sesión
session_start();

// Incluir solo una vez el archivo de conexión a la base de datos
require_once 'api/config.php'; // Se asume que $conn queda disponible

// Inicializar variable para guardar posibles errores
$error = "";

// Validar si se recibió una solicitud POST (cuando el formulario se envía)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Capturar los datos del formulario
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Preparar la consulta para evitar inyecciones SQL
  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // Vincular el parámetro
  $stmt->execute(); // Ejecutar la consulta
  $result = $stmt->get_result(); // Obtener los resultados

  // Verificar si se encontró un usuario con ese nombre
  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc(); // Obtener la fila con los datos

    // Comparar contraseñas (la de la BD ya está en md5)
    if (md5($password) === $row['password']) {
      // Autenticación correcta: guardar usuario en sesión y redirigir
      $_SESSION['user'] = $username;
      header("Location: crud/index.php");
      exit();
    } else {
      // Contraseña incorrecta
      $error = "Contraseña incorrecta.";
    }
  } else {
    // Usuario no encontrado en la base de datos
    $error = "Usuario no encontrado.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión</title>
  <!-- Bootstrap 5 vía CDN para estilos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <!-- Tarjeta de login centrada vertical y horizontalmente -->
  <div class="card p-4 shadow" style="min-width: 300px;">
    <h2 class="text-center mb-4">Login</h2>

    <!-- Mostrar mensaje de error si lo hay -->
    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Formulario de inicio de sesión -->
    <form method="post">
      <input type="text" name="username" placeholder="Usuario" autocomplete="off" required class="form-control mb-3">
      <input type="password" name="password" placeholder="Contraseña" autocomplete="off" required class="form-control mb-3">
      <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
  </div>
</body>
</html>





