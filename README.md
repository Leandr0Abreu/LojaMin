# Loja Minimalista — Front-end

## Estrutura
```
ecommerce/
├── index.php  login.php  cadastro.php  carrinho.php   ← telas (lógica PHP original preservada)
├── adicionar_carrinho.php  logout.php  conexao.php    ← inalterados
├── includes/   head.php · header.php · footer.php · helpers.php
├── assets/css/style.css
├── assets/js/app.ts   ← código-fonte TypeScript
├── assets/js/app.js   ← compilado (é este que o navegador carrega)
└── tsconfig.json
```

## Instalação
Copie a pasta sobre o seu projeto (substitui os 4 arquivos de tela; os demais permanecem iguais).
Tailwind e fontes vêm por CDN, então é preciso internet no navegador.

## Editar o TypeScript
```
npm i -g typescript
tsc -p .        # gera assets/js/app.js
```

## Imagens dos produtos (opcional)
Sem coluna de imagem, a vitrine mostra uma silhueta neutra. Para usar fotos:
```sql
ALTER TABLE produtos ADD COLUMN imagem VARCHAR(255) NULL;
-- ex.: UPDATE produtos SET imagem = 'uploads/camiseta.jpg' WHERE id = 1;
```
O front-end detecta a coluna `imagem` automaticamente.

## Regras de estoque na vitrine
- 1 a 3 unidades: selo vermelho "Últimas N unidades"
- 4 a 10 unidades: selo preto "Poucas unidades"
- acima de 10: apenas "N disponíveis"
- o seletor de quantidade nunca passa do estoque
