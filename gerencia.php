<?php
include("conexao.php");

if (!isset($_SESSION["gerencia"]) || $_SESSION["gerencia"] !== true) {
    header("Location: login.php");
    exit;
}

$veiculos = $conn->query("SELECT * FROM veiculos ORDER BY id DESC");
$pecas = $conn->query("SELECT * FROM pecas ORDER BY id DESC");

$receitas = $conn->query("SELECT COALESCE(SUM(valor),0) AS total FROM movimentacoes WHERE tipo='receita'")->fetch_assoc()["total"];
$despesas = $conn->query("SELECT COALESCE(SUM(valor),0) AS total FROM movimentacoes WHERE tipo='despesa'")->fetch_assoc()["total"];
$lucro = $receitas - $despesas;

$totalVeiculos = $conn->query("SELECT COUNT(*) AS total FROM veiculos")->fetch_assoc()["total"];
$totalPecas = $conn->query("SELECT COUNT(*) AS total FROM pecas")->fetch_assoc()["total"];
$xp = ($totalVeiculos * 10) + ($totalPecas * 5);

$valorEstoque = $conn->query("
SELECT COALESCE(SUM(quantidade * custo_unitario),0) AS total
FROM pecas
")->fetch_assoc()["total"];

$pecasBaixas = $conn->query("
SELECT *
FROM pecas
WHERE quantidade < 5
ORDER BY quantidade ASC
");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel da Gerência</title>
  <link rel="stylesheet" href="gerencia.css">
</head>
<body>

<header class="topo">
  <nav class="menu">
    <a href="index.php">Site</a>
    <a href="mecanico.php">Mecânico</a>
    <a href="pecas.php">Cadastrar peças</a>
    <a href="logout.php">Sair</a>
  </nav>
</header>

<main class="container">
  <section class="card destaque">
    <h1>Painel da Gerência 💼</h1>
    <p>Lucro, gastos, peças e controle geral da oficina.</p>
  </section>

 <section class="stats">
  <article class="stat">
    Receitas<br>
    <strong><?= moeda($receitas) ?></strong>
  </article>

  <article class="stat">
    Despesas<br>
    <strong><?= moeda($despesas) ?></strong>
  </article>

  <article class="stat">
    Lucro<br>
    <strong><?= moeda($lucro) ?></strong>
  </article>

  <article class="stat">
    XP da oficina<br>
    <strong><?= (int)$xp ?></strong>
  </article>

  <article class="stat">
    Inventário<br>
    <strong><?= moeda($valorEstoque) ?></strong>
  </article>
</section>

  <section class="card">
    <h2>Veículos cadastrados</h2>
    <div class="lista">
      <?php while ($v = $veiculos->fetch_assoc()): ?>
        <article class="item">
          <strong><?= htmlspecialchars($v["cliente"]) ?></strong> |
          Placa: <?= htmlspecialchars($v["placa"]) ?> |
          Modelo: <?= htmlspecialchars($v["modelo"]) ?> |
          Status: <?= htmlspecialchars($v["status"]) ?>
          <p><?= htmlspecialchars($v["problema"]) ?></p>
        </article>
      <?php endwhile; ?>
    </div>
  </section>

  <section class="card">
    <h2>Peças que chegaram</h2>
    <div class="lista">
      <?php while ($p = $pecas->fetch_assoc()): ?>
        <article class="item">
          <strong><?= htmlspecialchars($p["nome"]) ?></strong> |
          Marca: <?= htmlspecialchars($p["marca"]) ?> |
          Qtd: <?= (int)$p["quantidade"] ?> |
          Custo: <?= moeda($p["custo_unitario"]) ?>
          <p>Categoria: <?= htmlspecialchars($p["categoria"]) ?> | Fornecedor: <?= htmlspecialchars($p["fornecedor"]) ?></p>
        </article>
      <?php endwhile; ?>
    </div>
  </section>
  <section class="card">
    
    <h2>⚠️ Peças com estoque baixo</h2>

    <div class="lista">

        <?php if($pecasBaixas->num_rows > 0): ?>

            <?php while($pb = $pecasBaixas->fetch_assoc()): ?>

                <article class="item">

                    <strong>
                        <?= htmlspecialchars($pb["nome"]) ?>
                    </strong>

                    <p>
                        Marca:
                        <?= htmlspecialchars($pb["marca"]) ?>
                    </p>

                    <p>
                        Categoria:
                        <?= htmlspecialchars($pb["categoria"]) ?>
                    </p>

                    <p>
                        Quantidade restante:
                        <strong style="color:#ff7070">
                            <?= (int)$pb["quantidade"] ?>
                        </strong>
                    </p>

                    <p>
                        Fornecedor:
                        <?= htmlspecialchars($pb["fornecedor"]) ?>
                    </p>

                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <article class="item">
                Nenhuma peça com estoque baixo.
            </article>

        <?php endif; ?>

    </div>
</section>
</main>

</body>
</html>