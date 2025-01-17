<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'meu_banco');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Buscar as postagens
$sql = "SELECT * FROM posts ORDER BY data_postagem DESC"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Postagens</h1>
    <a href="../form.html">Criar Nova Postagem</a>

    <div class="postagens">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='postagem'>";
                // Exibir foto de perfil do usuário
                if (!empty($row['pic_perfil'])) {
                    echo "<img src='uploads/" . htmlspecialchars($row['fotos_perfil']) . "' alt='Foto de Perfil' style='max-width: 50px; border-radius: 50%;'>"; // Adicione o estilo para ser circular
                }
                echo "<p><strong>" . htmlspecialchars($row['nomes_usuario']) . "</strong></p>"; // Nome do usuário
                echo "<p>" . htmlspecialchars($row['texto']) . "</p>";

                // Exibir foto, se houver
                if (!empty($row['foto'])) {
                    $fotoPath = 'uploads/' . htmlspecialchars($row['foto']);
                    echo "<img src='$fotoPath' alt='Foto' style='max-width: 300px;'>";
                }

                // Exibir vídeo, se houver
                if (!empty($row['video'])) {
                    $videoPath = 'uploads/' . htmlspecialchars($row['video']);
                    echo "<video width='300' controls>
                            <source src='$videoPath' type='video/mp4'>
                            Seu navegador não suporta a tag de vídeo.
                          </video>";
                }

                echo "</div>";
            }
        } else {
            echo "Nenhuma postagem encontrada.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
