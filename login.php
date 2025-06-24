<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'api/config.php'; // Solo esto, NO repitas la conexión

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (md5($password) === $row['password']) {
      $_SESSION['user'] = $username;
      header("Location: crud/index.php");
      exit();
    } else {
      $error = "Contraseña incorrecta.";
    }
  } else {
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow" style="min-width: 300px;">
    <h2 class="text-center mb-4">Login</h2>
    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Usuario" autocomplete="off" required class="form-control mb-3">
      <input type="password" name="password" placeholder="Contraseña" autocomplete="off" required class="form-control mb-3">
      <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
  </div>
</body>
</html>




