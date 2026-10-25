<?php
/**
 * includes/header.php — barra de navegação.
 * Usa apenas as variáveis de sessão já existentes:
 *   $_SESSION['usuario_nome'] e $_SESSION['carrinho'].
 */
$logado       = isset($_SESSION['usuario_nome']);
$qtdCarrinho  = isset($_SESSION['carrinho']) ? (int) array_sum($_SESSION['carrinho']) : 0;
$paginaAtual  = basename($_SERVER['SCRIPT_NAME']);
?>
<a href="#conteudo" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-ink focus:px-4 focus:py-2 focus:text-paper">
    Pular para o conteúdo
</a>

<header class="sticky top-0 z-40 border-b border-ink/10 bg-paper/85 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:px-8">

        <!-- Logo -->
        <a href="index.php" class="font-display text-lg font-extrabold uppercase tracking-[0.22em]" aria-label="Loja Minimalista — página inicial">
            Loja<span class="text-ash">/</span>Min
        </a>

        <!-- Navegação desktop -->
        <nav class="hidden items-center gap-8 text-sm md:flex" aria-label="Principal">
            <a href="index.php" class="nav-link <?= $paginaAtual === 'index.php' ? 'is-active' : '' ?>">Vitrine</a>

            <?php if ($logado): ?>
                <span class="text-ash">Olá, <strong class="font-semibold text-ink"><?= e($_SESSION['usuario_nome']) ?></strong></span>
                <a href="logout.php" class="nav-link">Sair</a>
            <?php else: ?>
                <a href="login.php" class="nav-link <?= $paginaAtual === 'login.php' ? 'is-active' : '' ?>">Entrar</a>
                <a href="cadastro.php" class="btn btn-dark !py-2 !px-4 text-xs">Criar conta</a>
            <?php endif; ?>

            <a href="carrinho.php" class="relative flex items-center gap-2 nav-link <?= $paginaAtual === 'carrinho.php' ? 'is-active' : '' ?>" aria-label="Carrinho, <?= $qtdCarrinho ?> itens">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                <span>Carrinho</span>
                <?php if ($qtdCarrinho > 0): ?>
                    <span class="grid h-5 min-w-5 place-items-center rounded-full bg-ink px-1.5 text-[11px] font-semibold text-paper"><?= $qtdCarrinho ?></span>
                <?php endif; ?>
            </a>
        </nav>

        <!-- Botão mobile -->
        <div class="flex items-center gap-3 md:hidden">
            <a href="carrinho.php" class="relative p-2" aria-label="Carrinho, <?= $qtdCarrinho ?> itens">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                <?php if ($qtdCarrinho > 0): ?>
                    <span class="absolute -right-0.5 -top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-ink px-1 text-[10px] font-semibold text-paper"><?= $qtdCarrinho ?></span>
                <?php endif; ?>
            </a>
            <button type="button" class="p-2" data-menu-btn aria-expanded="false" aria-controls="menu-mobile" aria-label="Abrir menu">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M4 8h16M4 16h16"/></svg>
            </button>
        </div>
    </div>

    <!-- Menu mobile -->
    <nav id="menu-mobile" data-menu class="hidden border-t border-ink/10 bg-paper md:hidden" aria-label="Menu mobile">
        <div class="mx-auto flex max-w-7xl flex-col px-5 py-4 text-sm">
            <a href="index.php" class="py-3">Vitrine</a>
            <a href="carrinho.php" class="py-3">Carrinho <?= $qtdCarrinho > 0 ? '(' . $qtdCarrinho . ')' : '' ?></a>
            <?php if ($logado): ?>
                <p class="py-3 text-ash">Olá, <strong class="text-ink"><?= e($_SESSION['usuario_nome']) ?></strong></p>
                <a href="logout.php" class="py-3">Sair</a>
            <?php else: ?>
                <a href="login.php" class="py-3">Entrar</a>
                <a href="cadastro.php" class="btn btn-dark mt-2">Criar conta</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
