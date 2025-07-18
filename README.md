---

# 🧑‍🍳 AI Cookbook — Laravel + Gemini API

A web application built with **Laravel** that transforms simple ingredients you already have at home into complete, creative, and detailed recipes. Through integration with the **Gemini 1.5 Flash API** (Google AI), the system generates custom recipes so you can cook without waste and without leaving your house.

---

## 🔥 Features

✅ User-friendly interface for adding ingredients
✅ AI integration to generate creative and detailed recipes
✅ Smart limit to avoid overloading the AI (maximum 15 ingredients)
✅ Returns recipe name, ingredient list, preparation steps, and tips
✅ Lightweight, responsive, and clean interface

---

## 🛠️ Technologies

* **Laravel 11+**
* **PHP 8.2+**
* **Blade / Tailwind CSS**
* **Google Gemini 1.5 Flash API**
* **Vite** for frontend build
* **Axios / Fetch** for asynchronous requests

---

## ⚙️ Local Installation

```bash
# Clone the repository
git clone https://github.com/your-username/your-repository.git
cd your-repository

# Install dependencies
composer install
npm install && npm run build

# Set up environment variables
cp .env.example .env
php artisan key:generate

# Configure your database in .env
php artisan migrate

# Start the local server
php artisan serve
```
---

## 🔑 Gemini API Configuration

Add your API key to the `.env` file:

```
GEMINI_API_KEY=your-gemini-api-key-here
```
---

## 🚀 How It Works

### 1️⃣ The user adds available ingredients

### 2️⃣ The AI receives a **structured prompt** requesting:

* A creative name for the recipe
* A list of ingredients with quantities
* Preparation time and yield
* Step-by-step instructions
* Final tips

### 3️⃣ The backend calls the Gemini API with this prompt:

```php
return "You are an experienced chef. Based on these ingredients: {$ingredientsList}

Create a delicious recipe...
";
```

### 4️⃣ The AI returns a structured text, which is displayed to the user.

---

## 🖥️ Example Endpoint

| Method | Route            | Action                 |
| ------ | ---------------- | ---------------------- |
| POST   | /generate-recipe | Generate recipe via AI |

Expected payload:

```json
{
    "ingredients": ["rice", "chicken", "tomato"]
}
```

Response:

```json
{
    "success": true,
    "recipe": "RECIPE NAME: ...",
    "ingredients_used": [...]
}
```
---

## 🙋 About the Project

This project was created to bring technology and creativity together in the kitchen. It has the potential to evolve with features such as:

* User registration
* Favorite recipes
* Weekly recipe suggestions
* Integration with grocery APIs for shopping lists
* 
---

## 👨‍💻 Author

This project was developed by Claudia Alfieri for educational purposes.

---

## 📝 Contribution 🤝

Contributions are welcome through pull requests.

---

