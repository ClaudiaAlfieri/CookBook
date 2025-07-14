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

    // Função para gerar receita
    async function generateRecipe() {
        if (ingredients.length === 0) return;

        // Mostrar loading
        generateBtn.disabled = true;
        generateBtn.innerHTML = '🤖 Gerando receita...';

        try {
            const response = await fetch('/generate-recipe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    ingredients: ingredients
                })
            });

            const data = await response.json();

            if (data.success) {
                displayRecipe(data.recipe);
            } else {
                alert('Erro ao gerar receita: ' + data.error);
            }

        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao conectar com o servidor');
        } finally {
            // Restaurar botão
            generateBtn.disabled = false;
            updateGenerateButton();
        }
    }

    // Função para exibir a receita
    function displayRecipe(recipe) {
        // Criar modal ou seção para mostrar a receita
        const recipeModal = document.createElement('div');
        recipeModal.className = 'recipe-modal';
        recipeModal.innerHTML = `
            <div class="recipe-content">
                <button class="close-recipe" onclick="closeRecipe()">&times;</button>
                <div class="recipe-text">
                    ${recipe.replace(/\n/g, '<br>')}
                </div>
            </div>
        `;

        document.body.appendChild(recipeModal);

        // Tornar função global
        window.closeRecipe = function() {
            document.body.removeChild(recipeModal);
        };
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

    // Event listener para o botão de gerar
    generateBtn.addEventListener('click', function() {
        if (ingredients.length > 0) {
            console.log('Ingredientes selecionados:', ingredients);
            // Aqui você pode adicionar a lógica para gerar receitas
            alert('Ingredientes: ' + ingredients.join(', '));
        }
    });

    // Tornar a função removeIngredient global para o onclick
    window.removeIngredient = removeIngredient;

    // Inicializar o botão
    updateGenerateButton();
});
</script>
</body>

