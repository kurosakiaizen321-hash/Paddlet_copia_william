<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #4f73d0, #1a47b8);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container {
      background: #fff;
      border-radius: 16px;
      padding: 40px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 600;
      color: #1a47b8;
    }

    label {
      font-weight: 500;
      color: #333;
    }

    input {
      border-radius: 10px !important;
      padding: 10px 12px !important;
    }

    .btn-custom {
      background: #1a47b8;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      padding: 10px;
      transition: 0.3s;
    }

    .btn-custom:hover {
      background: #0d35a3;
      transform: translateY(-2px);
    }

    .form-text {
      font-size: 0.9rem;
      text-align: center;
      color: #666;
    }

    .form-text a {
      color: #1a47b8;
      font-weight: 500;
      text-decoration: none;
    }

    .form-text a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Login</h2>
    <form method="POST" action="login_action.php">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-custom w-100 mt-3">Criar Conta</button>

      <p class="form-text mt-3">Não possui uma conta? <a href="criar_conta.php">Cadastre-se</a></p>
    </form>
  </div>

</body>
</html>
