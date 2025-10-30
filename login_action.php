<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "paddlet_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        die("<p style='color:red;text-align:center;'>Preencha todos os campos.</p>");
    }

    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $nome, $senhaHash);
        $stmt->fetch();

        if (password_verify($senha, $senhaHash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;

            echo "<p style='color:green;text-align:center;'>Login bem-sucedido!</p>";
            header("Location: admin.php");
        } else {
            echo "<p style='color:red;text-align:center;'>Senha incorreta.</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>E-mail não encontrado.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
