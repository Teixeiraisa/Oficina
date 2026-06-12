<?php
include("conexao.php");

if (!isset($_SESSION["gerencia"]) || $_SESSION["gerencia"] !== true) {
    header("Location: login.php");
    exit;
}

$mensagem = "";

/* CADASTRAR NOVA PEÇA */
if (isset($_POST["cadastrar"])) {
    $nome = trim($_POST["nome"]);
    $marca = trim($_POST["marca"]);
    $categoria = trim($_POST["categoria"]);
    $quantidade = (int)$_POST["quantidade"];
    $custo_unitario = (float)$_POST["custo_unitario"];
    $fornecedor = trim($_POST["fornecedor"]);

    $stmt = $conn->prepare("
        INSERT INTO pecas (nome, marca, categoria, quantidade, custo_unitario, fornecedor)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssids", $nome, $marca, $categoria, $quantidade, $custo_unitario, $fornecedor);

    if ($stmt->execute()) {
        $nova_peca_id = $stmt->insert_id;

        $tipo = "entrada";
        $mov = $conn->prepare("
            INSERT INTO entradaesaida (peca_id, tipo, quantidade)
            VALUES (?, ?, ?)
        ");
        $mov->bind_param("isi", $nova_peca_id, $tipo, $quantidade);
        $mov->execute();
        $mov->close();

        $mensagem = "Peça cadastrada com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar peça.";
    }

    $stmt->close();
}

/* ENTRADA DE ESTOQUE */
if (isset($_POST["entrada"])) {
    $peca_id = (int)$_POST["peca_id"];
    $quantidade = (int)$_POST["quantidade"];

    if ($quantidade > 0) {
        $stmt = $conn->prepare("
            UPDATE pecas
            SET quantidade = quantidade + ?
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $quantidade, $peca_id);
        $stmt->execute();
        $stmt->close();

        $tipo = "entrada";
        $mov = $conn->prepare("
            INSERT INTO entradaesaida (peca_id, tipo, quantidade)
            VALUES (?, ?, ?)
        ");
        $mov->bind_param("isi", $peca_id, $tipo, $quantidade);
        $mov->execute();
        $mov->close();

        $mensagem = "Entrada registrada!";
    }
}

/* SAÍDA DE ESTOQUE */
if (isset($_POST["saida"])) {
    $peca_id = (int)$_POST["peca_id"];
    $quantidade = (int)$_POST["quantidade"];

    if ($quantidade > 0) {
        $consulta = $conn->prepare("
            SELECT quantidade
            FROM pecas
            WHERE id = ?
        ");
        $consulta->bind_param("i", $peca_id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $peca = $resultado->fetch_assoc();
        $consulta->close();

        if ($peca && $peca["quantidade"] >= $quantidade) {
            $stmt = $conn->prepare("
                UPDATE pecas
                SET quantidade = quantidade - ?
                WHERE id = ?
            ");
            $stmt->bind_param("ii", $quantidade, $peca_id);
            $stmt->execute();
            $stmt->close();

            $tipo = "saida";
            $mov = $conn->prepare("
                INSERT INTO entradaesaida (peca_id, tipo, quantidade)
                VALUES (?, ?, ?)
            ");
            $mov->bind_param("isi", $peca_id, $tipo, $quantidade);
            $mov->execute();
            $mov->close();

            $mensagem = "Saída registrada!";
        } else {
            $mensagem = "Quantidade insuficiente no estoque.";
        }
    }
}

$pecas = $conn->query("
    SELECT *
    FROM pecas
    ORDER BY nome ASC
");

$historico = $conn->query("
    SELECT e.*, p.nome
    FROM entradaesaida e
    LEFT JOIN pecas p ON p.id = e.peca_id
    ORDER BY e.id DESC
    LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventário de Peças</title>
    <link rel="stylesheet" href="pecas.css">
</head>
<body>

<header class="topo">
    <nav class="menu">
        <a href="gerencia.php">Painel</a>
        <a href="mecanico.php">Mecânico</a>
        <a href="index.php">Site</a>
        <a href="logout.php">Sair</a>
    </nav>
</header>

<main class="container">

    <section class="card destaque">
        <h1>Inventário DOAL Car 📦</h1>
        <p>Controle de peças, entradas, saídas e histórico da oficina.</p>
        <?php if ($mensagem): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Cadastrar nova peça</h2>

        <form method="POST" class="form-grid">
            <input type="text" name="nome" placeholder="Nome da peça" required>
            <input type="text" name="marca" placeholder="Marca" required>
            <input type="text" name="categoria" placeholder="Categoria" required>
            <input type="number" name="quantidade" placeholder="Quantidade inicial" min="0" required>
            <input type="number" step="0.01" name="custo_unitario" placeholder="Custo unitário" min="0" required>
            <input type="text" name="fornecedor" placeholder="Fornecedor" required>
            <button type="submit" name="cadastrar">Cadastrar peça</button>
        </form>
    </section>

    <section class="card">
        <h2>Estoque atual</h2>

        <div class="lista">
            <?php while ($p = $pecas->fetch_assoc()): ?>
                <article class="item">
                    <div class="item-topo">
                        <strong><?= htmlspecialchars($p["nome"]) ?></strong>
                        <span class="badge <?= ((int)$p["quantidade"] < 5) ? 'baixo' : 'ok' ?>">
                            <?= (int)$p["quantidade"] ?> em estoque
                        </span>
                    </div>

                    <p>Marca: <?= htmlspecialchars($p["marca"]) ?></p>
                    <p>Categoria: <?= htmlspecialchars($p["categoria"]) ?></p>
                    <p>Custo: <?= moeda($p["custo_unitario"]) ?></p>
                    <p>Fornecedor: <?= htmlspecialchars($p["fornecedor"]) ?></p>

                    <form method="POST" class="acoes">
                        <input type="hidden" name="peca_id" value="<?= (int)$p["id"] ?>">

                        <input
                            type="number"
                            name="quantidade"
                            placeholder="Qtd"
                            min="1"
                            required
                        >

                        <button type="submit" name="entrada" class="btn-entrada">
                            + Entrada
                        </button>

                        <button type="submit" name="saida" class="btn-saida">
                            - Saída
                        </button>
                    </form>
                </article>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="card">
        <h2>Últimas movimentações</h2>

        <div class="lista">
            <?php while ($h = $historico->fetch_assoc()): ?>
                <article class="item">
                    <div class="item-topo">
                        <strong><?= htmlspecialchars($h["nome"] ?? "Peça removida") ?></strong>
                        <span class="badge <?= $h["tipo"] === "entrada" ? "ok" : "saida" ?>">
                            <?= strtoupper($h["tipo"]) ?>
                        </span>
                    </div>

                    <p>Quantidade: <?= (int)$h["quantidade"] ?></p>
                    <p>Data: <?= htmlspecialchars($h["data_movimentacao"]) ?></p>
                </article>
            <?php endwhile; ?>
        </div>
    </section>

</main>

</body>
</html>