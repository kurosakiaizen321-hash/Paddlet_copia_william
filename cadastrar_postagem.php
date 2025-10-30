<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Postagem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-form {
            background: #fff;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }

        label {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 12px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            color: white;
            font-weight: 600;
            padding: 12px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .file-input {
            background-color: #f9fafb;
            border: 1px dashed #cbd5e1;
            padding: 10px;
            border-radius: 10px;
        }

        .file-input:hover {
            background-color: #f1f5f9;
        }

        .divider {
            height: 2px;
            background: #e2e8f0;
            margin: 25px 0;
            border-radius: 2px;
        }
    </style>
</head>

<body>
    <div class="card-form">
        <h2>Nova Postagem</h2>
        <form action="salvar_post.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select class="form-select" id="categoria" name="categoria" required>
                    <option value="" selected disabled>Selecione...</option>
                    <option value="Produto">Produto</option>
                    <option value="Saúde">Saúde</option>
                    <option value="Vagas de Emprego">Vagas de Emprego</option>
                    <option value="Notícias">Notícias</option>
                    <option value="Educação">Educação</option>
                    <option value="Eventos">Eventos</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="vencimento" class="form-label">Data de Vencimento</label>
                <input type="datetime-local" class="form-control" id="vencimento" name="vencimento" required>
            </div>

            <div class="divider"></div>

            <div class="mb-3">
                <label for="midia_link" class="form-label">Link da Mídia (opcional)</label>
                <input type="url" class="form-control" id="midia_link" name="midia_link" placeholder="https://exemplo.com/imagem.jpg">
            </div>

            <div class="mb-3">
                <label for="midia_arquivo" class="form-label">Upload de Mídia</label>
                <input type="file" class="form-control file-input" id="midia_arquivo" name="midia_arquivo">
            </div>

            <button type="submit" class="btn-submit">Publicar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
