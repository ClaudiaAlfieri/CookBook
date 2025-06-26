@extends('layout')

<body>
    <main>
        <section class="hero mt-10" id="inicio">
            <div class="container">
                <div class="hero-content">
                    <h1>Transforme ingredientes em receitas incríveis</h1>
                    <p>Descubra o que cozinhar com os ingredientes que você tem em casa. Nossa IA cria receitas
                        personalizadas só para você!</p>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="ingredient-section">
                <h2>Quais ingredientes você tem?</h2>
                <p>Digite os ingredientes disponíveis e deixe nossa IA sugerir receitas deliciosas</p>

                <div class="ingredient-input-container">
                    <span class="input-icon">🥕</span>
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
</body>

</html>
