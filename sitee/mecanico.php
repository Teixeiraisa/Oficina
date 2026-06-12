<?php
include("conexao.php");

/* salva o veículo quando o formulário é enviado */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cliente = trim($_POST["cliente"]);
    $placa = trim($_POST["placa"]);
    $modelo = trim($_POST["modelo"]);
    $problema = trim($_POST["problema"]);
    $status = trim($_POST["status"]);
    $prioridade = trim($_POST["prioridade"]);

    $stmt = $conn->prepare("INSERT INTO veiculos (cliente, placa, modelo, problema, status, prioridade) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cliente, $placa, $modelo, $problema, $status, $prioridade);
    $stmt->execute();
    $stmt->close();

    header("Location: mecanico.php");
    exit;
}

$veiculos = $conn->query("SELECT * FROM veiculos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu do Mecânico</title>
  <link rel="stylesheet" href="mecanico.css">
</head>
<body>

<header class="topo">
  <nav class="menu">
    <a href="index.php">Voltar ao site</a>
    <a href="login.php">Gerência</a>
  </nav>
</header>

<main class="container">
  <section class="card destaque">
    <h1>Menu do mecânico 🔧</h1>
    <p>Cadastro dos carros que entram na oficina.</p>
  </section>

  <section class="card">
    <h2>Cadastrar veículo</h2>

    <form method="POST" class="form">
      <input type="text" name="cliente" placeholder="Nome do cliente" required>
      <input type="text" name="placa" placeholder="Placa" required>
      <input type="text" name="modelo" placeholder="Modelo" required>
      <textarea name="problema" placeholder="Problema do veículo" required></textarea>

      <select name="status">
        <option value="Aguardando">Aguardando</option>
        <option value="Em manutenção">Em manutenção</option>
        <option value="Finalizado">Finalizado</option>
      </select>

      <select name="prioridade">
        <option value="Baixa">Baixa</option>
        <option value="Normal" selected>Normal</option>
        <option value="Alta">Alta</option>
      </select>

      <button type="submit">Cadastrar</button>
    </form>
  </section>

  <section class="card">
    <h2>Veículos cadastrados</h2>

    <div class="lista">
      <?php while ($v = $veiculos->fetch_assoc()): ?>
        <article class="item">
          <div class="linha">
            <strong><?= htmlspecialchars($v["cliente"]) ?></strong>
            <span class="badge"><?= htmlspecialchars($v["status"]) ?></span>
          </div>
          <p>Placa: <?= htmlspecialchars($v["placa"]) ?></p>
          <p>Modelo: <?= htmlspecialchars($v["modelo"]) ?></p>
          <p>Problema: <?= htmlspecialchars($v["problema"]) ?></p>
          <p>Prioridade: <?= htmlspecialchars($v["prioridade"]) ?></p>
        </article>
      <?php endwhile; ?>
    </div>
  </section>
</main>

</body>
</html>