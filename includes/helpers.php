<?php
/**
 * Funções puramente visuais (não alteram regra de negócio nem banco).
 */

/** Escapa texto para HTML. */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Formata valor em Real: 1234.5 => "1.234,50" */
function moeda($valor): string
{
    return number_format((float) $valor, 2, ',', '.');
}

/**
 * Paleta neutra usada nos placeholders de produto.
 * O tom é escolhido pelo ID, então cada peça mantém sempre a mesma cor.
 */
function tomProduto(int $id): array
{
    $tons = [
        ['#E7E3DC', '#4A463F'],
        ['#D9D4CB', '#3B3832'],
        ['#CBC5BA', '#2E2B26'],
        ['#1C1C1C', '#D8D4CC'],
        ['#EFECE6', '#5A564E'],
        ['#B9B3A7', '#22201C'],
    ];
    return $tons[$id % count($tons)];
}

/**
 * Imagem do produto.
 * - Se a tabela `produtos` tiver uma coluna `imagem` (caminho ou URL), ela é usada.
 * - Caso contrário, mostra uma silhueta minimalista em tom neutro.
 */
function imagemProduto(array $produto, string $classe = ''): string
{
    $id = (int) $produto['id'];
    [$fundo, $traco] = tomProduto($id);

    if (!empty($produto['imagem'])) {
        return '<img src="' . e($produto['imagem']) . '" alt="' . e($produto['nome']) . '" '
             . 'loading="lazy" class="h-full w-full object-cover ' . e($classe) . '">';
    }

    return '<div class="flex h-full w-full items-center justify-center ' . e($classe) . '" '
         . 'style="background:' . $fundo . ';color:' . $traco . '" role="img" aria-label="' . e($produto['nome']) . '">'
         . '<svg viewBox="0 0 100 100" class="h-1/2 w-1/2 opacity-60" fill="none" stroke="currentColor" '
         . 'stroke-width="1.4" stroke-linejoin="round" aria-hidden="true">'
         . '<path d="M35 12 12 24l8 18 10-5v51h40V37l10 5 8-18-23-12c-3 8-27 8-30 0Z"/>'
         . '</svg></div>';
}
