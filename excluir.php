<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "paddlet_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

// 1️⃣ Excluir posts vencidos automaticamente
$resultExpired = $conn->query("SELECT id, midia FROM posts WHERE vencimento < NOW()");
if ($resultExpired->num_rows > 0) {
    while ($row = $resultExpired->fetch_assoc()) {
        // Remove arquivo físico se existir
        if (!empty($row['midia']) && file_exists($row['midia'])) {
            unlink($row['midia']);
        }
        // Remove do banco
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $stmt->close();
    }
}

// 2️⃣ Excluir post específico, se id estiver definido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Pegar caminho da mídia para excluir o arquivo
    $stmt = $conn->prepare("SELECT midia FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $post = $res->fetch_assoc();
    $stmt->close();

    if ($post) {
        if (!empty($post['midia']) && file_exists($post['midia'])) {
            unlink($post['midia']); // remove arquivo físico
        }

        // Excluir do banco
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();

// Redirecionar para a tela de admin
header("Location: admin.php?info=1");
exit;
?>
