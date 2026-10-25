<?php
/**
 * includes/head.php
 * Abre <html>, <head> e <body>. Defina $titulo antes de incluir.
 * Deve ser incluído ANTES de qualquer saída HTML.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/helpers.php';

$titulo = $titulo ?? 'Loja Minimalista';
?>
<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F5F3EF">
    <title><?= e($titulo) ?> — Loja Minimalista</title>

    <!-- Tipografia -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind via CDN + tema da loja -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#F5F3EF',
                        bone:  '#EAE7E1',
                        sand:  '#D8D4CC',
                        ash:   '#8A867E',
                        ink:   '#111111',
                        rust:  '#B4472B'
                    },
                    fontFamily: {
                        sans:    ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Archivo', 'Inter', 'sans-serif']
                    }
                }
            }
        };
    </script>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased flex flex-col">
