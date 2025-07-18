---

# 🧑‍🍳 AI Cookbook — Laravel + Gemini API

Aplicação web desenvolvida em **Laravel** que transforma ingredientes simples que você tem em casa em receitas completas, criativas e detalhadas. Através da integração com a API **Gemini 1.5 Flash** (Google AI), o sistema gera receitas sob medida para você cozinhar sem desperdício e sem precisar sair de casa.

---

## 🔥 Funcionalidades

✅ Cadastro de ingredientes via interface amigável
✅ Integração com IA para geração de receitas criativas e detalhadas
✅ Limite inteligente para não sobrecarregar a IA (máximo 15 ingredientes)
✅ Retorno com nome da receita, lista de ingredientes, modo de preparo e dicas
✅ Interface leve, responsiva e clara

---

## 🛠️ Tecnologias

* **Laravel 11+**
* **PHP 8.2+**
* **Blade / Tailwind CSS**
* **Google Gemini 1.5 Flash API**
* **Vite** para build frontend
* **Axios / Fetch** para requisições assíncronas

---

## ⚙️ Instalação Local

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio

# Instale as dependências
composer install
npm install && npm run build

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure seu banco de dados no .env
php artisan migrate

# Rode o servidor local
php artisan serve
```

---

## 🔑 Configuração da API Gemini

Adicione sua chave no arquivo `.env`:

```
GEMINI_API_KEY=your-gemini-api-key-here
```

---

## 🚀 Como Funciona

### 1️⃣ O Usuário adiciona ingredientes disponíveis

### 2️⃣ A IA recebe um **prompt estruturado** solicitando:

* Nome criativo para a receita
* Lista de ingredientes com quantidades
* Tempo de preparo e rendimento
* Passo a passo numerado
* Dicas finais

### 3️⃣ O backend chama a API Gemini com esse prompt:

```php
return "Você é um chef experiente. Com base nestes ingredientes: {$ingredientsList}

Crie uma receita deliciosa...
";
```

### 4️⃣ A IA retorna um texto estruturado, exibido para o usuário.

---

## 🖥️ Exemplo de Endpoint

| Método | Rota             | Ação                    |
| ------ | ---------------- | ----------------------- |
| POST   | /generate-recipe | Gera uma receita com IA |

Payload esperado:

```json
{
    "ingredients": ["arroz", "frango", "tomate"]
}
```

Resposta:

```json
{
    "success": true,
    "recipe": "NOME DA RECEITA: ...",
    "ingredients_used": [...]
}
```

---

## 🙋 Sobre o Projeto

Este projeto foi criado com o propósito de unir tecnologia e criatividade na cozinha. Ele tem potencial para evoluir para funcionalidades como:

* Cadastro de usuários
* Favoritar receitas
* Sugestões semanais automáticas
* Integração com APIs de mercado para compras

---
