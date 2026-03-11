# Geo-Health-Connect
 An Integrated Digital  Platform for Location-Based Specialist Discovery and Real-Time Emergency Response
 # Health_v3 - Patient Registration System

This project is a web-based portal for patient registration and login. It is designed to run efficiently in **VS Code** using a PHP environment.

## 📂 Project Structure Explained
- **`user_registration.html`**: The frontend form where patients enter their Name, Email, and Password.
- **`user_register.php`**: The "Brain" of the registration. It connects to the database, checks if the user already exists, hashes the password for security, and saves the data.
- **`user_login.html`**: The frontend login page.
- **`user_login.php`**: Verifies the patient's credentials against the database to allow access.

## 🛠️ How it Works (Logic Flow)
1. **Data Entry**: User fills out the HTML form.
2. **Validation**: The PHP script checks if the email is already in the `health_v3` database.
3. **Security**: Passwords are encrypted using `PASSWORD_DEFAULT` so they are never stored as plain text.
4. **Feedback**: JavaScript `alert()` popups tell the user if they succeeded or if they need to try a different email.



## 💻 Running in VS Code
To run this project, you need:
1. **PHP Installed**: Your system must have PHP added to the 'Path' environment variable.
2. **VS Code Extensions**: 
   - `PHP Server`: Used to launch the project (Right-click > Serve project).
   - `MySQL`: Used to view your database tables directly in VS Code.

## 🛡️ Database Schema
- **Database**: `health_v3`
- **Table**: `users`
- **Columns**: `id` (Primary Key), `name`, `email` (Unique), `password` (Hashed).
