
📌 About the Project

The College Event Management System (CEMS) is a web-based application developed to make college event management simple and organized.

The system provides a platform where students can explore college events, register for events, and manage their registrations. Administrators can manage event details and monitor student participation.

This project was developed as part of an academic Database Management System (DBMS) project using PHP and MySQL.

🎯 Objectives

- To simplify the management of college events.
- To provide students with information about upcoming events.
- To allow students to register for college events online.
- To help administrators manage events and registrations.
- To understand database design and web application development.

✨ Features

👨‍🎓 Student Module

- Student registration and login.
- View available college events.
- View event details.
- Register for events.
- View personal event registrations.
- Submit feedback.

🛠️ Admin Module

- Admin login and dashboard.
- Add new events.
- Edit event details.
- Delete events.
- View and manage event registrations.
- View student information.

🗄️ Database Features

- MySQL database integration.
- Store and manage student information.
- Store event details and registrations.
- Manage feedback records.
- Login history management, if enabled in the current version.

💻 Technologies Used

Technology| Purpose
HTML| Structure of web pages
CSS| Styling and page layout
JavaScript| Client-side interactions
PHP| Server-side programming
MySQL| Database management
XAMPP| Local development server
phpMyAdmin| Database administration
Visual Studio Code| Code editor

⚙️ Software Requirements

- Windows operating system
- XAMPP
- PHP
- MySQL
- Visual Studio Code
- Google Chrome or another modern web browser

📂 Project Structure

CollegeEventManagement/
│
├── index.php
├── login.php
├── register.php
├── database.php
├── logout.php
├── style.css
├── script.js
├── college_event_management.sql
│
├── student/
│   ├── dashboard.php
│   ├── events.php
│   ├── events_details.php
│   ├── register_event.php
│   ├── my_registrations.php
│   └── feedback.php
│
├── admin/
│   ├── dashboard.php
│   ├── add_event.php
│   ├── edit_event.php
│   ├── delete_event.php
│   ├── events.php
│   ├── registrations.php
│   └── students.php
│
└── README.md

🗃️ Database Information

Database Name: "college_event_management_new"

The application uses MySQL to store and manage information related to users, events, registrations, and feedback.

The exact table names and structure depend on the SQL file included in the project.

🚀 Installation and Setup

Follow these steps to run the project locally.

Step 1: Install XAMPP

Download and install XAMPP on your computer.

Step 2: Copy the Project

Copy the project folder into the XAMPP "htdocs" directory.

Example:

C:\xampp\htdocs\CollegeEventManagement

Step 3: Start the Server

Open the XAMPP Control Panel and start:

- Apache
- MySQL

Step 4: Create the Database

1. Open "http://localhost/phpmyadmin/".
2. Create a database named "college_event_management_new".
3. Import the "college_event_management.sql" file if it is included and matches the current database structure.

Step 5: Configure Database Connection

Open "database.php" and check the database connection settings:

$host = "localhost";
$username = "root";
$password = "";
$database = "college_event_management_new";

Use the settings appropriate for your local MySQL installation.

Step 6: Run the Project

Open your browser and visit:

http://localhost/CollegeEventManagement/

📸 Screenshots

Screenshots of the home page, login page, student dashboard, admin dashboard, and event management pages can be added here.

To add a screenshot, upload the image to your repository and use this format:

![Home Page](screenshots/home.png)

🔐 Security Notes

- Use appropriate password hashing for user passwords.
- Validate user input on the server.
- Use prepared statements for database queries.
- Keep database credentials and sensitive information private.

🔮 Future Enhancements

- Email notifications for event registrations.
- Online certificate generation.
- Event search and filtering.
- Improved mobile-friendly interface.
- Event attendance tracking.
- Online feedback and reporting improvements.

📚 Learning Outcomes

Through this project, I learned about:

- Database design and management.
- PHP and MySQL connectivity.
- CRUD operations (Create, Read, Update, Delete).
- User authentication and role-based access.
- Developing dynamic web applications.
- Running and testing projects using XAMPP.

👩‍💻 Author

Neha Muneshwar

Project: College Event Management System (CEMS)
Project Type: Academic DBMS Project

📄 License

This project was developed for educational purposes. A license can be added if the project is distributed or reused publicly.
