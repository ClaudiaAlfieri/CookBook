// Funcionalidade para adicionar ingredientes
        const ingredientInput = document.getElementById('ingredientInput');
        const ingredientTags = document.getElementById('ingredientTags');
        const generateBtn = document.getElementById('generateBtn');
        let ingredients = [];

        // Adicionar ingrediente ao pressionar Enter
        ingredientInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                addIngredient(this.value.trim());
                this.value = '';
            }
        });

        function addIngredient(ingredient) {
            if (!ingredients.includes(ingredient.toLowerCase())) {
                ingredients.push(ingredient.toLowerCase());
                renderIngredients();
                updateGenerateButton();
            }
        }

        function removeIngredient(ingredient) {
            ingredients = ingredients.filter(item => item !== ingredient);
            renderIngredients();
            updateGenerateButton();
        }

        function renderIngredients() {
            ingredientTags.innerHTML = ingredients.map(ingredient => `
                <span class="ingredient-tag">
                    ${ingredient}
                    <button class="tag-remove" onclick="removeIngredient('${ingredient}')">×</button>
                </span>
            `).join('');
        }

        function updateGenerateButton() {
            if (ingredients.length > 0) {
                generateBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                generateBtn.textContent = `🤖 Gerar Receitas com ${ingredients.length} ingrediente${ingredients.length > 1 ? 's' : ''}`;
            } else {
                generateBtn.style.background = 'linear-gradient(135deg, #6b7280, #4b5563)';
                generateBtn.textContent = '🤖 Adicione ingredientes para começar';
            }
        }

        // Funcionalidade do botão gerar
        generateBtn.addEventListener('click', function() {
            if (ingredients.length > 0) {
                // Aqui você integrará com sua API Laravel
                generateBtn.textContent = '🔄 Gerando receitas...';
                generateBtn.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';

                // Simulação de chamada para API
                setTimeout(() => {
                    alert(`Gerando receitas com: ${ingredients.join(', ')}\n\nEsta funcionalidade será integrada com sua API Laravel!`);
                    generateBtn.textContent = `🤖 Gerar Receitas com ${ingredients.length} ingrediente${ingredients.length > 1 ? 's' : ''}`;
                    generateBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                }, 2000);
            }
        });

        // Inicializar estado do botão
        updateGenerateButton();

        // Animação suave para scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
