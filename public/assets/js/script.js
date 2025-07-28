// Função menu hamburguer mobile

document.addEventListener("DOMContentLoaded", function () {
    const mobileLinks = document.querySelectorAll(".nav-link-mobile");
    const toggleButton = document.querySelector(
        '[data-collapse-toggle="navbar-sticky"]'
    );
    const navbar = document.getElementById("navbar-sticky");

    mobileLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (window.innerWidth < 768) {
                const isExpanded =
                    toggleButton.getAttribute("aria-expanded") === "true";

                // Apenas simula o clique no botão se o menu estiver aberto
                if (isExpanded && toggleButton) {
                    toggleButton.click();
                }
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const ingredientInput = document.getElementById("ingredientInput");
    const ingredientTags = document.getElementById("ingredientTags");
    const generateBtn = document.getElementById("generateBtn");

    let ingredients = [];

    // Função para adicionar ingrediente
    function addIngredient(ingredient) {
        ingredient = ingredient.trim();
        if (ingredient && !ingredients.includes(ingredient)) {
            ingredients.push(ingredient);
            renderIngredients();
            ingredientInput.value = "";
            updateGenerateButton();
        }
    }

    // Função para remover ingrediente
    function removeIngredient(ingredient) {
        ingredients = ingredients.filter((item) => item !== ingredient);
        renderIngredients();
        updateGenerateButton();
    }

    // Função para renderizar os ingredientes
    function renderIngredients() {
        ingredientTags.innerHTML = "";
        ingredients.forEach((ingredient) => {
            const tag = document.createElement("span");
            tag.className = "ingredient-tag";
            tag.innerHTML = `
                ${ingredient}
                <button class="tag-remove" onclick="removeIngredient('${ingredient}')">&times;</button>
            `;
            ingredientTags.appendChild(tag);
        });
    }

    // Função para atualizar o botão de gerar receita
    function updateGenerateButton() {
        if (ingredients.length > 0) {
            generateBtn.disabled = false;
            generateBtn.textContent = `🤖 Gerar Receitas com ${
                ingredients.length
            } ingrediente${ingredients.length > 1 ? "s" : ""}`;
        } else {
            generateBtn.disabled = true;
            generateBtn.textContent = "🤖 Gerar Receitas com IA";
        }
    }

    // Função para criar animação de loading dots
    function showLoading() {
        generateBtn.disabled = true;
        generateBtn.innerHTML = `
            <span class="loading-animation">
                🤖 Gerando receita<span class="loading-dots">...</span>
            </span>
        `;

        // Adicionar CSS para animação apenas dos dots
        const style = document.createElement("style");
        style.textContent = `
            .loading-animation {
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .loading-dots {
                animation: loadingDots 1.5s infinite;
            }

            @keyframes loadingDots {
                0% { opacity: 0; }
                25% { opacity: 1; }
                50% { opacity: 0; }
                75% { opacity: 1; }
                100% { opacity: 0; }
            }

            .generate-btn:disabled {
                opacity: 0.8;
                cursor: not-allowed;
                pointer-events: none;
            }
        `;
        document.head.appendChild(style);
    }

    // Função para gerar receita
    async function generateRecipe() {
        if (ingredients.length === 0) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showModal(
                "❌ Erro",
                "Token CSRF não encontrado. Recarregue a página."
            );
            return;
        }

        showLoading();

        try {
            const response = await fetch("/generate-recipe", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    ingredients,
                }),
            });

            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Resposta do servidor não é JSON válida");
            }

            const data = await response.json();

            if (response.ok && data.success) {
                displayRecipe(data.recipe);
            } else {
                showModal(
                    "❌ Erro",
                    data.error || "Erro desconhecido ao gerar receita"
                );
            }
        } catch (error) {
            console.error("Erro na requisição:", error);

            let errorMessage = "Erro inesperado. Tente novamente.";
            if (error.name === "TypeError" && error.message.includes("fetch")) {
                errorMessage =
                    "Erro de conexão. Verifique sua internet e tente novamente.";
            } else if (error.message.includes("JSON")) {
                errorMessage =
                    "Erro no servidor. Tente novamente em alguns instantes.";
            }

            showModal("❌ Erro", errorMessage);
        } finally {
            generateBtn.disabled = false;
            updateGenerateButton();
        }
    }

    // Função universal para exibir modais
    function showModal(title, content, isRecipe = false) {
        const modal = document.createElement("div");
        modal.className = "recipe-modal";

        const formattedContent = isRecipe
            ? formatRecipeText(content)
            : `<p>${content}</p>`;

        modal.innerHTML = `
            <div class="recipe-content">
                <button class="close-recipe" onclick="closeModal()">&times;</button>
                <div class="recipe-text">
                    <h3 style="color: ${
                        isRecipe ? "#87A96B" : "#e74c3c"
                    };">${title}</h3>
                    ${formattedContent}
                    ${
                        !isRecipe
                            ? '<button onclick="closeModal()" style="background: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 15px;">Fechar</button>'
                            : ""
                    }
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Função global para fechar modal
        window.closeModal = function () {
            if (document.body.contains(modal)) {
                document.body.removeChild(modal);
            }
        };

        // Fechar ao clicar fora ou ESC
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });

        document.addEventListener("keydown", function handleEsc(e) {
            if (e.key === "Escape") {
                closeModal();
                document.removeEventListener("keydown", handleEsc);
            }
        });
    }

    // Função para exibir receita
    function displayRecipe(recipe) {
        showModal("🍽️", recipe, true);
    }

    // Função para formatar o texto da receita
    function formatRecipeText(text) {
        return text
            .replace(/\n/g, "<br>")
            .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
            .replace(
                /^([A-ZÇÁÉÍÓÚÂÊÔÀÃ\s]+:)/gm,
                '<h3 style="color: #333; margin-top: 20px; margin-bottom: 10px;">$1</h3>'
            )
            .replace(/^(\d+\.\s)/gm, "<strong>$1</strong>")
            .replace(/^(-\s)/gm, "<strong>$1</strong>");
    }

    // Event listeners
    generateBtn.addEventListener("click", generateRecipe);

    ingredientInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addIngredient(this.value);
        }
    });

    // Tornar função global
    window.removeIngredient = removeIngredient;

    // Inicializar
    updateGenerateButton();
});
