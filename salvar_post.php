<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "paddlet_db";

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Coleta dos dados do formulário
$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$vencimento = $_POST['vencimento'];

$midiaPath = null;

// Caso seja arquivo
if (isset($_FILES['midia_arquivo']) && $_FILES['midia_arquivo']['error'] == 0) {
    $ext = pathinfo($_FILES['midia_arquivo']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = uniqid() . "." . $ext;
    $destino = "uploads/" . $nomeArquivo;

    if (move_uploaded_file($_FILES['midia_arquivo']['tmp_name'], $destino)) {
        $midiaPath = $destino;
    } else {
        die("Erro ao fazer upload do arquivo.");
    }
}
// Caso seja link
else if (!empty($_POST['midia_link'])) {
    $midiaPath = $_POST['midia_link'];
}

// Prepara a query para inserir os dados
$stmt = $conn->prepare("INSERT INTO posts (titulo, categoria, descricao, midia, vencimento) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $titulo, $categoria, $descricao, $midiaPath, $vencimento);

if ($stmt->execute()) {
    echo "<p style='color:green;'>Post salvo com sucesso!</p>";
    echo "<a href='index.php'>Voltar</a>";
} else {
    echo "<p style='color:red;'>Erro ao salvar: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();

/*
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `midia` varchar(500) DEFAULT NULL,
  `vencimento` datetime NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


*/
?>
