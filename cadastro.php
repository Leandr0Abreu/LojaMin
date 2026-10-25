<?php
require 'conexao.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senhaPura = $_POST['senha'];

    $senhaSegura = password_hash($senhaPura, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaSegura]);
        
        $mensagem = "Cadastro realizado com sucesso! Você já pode fazer login.";
    } catch (PDOException $e) {
        $mensagem = "Erro: Este e-mail já está cadastrado ou ocorreu um problema.";
    }
}

$ehErro    = $mensagem !== "" && strpos($mensagem, 'Erro') === 0;
$ehSucesso = $mensagem !== "" && !$ehErro;

$titulo = 'Criar conta';
require 'includes/head.php';
require 'includes/header.php';
?>

<main id="conteudo" class="grid flex-1 lg:grid-cols-2">

    <!-- Painel editorial (só desktop) -->
    <aside class="relative hidden overflow-hidden bg-ink text-paper lg:block">
        <div class="absolute inset-0 flex flex-col justify-between p-14">
            <p class="eyebrow !text-paper/50">Nova conta</p>
            <div>
                <h2 class="font-display text-6xl font-extrabold uppercase leading-[0.95] tracking-tight xl:text-7xl">
                    Vista<br>o<br><span class="text-paper/40">essencial.</span>
                </h2>
                <p class="mt-6 max-w-xs text-sm leading-relaxed text-paper/60">
                    Crie sua conta em menos de um minuto e comece a montar seu carrinho.
                </p>
            </div>
        </div>
    </aside>

    <!-- Formulário -->
    <section class="flex items-center justify-center px-5 py-14 sm:px-8">
        <div class="reveal w-full max-w-sm">
            <p class="eyebrow">Cadastro</p>
            <h1 class="mt-2 font-display text-3xl font-bold uppercase tracking-tight">Crie sua conta</h1>

            <!-- Mostramos a mensagem de sucesso ou erro, se houver -->
            <?php if ($ehErro): ?>
                <div class="mt-6 border border-rust/40 bg-rust/5 px-4 py-3 text-sm text-rust" role="alert">
                    <?= e($mensagem) ?>
                </div>
            <?php endif; ?>

            <?php if ($ehSucesso): ?>
                <div class="mt-6 border border-ink/15 bg-white px-5 py-6" role="status">
                    <p class="text-sm font-semibold"><?= e($mensagem) ?></p>
                    <a href="login.php" class="btn btn-dark mt-5 w-full">Ir para o login</a>
                </div>
            <?php else: ?>

            <form method="POST" data-validate class="mt-8 space-y-5" novalidate>
                <div data-field>
                    <label class="label" for="nome">Nome</label>
                    <input class="input" type="text" id="nome" name="nome" required autocomplete="name"
                           placeholder="Seu nome" data-rule="nome"
                           value="<?= e($_POST['nome'] ?? '') ?>">
                    <p class="field-error hidden" data-error></p>
                </div>

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
                        <input class="input pr-20" type="password" id="senha" name="senha" required autocomplete="new-password"
                               placeholder="Mínimo 6 caracteres" data-rule="senha" data-password-main>
                        <button type="button" data-toggle-password class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-semibold uppercase tracking-wider text-ash hover:text-ink" aria-label="Mostrar senha">Mostrar</button>
                    </div>
                    <div class="strength" data-strength data-level="0" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                    <p class="mt-1.5 text-xs text-ash" data-strength-label>Use letras, números e símbolos.</p>
                    <p class="field-error hidden" data-error></p>
                </div>

                <!-- Sem atributo name: serve só para conferência no navegador e não é enviado ao PHP -->
                <div data-field>
                    <label class="label" for="confirmar">Confirmar senha</label>
                    <input class="input" type="password" id="confirmar" autocomplete="new-password"
                           placeholder="Repita a senha" data-rule="confirmar">
                    <p class="field-error hidden" data-error></p>
                </div>

                <button type="submit" class="btn btn-dark w-full" data-label="Cadastrar">Cadastrar</button>
            </form>

            <?php endif; ?>

            <p class="mt-8 text-sm text-ink/70">
                Já tem uma conta?
                <a href="login.php" class="font-semibold underline underline-offset-4 hover:text-ash">Faça login</a>
            </p>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>
