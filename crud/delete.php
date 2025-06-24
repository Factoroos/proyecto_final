<?php
session_start();
include '../auth.php';

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("ID inválido.");
}

$id = intval($_GET['id']);

// Enviar como POST con _method=DELETE
$data = ['_method' => 'DELETE'];

$ch = curl_init("https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/api/proyectos.php?id=$id");
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
  CURLOPT_POSTFIELDS => http_build_query($data)
]);

$response = curl_exec($ch);

if (!$response) {
  die("Error al conectar con la API: " . curl_error($ch));
}

$decoded = json_decode($response, true);
if (isset($decoded['error'])) {
  die("Error de la API: " . $decoded['error']);
}

curl_close($ch);

header("Location: index.php");
exit;



