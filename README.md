# QLS Assignment

This is a simple Laravel application for the QLS assignment.

## Prerequisites

> [!IMPORTANT]
> The application will NOT work unless Docker is running.

- Install **Docker Desktop** on your machine:

    - [Docker Desktop for Windows](https://docs.docker.com/desktop/install/windows-install/)
    - [Docker Desktop for Mac](https://docs.docker.com/desktop/install/mac-install/)

Make sure Docker is running after installation.

## Getting Started

1. **Build the Docker containers**

   ```bash
   docker-compose build
   ```

2. **Start the containers**

   ```bash
   docker-compose up -d
   ```

3. **Run database migrations**

   ```bash
   docker-compose exec app php artisan migrate
   ```

4. **Access the application**

> [!NOTE]
> It might take a short time before the application is loaded on the browser, especially during the first run.

   Open your browser and navigate to:

   ```
   http://127.0.0.1:8000
   ```
