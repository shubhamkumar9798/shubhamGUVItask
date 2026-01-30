# User Management System - Internship Project

A complete user registration and profile management system built with HTML, CSS, JavaScript (jQuery), PHP, MySQL, MongoDB, and Redis.

## Features

- User Registration
- User Login with session management
- Profile viewing and editing
- Session management using Redis
- User data stored in MySQL
- Profile details stored in MongoDB

## Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5, jQuery
- **Backend**: PHP
- **Databases**: 
  - MySQL (User credentials)
  - MongoDB (Profile details)
  - Redis (Session management)

## Prerequisites

Before running this project, make sure you have the following installed:

1. **XAMPP/WAMP/MAMP** (or any Apache + PHP + MySQL environment)
2. **MongoDB** (Community Edition)
3. **Redis Server**
4. **PHP Extensions**:
   - mysqli
   - mongodb (install via PECL: `pecl install mongodb`)
   - redis (install via PECL: `pecl install redis`)

## Installation Steps

### 1. Setup Apache Server

1. Copy the entire `internship-project` folder to your web server directory:
   - XAMPP: `C:/xampp/htdocs/`
   - WAMP: `C:/wamp64/www/`
   - MAMP: `/Applications/MAMP/htdocs/`

### 2. Setup MySQL Database

1. Open phpMyAdmin or MySQL command line
2. Import the database schema:
   ```sql
   source /path/to/internship-project/database/schema.sql
   ```
   Or manually run the SQL commands from `database/schema.sql`

3. Update database credentials in `config/config.php` if needed

### 3. Setup MongoDB

1. Start MongoDB service:
   ```bash
   mongod
   ```

2. The application will automatically create the database and collection when first profile is updated

### 4. Setup Redis

1. Start Redis server:
   ```bash
   redis-server
   ```

2. Verify Redis is running:
   ```bash
   redis-cli ping
   ```
   Should return: `PONG`

### 5. Configure PHP Extensions

1. Open your `php.ini` file
2. Enable these extensions:
   ```ini
   extension=mysqli
   extension=mongodb
   extension=redis
   ```

3. Restart Apache server

## Running the Application

1. Make sure all services are running:
   - Apache Server
   - MySQL Server
   - MongoDB Server
   - Redis Server

2. Open your web browser and navigate to:
   ```
   http://localhost/internship-project/register.html
   ```

3. Follow the flow:
   - Register a new account
   - Login with your credentials
   - View and update your profile

## Project Structure

```
internship-project/
│
├── config/
│   └── config.php              # Configuration settings
│
├── css/
│   └── style.css               # Custom styles
│
├── database/
│   ├── db.php                  # MySQL connection
│   ├── mongodb.php             # MongoDB connection
│   ├── redis.php               # Redis connection
│   └── schema.sql              # Database schema
│
├── js/
│   ├── register.js             # Registration logic
│   ├── login.js                # Login logic
│   └── profile.js              # Profile management logic
│
├── php/
│   ├── register.php            # Registration handler
│   ├── login.php               # Login handler
│   ├── profile.php             # Profile data fetcher
│   ├── update_profile.php      # Profile update handler
│   └── logout.php              # Logout handler
│
├── register.html               # Registration page
├── login.html                  # Login page
├── profile.html                # Profile page
└── README.md                   # This file
```

## Key Features Implementation

### Security Features
- Prepared statements for SQL queries (prevents SQL injection)
- Password hashing using PHP's `password_hash()`
- Session tokens for authentication
- Input validation on both client and server side

### Session Management
- Sessions stored in Redis with expiration time (1 hour)
- Session tokens stored in browser's localStorage
- Automatic logout on session expiry

### Data Storage
- **MySQL**: Stores user credentials (username, email, hashed password)
- **MongoDB**: Stores profile details (age, DOB, contact)
- **Redis**: Stores active sessions

## Troubleshooting

### Common Issues

1. **"Connection failed" error**
   - Check if MySQL/MongoDB/Redis services are running
   - Verify database credentials in `config/config.php`

2. **"Call to undefined function" error**
   - Install missing PHP extensions (mongodb, redis)
   - Restart Apache after enabling extensions

3. **AJAX requests not working**
   - Check browser console for errors
   - Verify file paths are correct
   - Ensure Apache is running

4. **Sessions not persisting**
   - Check if Redis is running
   - Verify localStorage is enabled in browser
   - Check browser console for errors

## Notes

- The project uses jQuery AJAX for all backend communications (no form submissions)
- All code is properly separated (HTML, CSS, JS, PHP in separate files)
- Bootstrap is used for responsive design
- No PHP sessions are used; session management is handled via Redis and localStorage
- All SQL queries use prepared statements

## Support

If you encounter any issues, please check:
1. All services (Apache, MySQL, MongoDB, Redis) are running
2. PHP extensions are properly installed
3. File paths and database credentials are correct
4. Browser console for JavaScript errors

---

**Developed for Internship Application**
# shubhamGUVItask
# shubhamGUVItask
