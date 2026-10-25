<?php
session_start();
require 'conexao.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: index.php");
        exit;
    } else {
        $mensagem = "E-mail ou senha incorretos.";
    }
}

$titulo = 'Entrar';
require 'includes/head.php';
require 'includes/header.php';
?>

<main id="conteudo" class="grid flex-1 lg:grid-cols-2">

    <!-- Painel editorial (só desktop) -->
    <aside class="relative hidden overflow-hidden bg-ink text-paper lg:block">
        <div class="absolute inset-0 flex flex-col justify-between p-14">
            <p class="eyebrow !text-paper/50">Conta</p>
            <div>
                <h2 class="font-display text-6xl font-extrabold uppercase leading-[0.95] tracking-tight xl:text-7xl">
                    Bem-<br>vindo<br><span class="text-paper/40">de volta.</span>
                </h2>
                <p class="mt-6 max-w-xs text-sm leading-relaxed text-paper/60">
                    Entre para acompanhar seu carrinho e voltar às suas peças favoritas.
                </p>
            </div>
        </div>
    </aside>

    <!-- Formulário -->
    <section class="flex items-center justify-center px-5 py-14 sm:px-8">
        <div class="reveal w-full max-w-sm">
            <p class="eyebrow">Acesso</p>
            <h1 class="mt-2 font-display text-3xl font-bold uppercase tracking-tight">Entrar na sua conta</h1>

            <?php if ($mensagem): ?>
                <div class="mt-6 border border-rust/40 bg-rust/5 px-4 py-3 text-sm text-rust" role="alert">
                    <?= e($mensagem) ?>
                </div>
            <?php endif; ?>

            <form method="POST" data-validate class="mt-8 space-y-5" novalidate>
                <div data-field>
                    <label class="label" for="email">E-mail</label>
                    <input class="input" type="email" id="email" name="email" required autocomplete="email"
                           placeholder="voce@email.com" data-rule="email"
                           value="<?= e($_POST['email'] ?? '') ?>">
                    <p class="field-error hidden" data-error></p>
                </div>

                <div data-field>
                    <label class="label" for="senha">Senha</label>
                    <div class="relative">
                        <input class="input pr-20" type="password" id="senha" name="senha" required autocomplete="current-password"
                               placeholder="Sua senha" data-rule="obrigatorio">
                        <button type="button" data-toggle-password class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-semibold uppercase tracking-wider text-ash hover:text-ink" aria-label="Mostrar senha">Mostrar</button>
                    </div>
                    <p class="field-error hidden" data-error></p>
                </div>

                <button type="submit" class="btn btn-dark w-full" data-label="Entrar">Entrar</button>
            </form>

            <p class="mt-8 text-sm text-ink/70">
                Não tem uma conta?
                <a href="cadastro.php" class="font-semibold underline underline-offset-4 hover:text-ash">Cadastre-se</a>
            </p>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>
