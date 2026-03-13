# 🏫 School Management System (Admin Panel)

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300">
</p>

A robust and stylish School Management System built with **Laravel 8**. This project features a sophisticated Admin Panel with a focus on Glassmorphism UI and relational data management.

---

## 🔥 Key Features

### 👨‍💼 Admin Dashboard
- **Live Statistics:** Real-time counters for total Students, Teachers, Classes, and Subjects.
- **Status Breakdown:** Visual stats for Active, Pending, and Inactive users.

### 🏫 Class & Student Management
- **Class-Wise Filtering:** View students specific to their enrolled classes. One-click access to manage students within a particular class.
- **Bulk Actions:** Approve, Activate, Inactivate, or Delete multiple students/teachers at once.
- **Individual Controls:** Toggle status and manage individual student records efficiently.

### 📚 Student\Teacher & Academics
- **Student\Teacher Management:** Full CRUD (Create, Read, Update, Delete) operations for Student and Teacher.
- **Relational Structure:** Seamlessly link students to classes and academic subjects.
- **Subject Management:** Full(Create, Read, Delete) operations for school subjects.

### 👤 Profile Customization
- **Dynamic Profile Image:** Upload profile photos with a custom naming logic (`name_timestamp`) for better server organization.
- **Security:** Update name, email, and password with real-time validation and secure hashing.

---

## 🛠️ Installation Guide

Follow these steps to set up the project locally on your machine:

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/manishsahu6474/school-managment-system.git
Install Dependencies:

Bash
composer install
npm install && npm run dev
Environment Configuration:

Copy .env.example to .env:

Bash
cp .env.example .env
Configure your database settings (DB_DATABASE, DB_USERNAME, DB_PASSWORD) inside the .env file.

Generate App Key & Migrate:

Bash
php artisan key:generate
php artisan migrate
Create Storage Link: (Crucial for profile images)

Bash
php artisan storage:link
Run the Application:

Bash
php artisan serve
💻 Tech Stack
Framework: Laravel 8 (PHP)

Frontend: Bootstrap 5, FontAwesome, Custom Glassmorphism CSS

Database: MySQL

Assets: UI-Avatars (for default profile icons)

📄 License
This project is open-sourced software licensed under the MIT license.
