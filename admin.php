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

// Buscar todos os posts
$result = $conn->query("SELECT * FROM posts ORDER BY criado_em");

$posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Mini Padlet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fb;
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #4f73d0, #1a47b8);
            color: #fff;
            padding: 25px 0;
            text-align: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        }

        header h1 {
            font-weight: 600;
            letter-spacing: 1px;
        }

        main {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
        }

        table {
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: #1a47b8;
            color: #fff;
        }

        th {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 0.9rem;
        }

        td img {
            width: 75px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }

        .action-icons a {
            color: #495057;
            margin-right: 10px;
            font-size: 1.2rem;
            transition: 0.2s;
        }

        .action-icons a:hover {
            color: #1a47b8;
            transform: scale(1.1);
        }

        .btn-add {
            background: #1a47b8;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-add:hover {
            background: #0d35a3;
            transform: translateY(-2px);
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #777;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Painel de Administração</h1>
    </header>

    <main>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Título</th>
                        <th>Vencimento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($posts as $post): ?>
                    <tr>
                        <td>
                            <?php if(!empty($post['midia'])): ?>
                                <img src="<?php echo htmlspecialchars($post['midia']); ?>" alt="Imagem do post">
                            <?php else: ?>
                                <span class="text-muted">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($post['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($post['vencimento']); ?></td>
                        <td class="action-icons">
                            <a href="editar.php?id=<?php echo $post['id']; ?>" title="Editar"><i class="bi bi-pencil-square"></i></a>
                            <a href="excluir.php?id=<?php echo $post['id']; ?>" title="Excluir"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(count($posts) === 0): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nenhum post cadastrado.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-4">
            <a href="cadastrar_postagem.php" class="btn btn-add px-4 py-2"><i class="bi bi-plus-circle me-1"></i> Adicionar Post</a>
        </div>
    </main>

    <footer>
        <p>© <?php echo date('Y'); ?> Mini Padlet - Painel de Administração</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
