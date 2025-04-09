# API Documentation

This folder contains the API documentation for the User Management System.

## Available Documentation

1. **Postman Collection** (`user-management-api.postman_collection.json`)
   - Import this file into Postman to explore and test all available API endpoints
   - Contains request examples with proper headers, body payloads, and authentication setup
   - Organized into logical collections (Authentication and Users)

2. **OpenAPI/Swagger Specification** (`api-docs.yaml`)
   - Complete API specification in YAML format (OpenAPI 3.0.0)
   - Can be viewed using Swagger UI or any OpenAPI-compatible viewer

## Using the Postman Collection

1. Open Postman
2. Click on "Import" button
3. Select the `user-management-api.postman_collection.json` file
4. After importing, you'll see a new collection named "User Management API"
5. Update the collection variables:
   - `base_url`: Set to your API host (default is http://localhost:8000)
   - `token`: After login, paste your JWT token here for authenticated requests

## Using the Swagger Documentation

You can view the Swagger documentation in several ways:

1. **Using Swagger UI**:
   - Upload the `api-docs.yaml` file to an online Swagger editor like [editor.swagger.io](https://editor.swagger.io/)
   - Or install Swagger UI locally using npm: `npm install -g swagger-ui`

2. **Using Laravel Swagger Packages**:
   - You can also add packages like `darkaonline/l5-swagger` to your Laravel project to generate and serve Swagger documentation directly from your application

## API Endpoints Overview

The API provides the following main endpoints:

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get authenticated user profile
- `POST /api/auth/refresh` - Refresh JWT token

### User Management
- `GET /api/users` - List users (paginated)
- `POST /api/users` - Create new user
- `GET /api/users/{id}` - Get user details
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user
- `GET /api/countries` - Get countries list 