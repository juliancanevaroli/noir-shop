# 🖤 NOIR — Loja de Moda Virtual

> Projeto de portfólio full-stack: carrinho de compras completo desenvolvido com PHP, MySQL, HTML, CSS e JavaScript.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)

---

## 📋 Sobre o Projeto

O **NOIR** é uma loja de moda virtual fictícia desenvolvida como projeto de portfólio. O objetivo foi construir uma aplicação **full-stack real**, conectando front-end, back-end e banco de dados.

A identidade visual da loja é sofisticada e elegante — fundo claro em tons de creme, navbar escura e detalhes em dourado envelhecido.

---

## ✨ Funcionalidades

- 🛍️ Catálogo de produtos listados dinamicamente do banco de dados
- 🔍 Busca por nome, categoria e faixa de preço
- 🛒 Carrinho de compras com sessões PHP
- ⚡ Adicionar produtos **sem recarregar a página** (AJAX)
- 🔢 Atualização de quantidade com subtotal em tempo real
- 🗑️ Remoção de itens com animação
- 📦 Finalização de pedido com transação SQL
- 📄 Página institucional (Sobre)
- 📱 Layout responsivo (mobile, tablet, desktop)

---

## 🛠️ Tecnologias

| Tecnologia | Uso |
|---|---|
| HTML5 | Estrutura das páginas |
| CSS3 + Variáveis CSS | Estilização e responsividade |
| JavaScript (Vanilla) | Interações assíncronas com Fetch API |
| PHP 8.x | Back-end, sessões e lógica de negócio |
| MySQL | Banco de dados relacional |
| PDO | Conexão segura com prepared statements |

---

## 📁 Estrutura do Projeto

```
noir-shop/
├── index.php               → Catálogo de produtos
├── carrinho.php            → Página do carrinho
├── finalizar.php           → Checkout
├── busca.php               → Busca e filtros
├── sobre.php               → Página institucional
│
├── actions/
│   ├── adicionar.php       → Adiciona produto ao carrinho (AJAX)
│   ├── atualizar.php       → Atualiza quantidade (AJAX)
│   ├── remover.php         → Remove item do carrinho (AJAX)
│   ├── contar_carrinho.php → Retorna total de itens (AJAX)
│   └── calcular_total.php  → Retorna valor total (AJAX)
│
├── db/
│   ├── conexao.php         → ⚠️ NÃO versionado (ver .gitignore)
│   ├── conexao.example.php → Modelo de configuração
│   └── banco.sql           → Script para criar tabelas e dados
│
├── css/
│   └── style.css           → Estilos globais
│
├── js/
│   └── main.js             → JavaScript do front-end
│
└── img/                    → Imagens dos produtos
```

---

## 🚀 Como Rodar Localmente

### Pré-requisitos
- [XAMPP](https://www.apachefriends.org/) instalado (Apache + PHP + MySQL)

### Passo a Passo

**1. Clone o repositório**
```bash
git clone https://github.com/seu-usuario/noir-shop.git
```

**2. Mova para o htdocs do XAMPP**
```
C:\xampp\htdocs\noir-shop\
```

**3. Inicie o Apache e MySQL** no XAMPP Control Panel

**4. Crie o banco de dados**
- Acesse `http://localhost/phpmyadmin`
- Crie um banco chamado `noir_loja`
- Importe o arquivo `db/banco.sql`

**5. Configure a conexão**
```bash
cp db/conexao.example.php db/conexao.php
```

Edite o `db/conexao.php` com suas credenciais:
```php
$host    = 'localhost';
$banco   = 'noir_loja';
$usuario = 'root';
$senha   = '';
```

**6. Acesse**
```
http://localhost/noir-shop
```

---

## 💡 Conceitos Aplicados

| Conceito | Onde é usado |
|---|---|
| PDO + Prepared Statements | Proteção contra SQL Injection |
| Sessions PHP | Carrinho persistente entre páginas |
| Fetch API (AJAX) | Interações sem recarregar a página |
| Transações SQL | Integridade ao finalizar pedido |
| CSS Custom Properties | Paleta de cores centralizada |
| CSS Grid | Layout responsivo do catálogo |
| `htmlspecialchars()` | Proteção contra XSS |
| `LOWER() + LIKE` | Busca case-insensitive no MySQL |

---

## 🗄️ Banco de Dados

```
produtos          pedidos            itens_pedido
─────────────     ──────────────     ──────────────────────
id (PK)           id (PK)            id (PK)
nome              nome_cliente       pedido_id (FK)
descricao         email_cliente      produto_id (FK)
preco             total              quantidade
categoria         status             preco_unit
imagem            criado_em
estoque
criado_em
```

---

## 🔮 Melhorias Futuras

- [ ] Sistema de login e cadastro
- [ ] Painel administrativo
- [ ] Envio de e-mail com PHPMailer
- [ ] Integração com gateway de pagamento

---

## ⚠️ Segurança

O arquivo `db/conexao.php` contém credenciais do banco e **não é versionado**. Está listado no `.gitignore`. Use o `db/conexao.example.php` como referência ao clonar o projeto.

---

## 👤 Autor

Desenvolvido por Julia N. Canevaroli — segundo projeto de portfólio.

[![GitHub](https://img.shields.io/badge/GitHub-juliancanevaroli-181717?style=flat&logo=github)](https://github.com/juliancanevaroli)
