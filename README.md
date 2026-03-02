# What The Duck

**What The Duck** is a lightweight PHP MVC web application for sharing and managing jokes.
It includes user authentication, profile management, customizable settings, and a structured MVC architecture built from scratch (no full-stack framework).

---

## Features

* User registration and login
* User profiles
* View and manage jokes
* User settings management
* Database-driven content
* Custom MVC architecture
* Custom error pages (401, 404, 418, 500)

---

## Project Structure

```
What-the-duck/
│
├── config/             # Configuration files (e.g., database)
├── public/             # Public entry point (index.php, assets)
│   └── assets/         # Static assets (images, etc.)
├── routes/             # Route definitions
├── sql/                # Database SQL files
├── src/
│   ├── Controllers/    # Application controllers
│   ├── Core/           # Core system classes (e.g., Database)
│   ├── Models/         # Data models
│   └── Services/       # Business logic layer
├── views/              # View templates
│   ├── errors/         # Error pages
│   ├── home/
│   ├── joke/
│   ├── login/
│   └── settings/
│
├── composer.json       # PHP dependencies
└── public/index.php    # Application entry point
```

---

## Architecture

This project follows a custom **MVC (Model–View–Controller)** pattern:

* **Models** → Handle database interaction
* **Views** → Render UI templates
* **Controllers** → Handle HTTP requests & business logic
* **Services** → Contain reusable business logic
* **Core** → Base framework functionality (e.g., database connection)

---

## Installation

### Clone the Repository

```bash
git clone [git@github.com:Mommy-Of-Light/What-the-duck.git](https://github.com/Mommy-Of-Light/What-the-duck.git)
cd What-the-duck
```

---

### Install Dependencies

Make sure you have **Composer** installed.

```bash
composer install
```

---

### Set Up the Database

1. Create a new MySQL database.
2. Import one of the provided SQL files:

```bash
sql/jokes.sql
```

---

### Configure Database Connection

Copy:

```bash
cp config/database.sample.php config/database.php
```

Then update with your database credentials in **config/database.php**

---

### Run the Application

If using Apache:

* Point your web server root to the `public/` directory.

If using PHP built-in server:

```bash
php -S localhost:8000 -t public
```

Or use the built-in script:

```bash
composer start
```

Then open:

```
http://localhost:8000
```

---

## Authentication

Users can:

* Register
* Log in
* View their profile
* Modify account settings

Session handling is managed within the application.

---

## Error Handling

Custom error pages are included:

* `401.php` – Unauthorized
* `404.php` – Page Not Found
* `418.php` – I'm a teapot
* `500.php` – Server Error

---

## Technologies Used

* PHP (Vanilla, custom MVC)
* MySQL
* Composer (autoloading)
* HTML/CSS
* Apache (.htaccess routing)

---

## Assets

Profile images and static assets are located in:

```
public/assets/
```

---

## License

This project is licensed under the terms defined in the `LICENSE` file.

---

## Future Improvements

* Pagination for jokes
* Joke submission system
* Admin dashboard
* REST API support
* CSRF protection improvements
* Password hashing upgrades (if not already implemented)

---

## Author

Created as a custom PHP MVC learning/project application.
