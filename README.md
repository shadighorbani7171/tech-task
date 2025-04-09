# User Management API

## Overview
This is a backend technical task implementing a user management system using Laravel and PostgreSQL. The project follows **Domain-Driven Design (DDD)** and **Test-Driven Development (TDD)** principles. JWT-based authentication is used for securing the API.

## Features Implemented
- ✅ Full CRUD operations for users:
  - Create, Read, Update, Delete
- ✅ JWT authentication system
  - Login, Logout, Refresh, Profile (me)
- ✅ Country selection from a predefined list
- ✅ Value Objects:
  - Email (validated format, lowercase)
  - PhoneNumber (sanitized & validated)
  - Country (predefined values)
- ✅ Profile image upload (optional)
- ✅ Validation via Form Requests
- ✅ Tests:
  - Unit tests for Value Objects & User model
  - Feature tests for Auth & User API
  - 68%+ test coverage
- ✅ PostgreSQL compatibility
- ✅ DDD folder structure

## Tech Stack
- Laravel 10.x
- PostgreSQL 16.x
- JWT (tymon/jwt-auth)
- PHPUnit (Testing)

## Installation
```bash
git clone <repository-url>
cd tech-task
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### Configure `.env` with PostgreSQL:
```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass
```

### Migrate and Seed:
```bash
php artisan migrate --seed
```

## Running the App
```bash
php artisan serve
```

## API Endpoints
### Auth
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `POST /api/auth/refresh`
- `GET /api/auth/me`

### Users (JWT protected)
- `GET /api/users`
- `POST /api/users`
- `GET /api/users/{id}`
- `PUT /api/users/{id}`
- `DELETE /api/users/{id}`

### Country
- `GET /api/countries`

## Project Structure (DDD)
```
app/
├── Domain/
│   └── User/
│       ├── Models/
│       ├── ValueObjects/
│       ├── Services/
│       └── Repositories/
├── Infrastructure/
│   └── User/ (EloquentRepository)
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Middleware/
```

## Testing
```bash
php artisan test --coverage
```
Test coverage: **68.3%** with full coverage on key services and validations.

## Notes
- All features were developed based on task acceptance criteria.
- Follows clean code principles and readable structure.
- Error handling with consistent JSON structure.


