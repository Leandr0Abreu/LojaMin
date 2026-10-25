<?php
session_start();
require 'conexao.php';

$stmt = $pdo->query("SELECT * FROM produtos WHERE estoque > 0");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titulo = 'Vitrine';
require 'includes/head.php';
require 'includes/header.php';
?>

<main id="conteudo" class="flex-1">

    <!-- Hero -->
    <section class="border-b border-ink/10">
        <div class="mx-auto max-w-7xl px-5 pb-12 pt-14 sm:px-8 sm:pb-16 sm:pt-20">
            <p class="eyebrow reveal">Coleção essencial</p>
            <h1 class="reveal reveal-2 mt-4 font-display text-5xl font-extrabold uppercase leading-[0.95] tracking-tight sm:text-7xl lg:text-8xl">
                Menos ruído.<br><span class="text-ash">Mais peça.</span>
            </h1>
            <p class="reveal reveal-3 mt-6 max-w-md text-[15px] leading-relaxed text-ink/70">
                Básicos de corte limpo e tons neutros, pensados para vestir todos os dias.
            </p>
            <a href="#vitrine" class="btn btn-dark reveal reveal-3 mt-8">Ver peças</a>
        </div>
    </section>

    <!-- Vitrine -->
    <section id="vitrine" class="mx-auto max-w-7xl scroll-mt-16 px-5 py-12 sm:px-8 sm:py-16">

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-bold uppercase tracking-tight sm:text-3xl">Peças disponíveis</h2>
                <p class="mt-1 text-sm text-ash"><span data-count><?= count($produtos) ?></span> <span data-count-label><?= count($produtos) === 1 ? 'peça' : 'peças' ?></span></p>
            </div>

            <?php if (!empty($produtos)): ?>
            <div class="flex flex-col gap-3 sm:flex-row">
                <label class="sr-only" for="busca">Buscar peça</label>
                <input id="busca" type="search" data-search placeholder="Buscar peça…" class="input sm:w-56" autocomplete="off">

                <label class="sr-only" for="ordem">Ordenar por</label>
                <select id="ordem" data-sort class="input sm:w-48">
                    <option value="padrao">Ordenar</option>
                    <option value="preco-asc">Menor preço</option>
                    <option value="preco-desc">Maior preço</option>
                    <option value="nome">Nome (A–Z)</option>
                </select>
            </div>
            <?php endif; ?>
        </div>

        <?php if (empty($produtos)): ?>
            <!-- Sem produtos em estoque -->
            <div class="border border-dashed border-sand py-24 text-center">
                <p class="font-display text-xl font-bold uppercase">Coleção em reposição</p>
                <p class="mt-2 text-sm text-ash">Nenhuma peça disponível no momento. Volte em breve.</p>
            </div>
        <?php else: ?>

        <div data-grid class="grid grid-cols-2 gap-x-4 gap-y-10 lg:grid-cols-3 lg:gap-x-6 xl:grid-cols-4">
            <?php foreach ($produtos as $produto):
                $id      = (int) $produto['id'];
                $estoque = (int) $produto['estoque'];
                $preco   = (float) $produto['preco'];

                if ($estoque <= 3) {
                    $nivel  = 'ultimas';
                    $rotulo = $estoque === 1 ? 'Última unidade' : "Últimas {$estoque} unidades";
                } elseif ($estoque <= 10) {
                    $nivel  = 'poucas';
                    $rotulo = 'Poucas unidades';
                } else {
                    $nivel  = 'ok';
                    $rotulo = '';
                }
            ?>
            <article class="product-card flex flex-col" data-card data-name="<?= e($produto['nome']) ?>" data-price="<?= $preco ?>">

                <div class="card-img bg-bone">
                    <?= imagemProduto($produto) ?>
                    <?php if ($nivel !== 'ok'): ?>
                        <span class="badge badge-<?= $nivel ?>"><?= e($rotulo) ?></span>
                    <?php endif; ?>
                </div>

                <div class="mt-4 flex flex-1 flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-[15px] font-semibold leading-snug"><?= e($produto['nome']) ?></h3>
                        <p class="whitespace-nowrap text-[15px] font-semibold">R$&nbsp;<?= moeda($preco) ?></p>
                    </div>

                    <p class="line-clamp-2 mt-1.5 text-[13px] leading-relaxed text-ink/60"><?= e($produto['descricao']) ?></p>

                    <p class="mt-3 flex items-center gap-2 text-xs text-ash">
                        <span class="dot dot-<?= $nivel ?>" aria-hidden="true"></span>
                        <?= $estoque === 1 ? '1 disponível' : $estoque . ' disponíveis' ?>
                    </p>

                    <!-- Formulário para mandar o produto para o carrinho -->
                    <form action="adicionar_carrinho.php" method="POST" data-add-form class="mt-4 flex flex-col gap-2 xl:flex-row">
                        <input type="hidden" name="produto_id" value="<?= $id ?>">

                        <div class="qty" data-qty>
                            <button type="button" data-qty-minus aria-label="Diminuir quantidade">−</button>
                            <input type="number" name="quantidade" value="1" min="1" max="<?= $estoque ?>" inputmode="numeric" aria-label="Quantidade de <?= e($produto['nome']) ?>">
                            <button type="button" data-qty-plus aria-label="Aumentar quantidade">+</button>
                        </div>

                        <button type="submit" class="btn btn-dark flex-1 !px-3" data-label="Adicionar">Adicionar</button>
                    </form>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Busca sem resultado -->
        <div data-empty-search class="hidden border border-dashed border-sand py-20 text-center">
            <p class="font-display text-lg font-bold uppercase">Nada encontrado</p>
            <p class="mt-2 text-sm text-ash">Tente outro termo de busca.</p>
        </div>

        <?php endif; ?>
    </section>
</main>

<?php require 'includes/footer.php'; ?>
