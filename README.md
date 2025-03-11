# TaskFlow - Project Management SaaS by parsleyfilip


TaskFlow is a modern, intuitive project management SaaS application built with Laravel and Blade templating engine. Designed for teams of all sizes, it offers robust project management features while maintaining simplicity and ease of use.

## 🚀 Features

- **Project Management**
  - Create and manage multiple projects
  - Track project progress in real-time
  - Customizable project templates

- **Task Management**
  - Task dependencies and subtasks
  - Priority levels and status tracking

- **Team Collaboration**
  - Role-based access control
  - E-mail based invitations


## 🛠️ Tech Stack

- **Backend**
  - PHP 8.1+
  - Laravel 10.x
  - MySQL 8.0

- **Frontend**
  - Laravel Blade templating
  - TailwindCSS
  - Vue.js

## 📦 Installation

1. Clone the repository:
```bash
git clone https://github.com/parsleyfilip/Project-Manager-App.git
cd taskflow
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Copy environment file and configure:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env` if needed:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seeders:
```bash
php artisan migrate --seed
```

6. Build assets:
```bash
npm run dev
```

7. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## ⭐ Show your support

Give a ⭐️ if this project helped you! 
