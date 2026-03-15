# WTG Spain

WTG Spain is a Laravel + Vite based application that uses WebSockets (Pusher), queues, and Docker for local development.

This guide explains how to run the project locally.

---

# Requirements

Make sure you have installed:

* Docker
* Node.js (>=18)
* npm
* PHP (>=8.2 recommended)
* Composer

---

# Installation

Clone the repository and install dependencies:

```bash
npm install
composer update
```

---

# Environment Configuration

Create and configure the `.env` file.

You can copy the default example:

```bash
cp .env.example .env
```

Pay special attention to the following environment variables.

---

## Database Configuration

```env
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

---

## Mail Configuration

```env
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

---

## Pusher Configuration (Broadcasting)

```env
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
```

These credentials are required for real-time events and broadcasting.

---

# Running the Project

## 1. Start Vite (Frontend)

Open a terminal and run:

```bash
npm run dev
```

Vite will start on:

```
http://localhost:5173
```

---

## 2. Start Docker Containers

Run Laravel Sail:

```bash
./vendor/bin/sail up -d
```

This will start the application containers in detached mode.

---

## 3. Enter the Docker Container

```bash
./vendor/bin/sail shell
```

---

## 4. Start the Queue Worker

Inside the container run:

```bash
php artisan queue:work --queue=default
```

This worker listens for broadcasting events and processes queued jobs.

---

# Useful Commands

Start containers:

```bash
./vendor/bin/sail up -d
```

Stop containers:

```bash
./vendor/bin/sail down
```

Enter container shell:

```bash
./vendor/bin/sail shell
```

Run queue worker:

```bash
php artisan queue:work --queue=default
```

---

# Project Stack

* Laravel
* Laravel Sail (Docker)
* Vite
* Vue
* Pusher (WebSockets)
* Redis / Queue workers

---

# Development Workflow

Typical workflow:

1. Start Docker

```bash
./vendor/bin/sail up -d
```

2. Start Vite

```bash
npm run dev
```

3. Start Queue Worker

```bash
php artisan queue:work --queue=default
```

---

# Notes

* Make sure `.env` is configured correctly before starting the application.
* Queue worker must be running for broadcasting and async jobs to work properly.

---

# Project Name

WTG Spain
