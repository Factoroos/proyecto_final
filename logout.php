<?php
// Inicia la sesión actual o recupera la existente.
// Esto es necesario para poder destruirla después.
session_start();

// Destruye todos los datos asociados con la sesión actual.
// Esto cierra efectivamente la sesión del usuario (logout).
session_destroy();

// Redirige al usuario al formulario de login (login.php)
// después de cerrar la sesión.
header("Location: login.php");

// Finaliza el script para asegurarse de que no se ejecute más código.
exit();
?>
