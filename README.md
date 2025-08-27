# Score Management System API

A **Laravel-based backend API** for managing teams, players, matches, lineups, scores, and statistics. The system supports authentication using **Laravel Sanctum** and is structured to provide a clean and maintainable API.

---

## Table of Contents

- [Features](#features)  
- [Tech Stack](#tech-stack)  
- [Requirements](#requirements)  
- [Installation](#installation)  
- [Environment Setup](#environment-setup)  
- [Database Setup](#database-setup)  
- [Running the Application](#running-the-application)  
- [API Endpoints](#api-endpoints)  
- [Authentication](#authentication)  
- [Testing](#testing)  
- [Folder Structure](#folder-structure)  
- [License](#license)  

---

## Features

- User authentication via **Laravel Sanctum**  
- CRUD operations for:  
  - Teams  
  - Players  
  - Matches  
  - Match lineups  
  - Match stats  
- Score tracking and calculation of winners  
- Paginated match listings  
- Fully structured API responses with **JSON resources**  
- Transaction-safe updates for scores, lineups, and stats  

---

## Tech Stack

- **Backend:** Laravel 10  
- **Database:** MySQL / MariaDB (configurable)  
- **Authentication:** Laravel Sanctum (Bearer token)  
- **API Documentation:** Scramble/OpenAPI compatible  
- **PHP Version:** 8.1+  

---

## Requirements

- PHP 8.1 or higher  
- Composer  
- MySQL or compatible database  
---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/NeerajBisht-git/score_management_system.git
cd score-management-system
```

2. Install PHP dependencies:

```bash
composer install
```

---

## Environment Setup

1. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

2. Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=score_management
DB_USERNAME=root
DB_PASSWORD=
```

3. Generate the application key:

```bash
php artisan key:generate
```

---

## Database Setup

1. Run migrations:

```bash
php artisan migrate
```

2. (Optional) Seed the database:

```bash
php artisan db:seed --class=DemoSeeder
```

---

## Running the Application

Start the development server:

```bash
php artisan serve
```

The API will be available at:

```
http://127.0.0.1:8000/docs/api
```

---

## API Endpoints

### Authentication

| Method | Endpoint       | Description                 |
|--------|----------------|-----------------------------|
| POST   | `/api/login`   | Login and get Bearer token  |
| POST   | `/api/logout`  | Logout user and revoke token|
| GET    | `/api/user`    | Get authenticated user info |

### Teams

| Method | Endpoint               | Description                  |
|--------|------------------------|------------------------------|
| GET    | `/api/teams`           | List all active teams        |
| POST   | `/api/teams`           | Create a new team            |
| GET    | `/api/teams/{team}`    | Get team details             |
| PATCH  | `/api/teams/{team}`    | Update team details          |

### Players

| Method | Endpoint               | Description                  |
|--------|------------------------|------------------------------|
| GET    | `/api/players`         | List all players             |
| POST   | `/api/players`         | Create a player              |
| GET    | `/api/players/{id}`    | Get player details           |
| PATCH  | `/api/players/{id}`    | Update player                |
| DELETE | `/api/players/{id}`    | Remove player                |

### Matches

| Method | Endpoint                       | Description                     |
|--------|--------------------------------|---------------------------------|
| GET    | `/api/matches`                  | List matches (paginated)        |
| POST   | `/api/matches`                  | Create a match                  |
| GET    | `/api/matches/{match}`          | Get match details               |
| PATCH  | `/api/matches/{match}`          | Update match details            |
| PATCH  | `/api/matches/{match}/score`    | Update match set scores         |
| GET    | `/api/matches/{match}/stats`    | Get match stats                 |
| PATCH  | `/api/matches/{match}/stats`    | Update match stats              |
| GET    | `/api/matches/{match}/lineups`  | Get match lineups               |
| PATCH  | `/api/matches/{match}/lineups`  | Update match lineups            |

### Scoreboard

| Method | Endpoint                       | Description                     |
|--------|--------------------------------|---------------------------------|
| GET    | `/api/scoreboard/{match}`      | Get full scoreboard for match   |

---

## Authentication

- All protected routes require a **Bearer token** issued by the `/api/login` endpoint.
- Include the token in request headers:

```
Authorization: Bearer <token>
```

---

## Testing

- You can use **Postman**, **Insomnia**, or **Scramble** to test endpoints.  
---

## Folder Structure

```
app/
 ├── Http/
 │    ├── Controllers/Api/    # API controllers
 │    ├── Requests/           # Form requests for validation
 │    └── Resources/          # JSON resources
 ├── Models/                  # Eloquent models
 └── Services/                # Business logic services

database/
 ├── migrations/
 └── seeders/
routes/
 └── api.php                  # API routes
```

---

## License

This project is open-source and available under the **MIT License**.

