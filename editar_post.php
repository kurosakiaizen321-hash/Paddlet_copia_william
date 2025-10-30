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
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int)$_GET['id'];

$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$vencimento = $_POST['vencimento'];
$destaque = isset($_POST['destaque']) ? 1 : 0; // checkbox

$midiaPath = null;

// Caso envie arquivo novo
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

// Atualiza o post no banco
if($midiaPath !== null){
    $stmt = $conn->prepare("UPDATE posts SET titulo=?, categoria=?, descricao=?, midia=?, vencimento=?, destaque=? WHERE id=?");
    $stmt->bind_param("sssssii", $titulo, $categoria, $descricao, $midiaPath, $vencimento, $destaque, $id);
} else {
    $stmt = $conn->prepare("UPDATE posts SET titulo=?, categoria=?, descricao=?, vencimento=?, destaque=? WHERE id=?");
    $stmt->bind_param("ssssii", $titulo, $categoria, $descricao, $vencimento, $destaque, $id);
}

if ($stmt->execute()) {
    echo "<p style='color:green;'>Post atualizado com sucesso!</p>";
    echo "<a href='admin.php'>Voltar para Admin</a>";
} else {
    echo "<p style='color:red;'>Erro ao atualizar: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
