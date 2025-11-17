# Inception - Docker Project

This repository contains a comprehensive guide to Docker and the Inception project setup.

## Table of Contents
- [What is Docker?](#what-is-docker)
- [Docker Architecture & Structure](#docker-architecture--structure)
- [How Docker Works](#how-docker-works)
- [PID 1 & Container Process](#pid-1--container-process)
- [Docker Engine vs Docker Daemon](#docker-engine-vs-docker-daemon)
- [Docker Compose](#docker-compose)
- [Docker Networking](#docker-networking)
- [Steps to Create a Valid Docker Project](#steps-to-create-a-valid-docker-project)
- [Why Docker?](#why-docker)
- [Project Structure](#project-structure)
- [Docker Installation Guide](#docker-installation-guide)

---

## What is Docker?

Docker is a platform for containerization.

It allows you to package an application + its dependencies into a container.

A container is like a lightweight virtual machine, but it shares the host kernel instead of emulating hardware.

This makes containers faster, lighter, and more portable than VMs.

👉 Instead of "it works on my machine," Docker ensures the same environment everywhere.

---

## Docker Architecture & Structure

Docker follows a client–server architecture:

### Docker Client
- You type commands (docker build, docker run, …).
- The client talks to the Docker Daemon (via REST API over Unix socket or TCP).

### Docker Daemon (dockerd)
- The background service that manages images, containers, volumes, networks.
- It listens to the client requests and executes them.

### Docker Engine
- This is the whole runtime system = Client + Daemon + REST API.

### Docker Objects
- **Images** → read-only templates used to create containers.
- **Containers** → running instances of images.
- **Networks** → isolated environments for containers to communicate.
- **Volumes** → persistent data storage, decoupled from container lifecycle.

---

## How Docker Works

When you `docker run`:

1. The daemon pulls the image (if not present locally).
2. It creates a container filesystem (union of image layers).
3. It sets up network namespace (IP, hostname, DNS).
4. It mounts volumes (if defined).
5. It starts the container with one main process (PID 1) inside.

Containers run in namespaces (process isolation, networking, file systems) and cgroups (resource limits).

---

## PID 1 & Container Process

In Linux, PID 1 is the first process inside a container.

- It has special responsibilities (reaping zombie processes, handling signals).
- If PID 1 dies, the container stops.

That's why Dockerfiles usually end with:
```dockerfile
CMD ["nginx", "-g", "daemon off;"]
```

→ ensures the main service runs as PID 1.

### 👉 In our project:
- `mysqld_safe` runs as PID 1 in the MariaDB container.
- `php-fpm` runs as PID 1 in the WordPress container.
- `nginx` runs as PID 1 in the Nginx container.

---

## Docker Engine vs Docker Daemon

- **Docker Engine** = whole system (CLI, Daemon, API).
- **Docker Daemon (dockerd)** = just the background process managing containers.
- **Docker Client** = CLI tool that talks to the Daemon.

---

## Docker Compose

Docker Compose is a tool to define and run multi-container apps.

Instead of manually running containers one by one, you define a `docker-compose.yml` with:
- Services (containers)
- Networks
- Volumes
- Dependencies (depends_on)

You launch everything with:
```bash
docker-compose up --build
```

### 👉 In our project:
- `mariadb` service = DB container
- `wordpress` service = PHP-FPM + WordPress container
- `nginx` service = Web server + SSL container
- Volumes `wp` & `db` keep data persistent.
- `app-network` allows communication between services.

---

## Docker Networking

Docker creates different network drivers:

- **bridge** (default): isolated network with private IPs, containers talk via service names.
- **host**: shares the host network.
- **overlay**: for multi-host Swarm.
- **none**: no networking.

### 👉 In Compose, we defined:
```yaml
networks:
  app-network:
    driver: bridge
```

So:
- `wordpress` can connect to `mariadb` by hostname `mariadb:3306`.
- `nginx` can reach `wordpress` via `wordpress:9000`.

---

## Steps to Create a Valid Docker Project

1. **Plan architecture**: Which services? Which should be separate containers?
   (Inception constraint: one service per container).

2. **Write Dockerfiles for each service**:
   - Start from `alpine` or `debian`.
   - Install only what is needed.
   - Copy configs, scripts.
   - Set `CMD` or `ENTRYPOINT` to run the main process.

3. **Use volumes for persistence** (databases, user files).

4. **Configure networks** so services can talk by name.

5. **Secure services** (SSL for Nginx, non-root DB user, etc.).

6. **Write docker-compose.yml** to orchestrate everything.

7. **Test lifecycle**: build, up, down, restart → check persistence and networking.

8. **Clean images**: small base images, remove cache, don't leave secrets inside.

---

## Why Docker?

- **Consistency** → same environment everywhere.
- **Isolation** → each service independent.
- **Portability** → runs on any host.
- **Scalability** → containers scale horizontally.
- **Reproducibility** → CI/CD pipelines, reproducible builds.

---

## Project Structure

```
🖥️ Host Machine (laptop/PC)
───────────────────────────────
/home/akrid/Desktop/inception/
├── srcs/
│   ├── requirements/
│   │   ├── wordpress/
│   │   ├── mariadb/
│   │   └── nginx/
│   └── docker-compose.yml
└── secrets/
    └── mariadb_password.txt
            
─────────────────────────────────────────────────────────────
🐳 Docker Engine
─────────────────────────────────────────────────────────────
Each container has its own isolated **filesystem root `/`**
(Debian-like structure: /etc, /usr, /var, /run, ...)

📦 wordpress container
──────────────────────
/
├── usr/local/bin/wp        ← WP-CLI binary
├── etc/php/7.4/fpm/        ← PHP-FPM configs
└── var/www/html/           ← WordPress site files
        ├── index.php
        ├── wp-config.php
        ├── wp-content/
        └── wp-admin/

📦 mariadb container
────────────────────
/
├── etc/mysql/              ← MariaDB configs
└── var/lib/mysql/          ← Database files (mounted volume)

📦 nginx container
─────────────────
/
├── etc/nginx/              ← nginx.conf + ssl certs
└── var/www/html/           ← (same WP files, shared volume)
                                         
─────────────────────────────────────────────────────────────
📦 Docker Volumes
─────────────────────────────────────────────────────────────
wordpress_data → mounted to /var/www/html in wordpress + nginx
mariadb_data   → mounted to /var/lib/mysql in mariadb
```

---

## Docker Installation Guide

### Step 1: Update System 

```bash
sudo apt update
sudo apt upgrade -y
```

### Step 2: Install Dependencies

```bash
sudo apt install ca-certificates curl gnupg lsb-release -y
```

### Step 3: Add Docker's Official GPG Key

GPG (GNU Privacy Guard key)

```bash
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
```

### Step 4: Set up the Repository

```bash
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

### Step 5: Install Docker Engine

```bash
sudo apt update
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin -y
```

### Step 6: Start and Enable Docker

```bash
sudo systemctl start docker
sudo systemctl enable docker
```

### Step 7: Verify Docker Installation

```bash
docker --version
```

### Step 8: Optional - Run Docker as Non-Root User

```bash
sudo usermod -aG docker $USER
newgrp docker
```

---

## Getting Started

After installing Docker, you can build and run the Inception project:

```bash
# Navigate to the project directory
cd srcs

# Build and start all services
docker-compose up --build

# Stop all services
docker-compose down

# Stop and remove volumes (clean slate)
docker-compose down -v
```

---

## License

This project is part of the 42 School curriculum.
