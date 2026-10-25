# 🛒 E-commerce Minimalista (PHP + MySQL) - Projeto Acadêmico

Este repositório contém um projeto de e-commerce básico desenvolvido como atividade avaliativa para a faculdade. O objetivo principal foi implementar a lógica de um sistema web full-stack do zero, utilizando **PHP** para o back-end, **MySQL** para o gerenciamento de banco de dados, além de controle de estoque, sessões de usuário e carrinho de compras.

---

## 🚀 Tecnologias Utilizadas

* **Linguagem:** PHP (com PDO para segurança nas consultas SQL)
* **Banco de Dados:** MySQL / MariaDB (via XAMPP ou ambiente local similar)
* **Gerenciamento de Estado:** PHP Sessions (`$_SESSION`)
* **Front-end:** HTML5 / CSS (estruturado de forma modular para integração visual)

---

## 🛠️ Funcionalidades Implementadas

1. **Autenticação de Usuários:**
   * Sistema de cadastro com criptografia de senha (`password_hash`).
   * Sistema de login com validação segura (`password_verify`).
   * Controle de sessão ativa e encerramento (Logout).
2. **Vitrine e Estoque:**
   * Listagem dinâmica de produtos diretamente do banco de dados.
   * Validação visual e lógica para exibir apenas produtos com estoque disponível (`estoque > 0`).
3. **Carrinho de Compras:**
   * Adição de produtos ao carrinho utilizando sessões do PHP.
   * Cálculo automático de subtotais e valor total da compra com base no banco de dados.

---

## 📂 Estrutura de Pastas

```text
lojaroupa/
│
├── conexao.php             # Arquivo central de conexão com o banco de dados (PDO)
├── cadastro.php            # Tela e lógica de cadastro de novos usuários
├── login.php               # Tela e lógica de autenticação de usuários
├── logout.php              # Encerramento da sessão do usuário
├── index.php               # Vitrine principal (Listagem de produtos e estoque)
├── adicionar_carrinho.php  # Lógica de controle do carrinho na sessão
└── carrinho.php            # Tela de resumo do carrinho e valor total