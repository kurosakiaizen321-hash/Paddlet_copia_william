<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "paddlet_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        die("<p style='color:red;text-align:center;'>Preencha todos os campos.</p>");
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    // Verifica se o e-mail já está cadastrado
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("<p style='color:red;text-align:center;'>E-mail já cadastrado. <a href='login.php'>Entrar</a></p>");
    }

    // Insere novo usuário
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        echo "<p style='color:green;text-align:center;'>Conta criada com sucesso!</p>";
        header("Location: login.php");
    } else {
        echo "<p style='color:red;text-align:center;'>Erro ao criar conta: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $check->close();
}

$conn->close();
?>
