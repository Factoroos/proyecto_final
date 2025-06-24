<?php
// Inicia la sesión actual o recupera la existente
session_start();

// Destruye todos los datos de la sesión
session_destroy();

// Redirige al inicio público del sitio después del logout
header("Location: https://teclab.uct.cl/~jorge.sepulveda/");

// Finaliza la ejecución del script
exit();
?>

