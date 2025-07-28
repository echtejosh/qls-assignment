# QLS assignment

This is a simple Laravel application for the QLS assignment.

---

## Prerequisites

- Install **Docker Desktop** on your machine:

    - [Docker Desktop for Windows](https://docs.docker.com/desktop/install/windows-install/)
    - [Docker Desktop for Mac](https://docs.docker.com/desktop/install/mac-install/)
    - For Linux, install Docker Engine: https://docs.docker.com/engine/install/

Make sure Docker is running after installation.

---

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

   Replace `<container_name>` with the actual container name running your Laravel app.

   To find the container name, run:

   ```bash
   docker ps
   ```

   Then run migrations inside the container:

   ```bash
   docker exec -it <container_name> php artisan migrate
   ```

4. **Access the application**

   Open your browser and navigate to:

   ```
   http://127.0.0.1:8000
   ```

