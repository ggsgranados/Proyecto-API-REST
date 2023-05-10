<?php
// Establecer variables de conexión a la base de datos
$host = "localhost";
$dbname = "escuela";
$username = "root";
$password = "";

// Establecer credenciales para la autenticación básica
$auth_username = "admin";
$auth_password = "admin123";

// Obtener las credenciales de autenticación del encabezado HTTP
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
    || $_SERVER['PHP_AUTH_USER'] != $auth_username || $_SERVER['PHP_AUTH_PW'] != $auth_password) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Acceso restringido"');
    exit;
}

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Establecer el encabezado de respuesta a JSON
header('Content-Type: application/json');

// Comprobar el método HTTP utilizado
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        // Obtener un profesor específico o todos los profesores
        if (isset($_GET['id'])) {
            // Obtener un profesor específico
            $stmt = $pdo->prepare("SELECT * FROM profesores WHERE id = ? ");
            $stmt->execute([$_GET['id']]);
            $profesor = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($profesor);
        } else {
            // Obtener todos los profesores
            $stmt = $pdo->query("SELECT * FROM profesores order by id desc");
            $profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($profesores);
        }
        break;
    case 'POST':
        // Crear un nuevo profesor
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO profesores (nombre, apellido, edad) VALUES (:nombre,:apellido,:edad)");
        $stmt-> bindValue(":nombre",$data['nombre']);
        $stmt-> bindValue(":apellido",$data['apellido']);
        $stmt-> bindValue(":edad",$data['edad']);
        $stmt->execute();
        $profesor_id = $pdo->lastInsertId();
        $profesor = [
            'id' => $profesor_id,
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'edad' => $data['edad']
        ];
        echo json_encode($profesor);
        break;
    case 'PUT':
         $data = json_decode(file_get_contents('php://input'), true);
         if ($data['edad']==-987) {
            $stmt = $pdo->prepare("DELETE FROM profesores WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['mensaje' => 'El profesor ha sido eliminado correctamente.']);          
         } else {
           // Actualizar un profesor existente              
           $stmt = $pdo->prepare("UPDATE profesores SET nombre = ?, apellido = ?, edad = ? WHERE id = ?");
           $stmt->execute([$data['nombre'], $data['apellido'], $data['edad'], $data['id']]);
           $profesor = [
            'id' => $data['id'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'edad' => $data['edad']
            ];
           echo json_encode($profesor);    
        }     
        break;
    case 'DELETE':
       // Eliminar un profesor existente
       if (isset($_GET['id'])) {
          $stmt = $pdo->prepare("DELETE FROM profesores WHERE id = ?");
          $stmt->execute([$_GET['id']]);
          echo json_encode(['mensaje' => 'El profesor ha sido eliminado correctamente.']);           
       }else {
          // Error: no se ha proporcionado un ID de profesor para actualizar
          header('HTTP/1.1 400 Bad Request');
          echo json_encode(['error' => 'No se proporcionado un ID de profesor para actualizar']);
        }
        break;
    default:
        // Método HTTP no válido
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(['error' => 'Método HTTP no válido']);
        break;
  }

//Cerrar la conexión con la base de datos
$pdo = null;
?>