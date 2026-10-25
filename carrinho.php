<?php
session_start();
require 'conexao.php';

$carrinho = $_SESSION['carrinho'] ?? [];
$produtos_carrinho = [];
$total = 0;

if (!empty($carrinho)) {
    $ids = implode(',', array_keys($carrinho));
    
    $stmt = $pdo->query("SELECT * FROM produtos WHERE id IN ($ids)");
    $produtos_carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$linhas = [];
$qtdItens = 0;
foreach ($produtos_carrinho as $produto) {
    $id = $produto['id'];
    $qtd = $carrinho[$id];
    $subtotal = $produto['preco'] * $qtd;
    $total += $subtotal;
    $qtdItens += $qtd;
    $linhas[] = ['produto' => $produto, 'qtd' => $qtd, 'subtotal' => $subtotal];
}

$titulo = 'Carrinho';
require 'includes/head.php';
require 'includes/header.php';
?>

<main id="conteudo" class="mx-auto w-full max-w-7xl flex-1 px-5 py-10 sm:px-8 sm:py-14">

    <a href="index.php" class="inline-flex items-center gap-2 text-sm text-ink/70 transition hover:text-ink">
        <span aria-hidden="true">←</span> Continuar comprando
    </a>

    <h1 class="reveal mt-6 font-display text-4xl font-extrabold uppercase tracking-tight sm:text-5xl">Carrinho</h1>

    <?php if (empty($produtos_carrinho)): ?>

        <!-- Carrinho vazio -->
        <div class="mt-10 border border-dashed border-sand px-6 py-24 text-center">
            <svg class="mx-auto h-10 w-10 text-ash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
            <p class="mt-5 font-display text-xl font-bold uppercase">Seu carrinho está vazio</p>
            <p class="mt-2 text-sm text-ash">Que tal começar pelas peças da vitrine?</p>
            <a href="index.php" class="btn btn-dark mt-8">Ver peças</a>
        </div>

    <?php else: ?>

        <p class="mt-2 text-sm text-ash"><?= $qtdItens ?> <?= $qtdItens == 1 ? 'item' : 'itens' ?></p>

        <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_22rem] lg:gap-14">

            <!-- Lista de itens -->
            <ul class="divide-y divide-ink/10 border-y border-ink/10">
                <?php foreach ($linhas as $linha):
                    $produto  = $linha['produto'];
                    $qtd      = (int) $linha['qtd'];
                    $subtotal = $linha['subtotal'];
                    $excede   = $qtd > (int) $produto['estoque'];
                ?>
                <li class="flex gap-4 py-6 sm:gap-6">
                    <div class="h-28 w-24 flex-none overflow-hidden bg-bone sm:h-32 sm:w-28">
                        <?= imagemProduto($produto) ?>
                    </div>

                    <div class="flex min-w-0 flex-1 flex-col justify-between">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <h2 class="truncate text-[15px] font-semibold"><?= e($produto['nome']) ?></h2>
                                <p class="mt-1 text-sm text-ash">R$ <?= moeda($produto['preco']) ?> <span class="text-ink/40">· unidade</span></p>
                            </div>
                            <p class="whitespace-nowrap text-[15px] font-semibold">R$ <?= moeda($subtotal) ?></p>
                        </div>

                        <div>
                            <p class="text-sm text-ink/70">Quantidade: <strong class="font-semibold text-ink"><?= $qtd ?></strong></p>
                            <?php if ($excede): ?>
                                <p class="mt-1.5 text-xs text-rust">
                                    Atenção: restam apenas <?= (int) $produto['estoque'] ?> em estoque.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Resumo -->
            <aside class="h-fit border border-ink/10 bg-white p-6 lg:sticky lg:top-24">
                <h2 class="font-display text-lg font-bold uppercase tracking-tight">Resumo</h2>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-ink/70">Itens (<?= $qtdItens ?>)</dt>
                        <dd>R$ <?= moeda($total) ?></dd>
                    </div>
                </dl>

                <div class="mt-5 flex items-baseline justify-between border-t border-ink/10 pt-5">
                    <span class="text-sm font-semibold uppercase tracking-wider">Total da compra</span>
                    <span class="font-display text-2xl font-extrabold">R$ <?= moeda($total) ?></span>
                </div>

                <button type="button" class="btn btn-dark mt-6 w-full" data-checkout-open>Finalizar compra</button>
                <a href="index.php" class="btn btn-line mt-3 w-full">Continuar comprando</a>
            </aside>
        </div>

        <!-- Modal de finalização (simulação, como no original) -->
        <dialog class="modal" data-modal aria-labelledby="modal-titulo">

            <div data-modal-step="confirmar" class="p-7">
                <p class="eyebrow">Revisão</p>
                <h2 id="modal-titulo" class="mt-2 font-display text-2xl font-bold uppercase tracking-tight">Confirmar compra?</h2>
                <p class="mt-4 text-sm leading-relaxed text-ink/70">
                    Você está finalizando <?= $qtdItens ?> <?= $qtdItens == 1 ? 'item' : 'itens' ?>
                    no valor total de <strong class="text-ink">R$ <?= moeda($total) ?></strong>.
                </p>
                <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row">
                    <button type="button" class="btn btn-line flex-1" data-modal-close>Voltar</button>
                    <button type="button" class="btn btn-dark flex-1" data-checkout-confirm>Confirmar</button>
                </div>
            </div>

            <div data-modal-step="sucesso" class="hidden p-7 text-center">
                <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-ink text-paper">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg>
                </div>
                <h2 class="mt-5 font-display text-2xl font-bold uppercase tracking-tight">Compra finalizada!</h2>
                <p class="mt-3 text-sm text-ink/70">Compra finalizada com sucesso! <span class="text-ash">(Simulação)</span></p>
                <a href="index.php" class="btn btn-dark mt-7 w-full">Voltar à vitrine</a>
            </div>
        </dialog>

    <?php endif; ?>
</main>

<?php require 'includes/footer.php'; ?>
