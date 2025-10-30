<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "paddlet_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

// 🔹 Buscar posts destacados
$destaques = [];
$resultDest = $conn->query("SELECT * FROM posts WHERE destaque = TRUE AND vencimento >= NOW() ORDER BY criado_em DESC");
if ($resultDest && $resultDest->num_rows > 0) {
    while ($row = $resultDest->fetch_assoc()) {
        $destaques[] = $row;
    }
}

// 🔹 Buscar posts normais (não vencidos)
$result = $conn->query("SELECT * FROM posts WHERE vencimento >= NOW() ORDER BY criado_em DESC");

$postsPorCategoria = [
    "Produto" => [],
    "Saúde" => [],
    "Vagas de Emprego" => [],
    "Notícias" => [],
    "Educação" => [],
    "Eventos" => []
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (isset($postsPorCategoria[$row['categoria']])) {
            $postsPorCategoria[$row['categoria']][] = $row;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Padlet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #eef1f5;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 40px 0 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #1f3b73;
        }

        h2 {
            margin: 40px 0 25px;
            font-weight: 600;
            color: #1f3b73;
            border-left: 5px solid #3b82f6;
            padding-left: 10px;
        }

        .carousel-inner {
            padding: 10px 0;
        }

        .card-post {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-post:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .card-post img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px 25px;
        }

        .card-body h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .card-body p {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .card-body small {
            display: block;
            margin-top: 10px;
            font-size: 0.85rem;
            color: #777;
        }

        /* Destaques com layout especial */
        .destaque .card-post {
            border: 2px solid #fbbf24;
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.25);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(59, 130, 246, 0.8);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            background-size: 60% 60%;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
        }

        .categoria-section {
            background: #f9fafc;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 40px;
        }

        .text-muted {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mini Padlet</h1>

        <!-- 🟨 CARROSSEL DE DESTAQUES -->
        <?php if (count($destaques) > 0): ?>
            <div class="categoria-section destaque mb-5">
                <h2>⭐ Destaques</h2>
                <div id="carousel_destaques" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($destaques as $index => $post): ?>
                            <div class="carousel-item <?php if ($index == 0) echo 'active'; ?>">
                                <div class="card-post">
                                    <?php
                                    if (!empty($post['midia'])) {
                                        if (filter_var($post['midia'], FILTER_VALIDATE_URL) || file_exists($post['midia'])) {
                                            echo "<img src='{$post['midia']}' alt='Mídia do post'>";
                                        }
                                    }
                                    ?>
                                    <div class="card-body">
                                        <h5><?php echo htmlspecialchars($post['titulo']); ?></h5>
                                        <p><?php echo nl2br(htmlspecialchars($post['descricao'])); ?></p>
                                        <small>Vence em: <?php echo date("d/m/Y H:i", strtotime($post['vencimento'])); ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($destaques) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel_destaques" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel_destaques" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 🧩 OUTRAS CATEGORIAS -->
        <?php foreach ($postsPorCategoria as $categoria => $posts): ?>
            <div class="categoria-section">
                <h2><?php echo htmlspecialchars($categoria); ?></h2>

                <?php if (count($posts) > 0): ?>
                    <div id="carousel_<?php echo strtolower(str_replace(' ', '_', $categoria)); ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($posts as $index => $post): ?>
                                <div class="carousel-item <?php if ($index == 0) echo 'active'; ?>">
                                    <div class="card-post">
                                        <?php
                                        if (!empty($post['midia'])) {
                                            if (filter_var($post['midia'], FILTER_VALIDATE_URL) || file_exists($post['midia'])) {
                                                echo "<img src='{$post['midia']}' alt='Mídia do post'>";
                                            }
                                        }
                                        ?>
                                        <div class="card-body">
                                            <h5><?php echo htmlspecialchars($post['titulo']); ?></h5>
                                            <p><?php echo nl2br(htmlspecialchars($post['descricao'])); ?></p>
                                            <small>Vencimento: <?php echo date("d/m/Y H:i", strtotime($post['vencimento'])); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($posts) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel_<?php echo strtolower(str_replace(' ', '_', $categoria)); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel_<?php echo strtolower(str_replace(' ', '_', $categoria)); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">Próximo</span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">Nenhum post nesta categoria.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
