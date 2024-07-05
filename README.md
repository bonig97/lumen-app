# Lumen API Project

## Description

This project is a simple RESTful API built using the Lumen micro-framework by Laravel.
The project allows the management of profiles with full CRUD (Create, Read, Update, Delete) functionality, including associated attributes for each profile.
Security is ensured through Bearer token authentication for all requests other than GET.

## Technologies Used

- PHP 8.1
- Lumen 8.x
- MySQL 8.0
- Docker
- Composer
- PHPUnit for testing

## Requirements

- Docker and Docker Compose installed on your machine
- Git

## Installation Instructions

### Step 1: Copy Configuration Files

Copy the .env.dist file to .env and the .env.testing.dist file to .env.testing.

```bash
cp .env.dist .env
cp .env.testing.dist .env.testing
```

### Step 2: Build and Start Docker Containers

Make sure Docker is running.
Then, run the following command to build and start the containers.

```bash
docker compose up --build -d
```

### Step 3: Run Migrations

Once the containers are running, execute the migrations to set up the database.

```bash
bin/docker/artisan migrate
bin/docker/artisan migrate --env=testing
```

### Step 4: Import Postman Collection

The repository includes a [LumenAPI.postman_collection.json](LumenAPI.postman_collection.json) file that can be imported into Postman to easily test the APIs.
Import this file into your Postman.

```bash
bin/docker/phpunit
```

### Step 5: Interact with the APIs

You can now interact with the APIs using Postman or any other HTTP client.
Make sure to include the Bearer token for requests.

## Available Endpoints

- GET /api/profiles - Retrieve all profiles
- POST /api/profiles - Create a new profile
- GET /api/profiles/{id} - Retrieve a specific profile
- PUT /api/profiles/{id} - Update a specific profile
- DELETE /api/profiles/{id} - Delete a specific profile

## Middleware

- Log Middleware: Logs every request to a log file.
- Auth Middleware: Verifies the presence of a Bearer token for all requests.

## Events

For every CRUD operation, an event is fired which writes a log entry to the [app/storage/logs/access.log](storage/logs/access.log) file.

## Author

- Name: Gabriele Boni
- Email: bonig97@gmail.com
