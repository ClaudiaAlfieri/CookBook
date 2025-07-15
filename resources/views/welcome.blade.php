@extends('layout')

<body>
    <main>
        <section class="hero mt-10" id="inicio">
            <div class="container">
                <div class="hero-content">
                    <h1>Transforme ingredientes em receitas incríveis</h1>
                    <p>Descubra o que cozinhar com os ingredientes que você tem em casa. Nossa IA cria receitas
                        personalizadas para você!</p>
                </div>
            </div>
        </section>

         <section class="features" id="como-funciona">
            <div class="container">
                <h2 class="section-title">Como Funciona</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <span class="feature-icon">📝</span>
                        <h3>Liste seus ingredientes</h3>
                        <p>Digite todos os ingredientes que você tem disponível em casa, desde temperos até proteínas e
                            vegetais.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🤖</span>
                        <h3>IA analisa e sugere</h3>
                        <p>Nossa inteligência artificial analisa seus ingredientes e encontra as melhores combinações
                            para receitas deliciosas.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">👨‍🍳</span>
                        <h3>Cozinhe e aproveite</h3>
                        <p>Receba receitas detalhadas com modo de preparo passo a passo e comece a cozinhar
                            imediatamente!</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="ingredient-section">
                <h2>Quais ingredientes você tem?</h2>
                <p>Digite os ingredientes disponíveis e deixe nossa IA sugerir receitas deliciosas</p>

                <div class="ingredient-input-container">
                    {{-- <span class="input-icon">🥕</span> --}}
                    <input type="text" class="ingredient-input"
                        placeholder="Digite um ingrediente e pressione Enter..." id="ingredientInput">
                </div>

                <div class="ingredient-tags" id="ingredientTags">
                    <!-- Tags de ingredientes serão adicionadas aqui -->
                </div>

                <button class="generate-btn" id="generateBtn">
                    🤖 Gerar Receitas com IA
                </button>
            </div>
        </section>


    </main>

   <script>
        document.addEventListener('DOMContentLoaded', function() {
    const ingredientInput = document.getElementById('ingredientInput');
    const ingredientTags = document.getElementById('ingredientTags');
    const generateBtn = document.getElementById('generateBtn');

    let ingredients = [];

    // Função para adicionar ingrediente
    function addIngredient(ingredient) {
        ingredient = ingredient.trim();

        if (ingredient && !ingredients.includes(ingredient)) {
            ingredients.push(ingredient);
            renderIngredients();
            ingredientInput.value = '';
            updateGenerateButton();
        }
    }

    // Função para remover ingrediente
    function removeIngredient(ingredient) {
        ingredients = ingredients.filter(item => item !== ingredient);
        renderIngredients();
        updateGenerateButton();
    }

    // Função para renderizar os ingredientes na tela
    function renderIngredients() {
        ingredientTags.innerHTML = '';

        ingredients.forEach(ingredient => {
            const tag = document.createElement('span');
            tag.className = 'ingredient-tag';
            tag.innerHTML = `
                ${ingredient}
                <button class="remove-ingredient" onclick="removeIngredient('${ingredient}')">&times;</button>
            `;
            ingredientTags.appendChild(tag);
        });
    }

    // Função para atualizar o botão de gerar
    function updateGenerateButton() {
        if (ingredients.length > 0) {
            generateBtn.disabled = false;
            generateBtn.textContent = `🤖 Gerar Receitas com ${ingredients.length} ingrediente${ingredients.length > 1 ? 's' : ''}`;
        } else {
            generateBtn.disabled = true;
            generateBtn.textContent = '🤖 Gerar Receitas com IA';
        }
    }

    // Função para gerar receita - CORRIGIDA
    async function generateRecipe() {
        if (ingredients.length === 0) return;

        // Verificar se o CSRF token existe
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('Erro: Token CSRF não encontrado. Recarregue a página.');
            return;
        }

        // Mostrar loading
        generateBtn.disabled = true;
        generateBtn.innerHTML = '🤖 Gerando receita...';

        try {
            const response = await fetch('/generate-recipe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ingredients: ingredients
                })
            });

            // Verificar se a resposta é JSON válida
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Resposta do servidor não é JSON válida');
            }

            const data = await response.json();

            if (response.ok && data.success) {
                displayRecipe(data.recipe);
            } else {
                const errorMessage = data.error || 'Erro desconhecido ao gerar receita';
                showError(errorMessage);
            }

        } catch (error) {
            console.error('Erro na requisição:', error);

            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                showError('Erro de conexão. Verifique sua internet e tente novamente.');
            } else if (error.message.includes('JSON')) {
                showError('Erro no servidor. Tente novamente em alguns instantes.');
            } else {
                showError('Erro inesperado. Tente novamente.');
            }
        } finally {
            // Restaurar botão
            generateBtn.disabled = false;
            updateGenerateButton();
        }
    }

    // Função para exibir erros
    function showError(message) {
        // Criar um modal de erro simples
        const errorModal = document.createElement('div');
        errorModal.className = 'recipe-modal';
        errorModal.innerHTML = `
            <div class="recipe-content">
                <button class="close-recipe" onclick="closeError()">&times;</button>
                <div class="recipe-text">
                    <h3 style="color: #e74c3c;">❌ Erro</h3>
                    <p>${message}</p>
                    <button onclick="closeError()" style="
                        background: #e74c3c;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        margin-top: 15px;
                    ">Fechar</button>
                </div>
            </div>
        `;

        document.body.appendChild(errorModal);

        // Função para fechar erro
        window.closeError = function() {
            if (document.body.contains(errorModal)) {
                document.body.removeChild(errorModal);
            }
        };

        // Fechar ao clicar fora
        errorModal.addEventListener('click', function(e) {
            if (e.target === errorModal) {
                closeError();
            }
        });
    }

    // Função para exibir a receita - MELHORADA
    function displayRecipe(recipe) {
        const recipeModal = document.createElement('div');
        recipeModal.className = 'recipe-modal';

        // Formatar o texto da receita
        const formattedRecipe = formatRecipeText(recipe);

        recipeModal.innerHTML = `
            <div class="recipe-content">
                <button class="close-recipe" onclick="closeRecipe()">&times;</button>
                <div class="recipe-text">
                    ${formattedRecipe}
                </div>
            </div>
        `;

        document.body.appendChild(recipeModal);

        // Função para fechar receita
        window.closeRecipe = function() {
            if (document.body.contains(recipeModal)) {
                document.body.removeChild(recipeModal);
            }
        };

        // Fechar ao clicar fora
        recipeModal.addEventListener('click', function(e) {
            if (e.target === recipeModal) {
                closeRecipe();
            }
        });

        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRecipe();
            }
        });
    }

    // Função para formatar o texto da receita
    function formatRecipeText(text) {
        // Substituir quebras de linha por <br>
        let formatted = text.replace(/\n/g, '<br>');

        // Destacar seções importantes (texto em maiúsculas seguido de :)
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formatted = formatted.replace(/^([A-ZÇÁÉÍÓÚÂÊÔÀÃ\s]+:)/gm, '<h3 style="color: #333; margin-top: 20px; margin-bottom: 10px;">$1</h3>');

        // Destacar itens numerados
        formatted = formatted.replace(/^(\d+\.\s)/gm, '<strong>$1</strong>');

        // Destacar itens com hífen
        formatted = formatted.replace(/^(-\s)/gm, '<strong>$1</strong>');

        return formatted;
    }

    // Event listener para o botão de gerar
    generateBtn.addEventListener('click', generateRecipe);

    // Event listener para Enter no input
    ingredientInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addIngredient(this.value);
        }
    });

    // Tornar a função removeIngredient global
    window.removeIngredient = removeIngredient;

    // Inicializar o botão
    updateGenerateButton();

});
    </script>
</body>

