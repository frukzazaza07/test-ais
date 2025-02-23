# Laravel 10 + Inertia.js + Vue 3

This is a Laravel 10 project using Inertia.js with Vue 3 as the frontend framework. It optionally includes a Docker setup with Nginx, MySQL, and Node.js for building assets.

## Features
- Laravel 10 as the backend framework
- Inertia.js for server-side rendering with Vue 3
- Vue 3 with Vite for frontend assets
- Optional Docker support with Nginx, MySQL, and Node.js

---

## Installation (Without Docker)

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 5.7+

### Steps
```sh
# Clone the repository
git clone <repo-url> project-name
cd project-name

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy the .env file and configure database settings
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate
php DB:seed

# Start the development server
php artisan serve

# Start Vite for frontend hot-reloading
npm run dev
```

---

## Running with Docker

### Prerequisites
- Docker & Docker Compose

### Steps
```sh
# Clone the repository
git clone <repo-url> project-name
cd project-name

# Copy environment file
cp .env.example .env

# Build and start the containers
docker-compose up --build -d

# After container started wait install finish in container
docker logs -f laravel_app
```

This will start the application with the following services:
- `app` → Laravel application running on PHP 8.2
- `mysql` → MySQL 5.7 database
- `nginx` → Web server serving the Laravel app
- `node` → Node.js container for building Vue assets

### Running Commands inside Docker
If you need to run artisan or npm commands, use:
```sh


```

---

## Environment Variables
Make sure to update the `.env` file with the correct database credentials:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

For Docker, ensure the MySQL container name (`db`) matches `DB_HOST`.

---

## Building Frontend Assets
For development:
```sh
npm run dev
```
For production:
```sh
npm run build
```
If using Docker:
```sh
docker exec -it node npm run build
```

---

## API Endpoints
- `GET /` → Home Page

---

## License
This project is open-source under the [MIT license](LICENSE).

