# Laravel Project with Vue.js

This is a Laravel project integrated with Vue.js for the frontend. It provides functionalities related to supplier management and product handling.

## Prerequisites

Before running the project, ensure you have the following installed:

-   [PHP](https://www.php.net/) (version 7.4 or higher).
-   [Composer](https://getcomposer.org/) for managing PHP dependencies.
-   [Node.js](https://nodejs.org/) (version 12 or higher) and [npm](https://www.npmjs.com/) for managing JavaScript dependencies.
-   A local database (like MySQL) set up and configured in the `.env` file.

## How to run?

1. Clone or download this repository to your local machine.

    ```bash
    git clone https://github.com/Buddhikanip/Simple-Laravel-API-Test.git
    ```

2. Navigate to the project directory:
    ```bash
    cd Simple-Laravel-API-Test
    ```
3. Install the PHP dependencies using Composer:
    ```bash
    composer install
    ```
4. Copy the `.env.example` file to create your `.env` file:
    ```bash
    cp .env.example .env
    ```
5. Generate the application key:
    ```bash
    php artisan key:generate
    ```
6. Set up your database configuration in the `.env` file.
7. Run the database migrations and seed:
    ```bash
    php artisan migrate --seed
    ```
8. Open two terminals in the project root directory:

    - In the first terminal, run the Laravel server:

        ```bash
        php artisan serve
        ```

    - In the second terminal, run the Vue.js development server:
        ```bash
        npm install
        npm run dev
        ```

9. The application will be accessible at `http://localhost:8000/` and the Vue.js frontend will be running on a different port (usually with vite `http://localhost:5173/`).

## Project Structure

-   **app/**: Contains the core application code (controllers, models, etc.)
-   **resources/js/**: Contains Vue components and JavaScript files.
-   **resources/views/**: Contains Blade templates for server-rendered views.
-   **routes/**: Contains the application routes.

## Configuration

You can modify the application settings by editing the .env file. Here you can change the database connection settings, app name, and other environment-specific settings.
