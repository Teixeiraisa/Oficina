
  <!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DOAL Car</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="topo">
    <nav class="menu">
      <a href="#informacoes">Informações</a>
      <a href="#promocoes">Promoções</a>
      <a href="#marcas">Marcas</a>
      <a href="mecanico.php">Menu do mecânico</a>
      <a href="login.php">Gerência</a>
    </nav>
  </header>

  <main class="hero">
    <div class="hero-texto">
      <h1>DOAL Car</h1>
      <p class="subtitles">Agende já o seu horário!</p>
      <div class="botoes">
        <a class="botao" href="#promocoes">Ver promoções</a>
        <a class="botao secundario" href="#contato">Contato</a>
      </div>
    </div>

    <img src="imagens/loja.png" alt="Logo da DOAL Car" class="logo" />
  </main>

  <section id="informacoes" class="secao">
    <h2>Informações</h2>
    <p>
      Tudo para manutenção de seu veículo ! 
    </p>
    <p>
    trabalhamos com veículos nacionais e importados, venha já fazer a manutenção geral de seu veículo!
    </p>
  </section>

  <section id="promocoes" class="secao">
    <h2>Promoções do mês</h2>

    <div class="cards">
      <article class="card promo">
        <img src="imagens/copa.png" alt="Promoção 1">
        <h3>Especial do mês</h3>
        <p>Venha fazer sua revisão completa antes dos jogos do brasil!.</p>
      </article>

      <article class="card promo">
        <img src="imagens/ali.png" alt="Promoção 2">
        <h3>Alinhamento e Balanceamento</h3>
        <p>Pacote com preço promocional.</p>
      </article>

      <article class="card promo">
        <img src="imagens/fer.png" alt="Promoção 3">
        <h3>Revisão completa</h3>
        <p>Mais segurança para rodar tranquilo.</p>
      </article>
    </div>
  </section>

  <section id="marcas" class="secao">
    <h2>Marcas parceiras</h2>

    <div class="faixa-marcas">
      <div class="marcas-track">
        <img src="imagens/bosch.png" class="marca">
        <img src="imagens/kyb.png"  class="marca">
        <img src="imagens/cofap.webp" class="marca">
        <img src="imagens/monroe.png"  class="marca">
        <img src="imagens/moura.png"  class="marca">
        <img src="imagens/zetta.jpeg"  class="marca">
        <img src="imagens/pirelli.png"  class="marca">
        <img src="imagens/ngk.png"  class="marca">
        <img src="imagens/nakata.jpg"  class="marca">
      </div>
    </div>
  </section>

  <section id="contato" class="secao">
    <h2>Contato</h2>
      <a href="https://wa.me/551124854330"><button class="botao" type="button">Whatsapp</button></a>
      <a href="https://www.instagram.com/doalcar/"><button class="botao" type="button">Instagram</button></a>
  </section>
</body>
</html>