<?php 
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die("Usuário não autenticado. Acesse a página de login.");
}

$conn = new mysqli('localhost', 'root', '', 'meu_banco');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nomes_usuario = $_SESSION['usuario_nome'] ?? '';

    $texto = $_POST['texto'];
    $foto = null;
    $video = null;

    $uploadDir = __DIR__ . '/uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!empty($_FILES['foto']['name'])) {
        $foto = basename($_FILES['foto']['name']);
        $fotoPath = $uploadDir . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath)) {
            echo "Erro ao enviar a foto.";
            exit;
        }
    }

    if (!empty($_FILES['video']['name'])) {
        $video = basename($_FILES['video']['name']);
        $videoPath = $uploadDir . $video;

        if (!move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
            echo "Erro ao enviar o vídeo.";
            exit;
        }
    }

    $sql = "INSERT INTO posts (texto, foto, video, usuario_id, nomes_usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    // Usar null se a foto ou vídeo não forem enviados
    $fotoParam = $foto ? $foto : null;
    $videoParam = $video ? $video : null;

    // Bind parameters
    $stmt->bind_param('sssis', $texto, $fotoParam, $videoParam, $usuario_id, $nomes_usuario);

    if ($stmt->execute()) {
        header("Location: ../PHP/fy.php");
        exit;
    } else {
        echo "Erro ao criar postagem: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
