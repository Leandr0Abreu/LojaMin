<?php
/** includes/footer.php — rodapé, container de toasts e script principal. */
?>
<footer class="mt-auto border-t border-ink/10">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-8 text-xs text-ash sm:flex-row sm:items-center sm:justify-between sm:px-8">
        <p class="font-display font-bold uppercase tracking-[0.22em] text-ink">Loja<span class="text-ash">/</span>Min</p>
        <p>© <?= date('Y') ?> Loja Minimalista. Todos os direitos reservados.</p>
    </div>
</footer>

<div data-toasts class="pointer-events-none fixed inset-x-0 bottom-4 z-50 flex flex-col items-center gap-2 px-4" aria-live="polite"></div>

<script src="assets/js/app.js" defer></script>
</body>
</html>
