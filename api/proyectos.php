<?php
include 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$rawMethod = $_SERVER['REQUEST_METHOD'];

// Soporte para _method en POST (por JSON o form-urlencoded)
if ($rawMethod === 'POST') {
    if (isset($_POST['_method'])) {
        $method = strtoupper($_POST['_method']);
    } else {
        $jsonBody = json_decode(file_get_contents("php://input"), true);
        $method = isset($jsonBody['_method']) ? strtoupper($jsonBody['_method']) : 'POST';
    }
} else {
    $method = $rawMethod;
}

// Obtener ID desde PATH_INFO o desde GET
$path = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
$id = isset($path[0]) && is_numeric($path[0]) ? intval($path[0]) : (isset($_GET['id']) ? intval($_GET['id']) : null);

function getJsonBody() {
    return json_decode(file_get_contents("php://input"), true);
}

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM proyectos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC");
            $out = [];
            while ($row = $res->fetch_assoc()) {
                $out[] = $row;
            }
            echo json_encode($out);
        }
        break;

    case 'POST':
        $data = isset($jsonBody) ? $jsonBody : getJsonBody();
        if (!isset($data['titulo'], $data['descripcion'], $data['url_github'], $data['url_produccion'], $data['imagen'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos."]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['titulo'], $data['descripcion'], $data['url_github'], $data['url_produccion'], $data['imagen']);
        $stmt->execute();
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
        break;

    case 'PATCH':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID no proporcionado."]);
            break;
        }

        $data = getJsonBody();
        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Datos JSON inválidos."]);
            break;
        }

        $camposPermitidos = ['titulo', 'descripcion', 'url_github', 'url_produccion', 'imagen'];
        $sets = [];
        foreach ($data as $k => $v) {
            if (in_array($k, $camposPermitidos)) {
                $sets[] = "$k = '" . $conn->real_escape_string($v) . "'";
            }
        }

        if (empty($sets)) {
            http_response_code(400);
            echo json_encode(["error" => "No se enviaron campos válidos."]);
            break;
        }

        $sql = "UPDATE proyectos SET " . implode(", ", $sets) . " WHERE id = $id";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar."]);
        }
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID no proporcionado."]);
            break;
        }

        $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
