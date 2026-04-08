# Voices Job Submission Form - Web Developer Take-Home Assignment

A full-stack web application for submitting voice acting job opportunities with field requirements, server-side validation, email confirmations, and comprehensive logging. Built with PHP, MySQL, and Cypress for end-to-end testing.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Setup Instructions](#setup-instructions)
- [Database Schema](#database-schema)
- [Running the Application](#running-the-application)
- [Testing](#testing)
- [API Endpoints](#api-endpoints)
- [Logging System](#logging-system)
- [Form Validation](#form-validation)
- [Error Handling](#error-handling)
- [Security Features](#security-features)
- [Development Notes](#development-notes)

---

## Project Overview

The **Voices Job Submission Form** is a professional web application designed to collect voice acting job opportunities from users. The application features:

- A responsive HTML form with real-time client-side validation
- Comprehensive server-side validation using a custom Validator service
- Email confirmations sent to users upon successful submission
- File upload support for reference materials (PDFs, images, audio, video)
- Centralized logging system for form submissions, database operations, and email activity
- Full test coverage with PHPUnit (13 tests) and Cypress E2E tests (19 tests)
- Database persistence using MySQL with proper error handling

**Purpose:** This take-home assignment demonstrates full-stack development skills including form handling, validation, database operations, email integration, comprehensive testing, and code organization.

---

## Features

### Form Functionality
- ✅ **Job Title Input** - Required text field (max 255 characters)
- ✅ **Script/Description** - Optional textarea with real-time word counter (max 1000 words)
- ✅ **Country Selection** - Dynamic country dropdown (Canada, US, expandable)
- ✅ **State/Province Selection** - Dynamically populated based on selected country
- ✅ **Budget Selection** - Radio buttons with 3 pricing tiers:
  - Low: $5–$99
  - Medium: $100–$249
  - High: $250–$499
- ✅ **File Upload** - Optional reference file upload (20MB max)
  - Supported formats: PDF, DOC, DOCX, TXT, MP3, WAV, IMG, JPEG, JPG, PNG, MP4, MPEG
  - Files stored securely outside web root in `/storage/uploads/`
  - Access controlled via FileDownloadController

### Form Features
- ✅ **Form Validation** - Real-time client-side + server-side validation
- ✅ **Error Messaging** - User-friendly error messages for each field
- ✅ **Reset Button** - Clear all fields with one click
- ✅ **CSRF Protection** - Anti-CSRF token on every form submission
- ✅ **Honeypot Field** - Bot detection using hidden "website" field
- ✅ **Accessibility** - WCAG 2.1 Level AA+: ARIA labels, semantic HTML, role="alert" for errors, natural keyboard navigation, skip link, 7:1 color contrast, 44px touch targets
- ✅ **Email Confirmation** - Automated email sent upon successful submission
- ✅ **Responsive Design** - Mobile-first (480px → 600px tablet → 768px desktop) with enhanced touch targets and tablet-specific optimizations

### Backend Features
- ✅ **Database Persistence** - All submissions stored in MySQL
- ✅ **Comprehensive Logging** - Form submissions, database ops, email activity logged to files
- ✅ **Error Handling** - Graceful error handling with user-friendly feedback
- ✅ **Service-Oriented Architecture** - Validator, Mailer, Logger, FileUpload services
- ✅ **PDO Prepared Statements** - SQL injection prevention
- ✅ **Environment Configuration** - Flexible .env configuration

---

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Language** | PHP | 8.2.29 |
| **Database** | MySQL | 8.0+ |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript (ES6+) | Latest |
| **Testing (Unit/Integration)** | PHPUnit | 10.5.63 |
| **Testing (E2E)** | Cypress | 15.13.0 |
| **Package Manager** | Composer | Latest |
| **Architecture Pattern** | MVC (Model-View-Controller) | Custom Implementation |
| **HTTP Method** | POST | Form submission |
| **File Storage** | Local filesystem | `/storage/uploads/` (secure, outside web root) |
| **Email** | PHP mail() function | Native |

---

## Project Structure

```
voices-web-developer-project/
├── app/
│   ├── config.php                    # App config autoload
│   ├── Controllers/
│   │   └── JobFormController.php     # Main form submission handler
│   ├── Models/
│   │   └── Job.php                   # Job data model
│   ├── Repositories/
│   │   └── JobRepository.php         # Database operations for jobs
│   ├── Services/
│   │   ├── Logger.php                # Centralized logging service
│   │   ├── Validator.php             # Form validation rules
│   │   ├── Mailer.php                # Email confirmation service
│   │   └── FileUpload.php            # File upload handling (storage/uploads)
│   ├── Views/
│   │   └── form.php                  # Main form template
│   └── helpers.php                   # Global logging helper functions
├── config/
│   └── Database.php                  # Database singleton & PDO connection
├── database/
│   └── migration.sql                 # Database schema (run first!)
├── public/
│   ├── index.php                     # Application entry point
│   ├── script.js                     # Client-side form logic
│   ├── styles.css                    # Form styling with tablet optimization
│   ├── uploads/                      # Old uploads (blocked by .htaccess)
│   └── Css/styles.css                # Main stylesheet with responsive design
├── storage/
│   ├── uploads/                      # Secure file storage (outside web root)
│   └── .gitignore                    # Prevents uploaded files from git
├── cypress/
│   ├── e2e/
│   │   └── form.cy.js                # E2E test suite (19 tests)
│   ├── fixtures/
│   │   └── example.json              # Test fixtures
│   └── support/
│       ├── commands.js               # Custom Cypress commands
│       └── e2e.js                    # Cypress configuration
├── tests/
│   ├── feature/                      # Feature tests
│   └── unit/
│       └── SampleTest.php            # Unit test example
├── logs/                             # Application logs (auto-created)
│   ├── form.log
│   ├── database.log
│   ├── email.log
│   └── errors.log
├── composer.json                     # PHP dependencies
├── phpunit.xml                       # PHPUnit configuration
├── cypress.config.js                 # Cypress configuration
├── php.ini.dev                       # Development PHP config (20MB upload limit)
├── .env                              # Environment variables
└── README.md                         # This file
```

---

## Setup Instructions

### Prerequisites
- PHP 8.2+ with CLI support
- MySQL 8.0+ database server
- Node.js 16+ and npm (for Cypress)
- Composer for PHP dependency management
- A code editor (VS Code recommended)

### 1. Clone the Repository
```bash
git clone <repository-url>
cd voices-web-developer-project
```

### 2. Install PHP Dependencies
```bash
composer install
composer dump-autoload
```

### 3. Setup Environment Variables
Create a `.env` file in the project root:
```bash
cp .env.example .env  # If available, or create manually
```

Configure `.env` with:
```env
DB_HOST=localhost
DB_NAME=voices_job_form
DB_USER=root
DB_PASSWORD=your_password
MAIL_TO=your_email@example.com
```

### 4. Create and Migrate Database
```bash
# Create the database in MySQL
mysql -u root -p -e "CREATE DATABASE voices_job_form CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
mysql -u root -p voices_job_form < database/migration.sql
```

Verify tables were created:
```bash
mysql -u root -p -e "USE voices_job_form; SHOW TABLES;"
```

### 5. Set File Permissions
```bash
# Create and permission storage directory for uploads
mkdir -p storage/uploads
chmod 755 storage/uploads

# Block direct access to old uploads (add .htaccess if using Apache)
echo '<FilesMatch ".*">\n  Order Allow,Deny\n  Deny from all\n</FilesMatch>' > public/uploads/.htaccess

# Make logs directory writable
mkdir -p logs
chmod 755 logs
```

### 7. Configure PHP Development Settings (File Upload Support)

This step is **optional but recommended** for testing large file uploads during development.

**What it does:** Creates a local PHP configuration file that allows file uploads up to 20MB (instead of the system's default 2MB).

#### Option A: Let Us Create It (Automated)
```bash
# The php.ini.dev file already exists in the project root
# It's configured with 20MB upload limits for development
cat php.ini.dev
```

#### Option B: Create It Manually
If you need to create or recreate the `php.ini.dev` file:

```bash
# Create the file in the project root
touch php.ini.dev

# Add the content (use your preferred editor, or run this)
cat > php.ini.dev << 'EOF'
; Custom PHP Configuration for Development
; This overrides the system php.ini for larger file uploads

post_max_size = 20M
upload_max_filesize = 20M
EOF
```

**What's in `php.ini.dev`:**
```ini
; Custom PHP Configuration for Development
; This overrides the system php.ini for larger file uploads

post_max_size = 20M
upload_max_filesize = 20M
```

### 8. Install Frontend Dependencies (for Cypress)
```bash
npm install
```

### 9. Start the Development Server
```bash
# Option 1: Using PHP built-in server
php -S localhost:8000 -t public

# Option 2: Using Apache (if configured)
# Visit: http://localhost/voices-web-developer-project/public
```

Application is now accessible at: **http://localhost:8000**

---

## Database Schema

### Jobs Table
```sql
CREATE TABLE jobs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    script LONGTEXT NULL,
    country CHAR(2) NOT NULL,
    state_or_province VARCHAR(100) NOT NULL,
    reference_file_path VARCHAR(255) NULL,
    budget ENUM('low', 'medium', 'high') NOT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_country (country),
    INDEX idx_created_at (created_at)
);
```

**Fields Explanation:**
- `id` - Unique submission identifier
- `title` - Job title/position name (required)
- `script` - Full job description (optional, nullable)
- `country` - Two-letter country code (CA, US)
- `state_or_province` - State/province name for the position
- `reference_file_path` - Path to uploaded reference file (nullable)
- `budget` - Budget tier (low/medium/high)
- `ip_address` - User's IP address for tracking
- `created_at` - Submission timestamp
- `updated_at` - Last update timestamp

---

## Running the Application

### Start Development Server

**Recommended (with 20MB file upload support):**
```bash
php -c php.ini.dev -S localhost:8000 -t public
```
✅ Enables full file upload validation for files up to 20MB
✅ Client-side validation works perfectly
✅ Server-side validation displays proper error messages

**Alternative (without php.ini.dev):**
```bash
php -S localhost:8000 -t public
```
⚠️ Files 0-2MB work fine
⚠️ Files 2-20MB may show generic PHP errors (not app errors)
⚠️ Validation still works, but UX is less polished

### Access the Application
1. Open browser: **http://localhost:8000**
2. Fill out the job submission form
3. Upload optional reference file (if desired)
4. Click "Submit"
5. Check email for confirmation (sent to configured MAIL_TO address)

### View Logs
Logs are automatically created in the `/logs/` directory:
```bash
tail -f logs/form.log      # Form submission activity
tail -f logs/database.log  # Database insert operations
tail -f logs/email.log     # Email send confirmations
tail -f logs/errors.log    # Application errors
```

### File Upload Validation

The application has **3 layers of file upload protection**:

#### 1. **Client-Side JavaScript Validation** (runs immediately)
- Checks if file > 20MB
- Shows user-friendly error before sending
- Prevents large files from reaching server

#### 2. **PHP Configuration Limits** (controlled by php.ini.dev)
- `post_max_size = 20M`
- `upload_max_filesize = 20M`
- Rejects files exceeding these limits at PHP level

#### 3. **Server-Side Validator**
- Checks MIME type (PDF, TXT, MP3, PNG, JPEG, etc.)
- Validates file size programmatically
- Displays proper error messages on form

**Supported File Types:**
- Images: JPEG, PNG
- Documents: PDF, DOC, DOCX, TXT
- Audio: MP3, WAV, MP4
- Video: MP4, MPEG

**Max File Size:** 20MB (both soft limit enforced by app and hard limit in php.ini.dev)

---

## Testing

### PHPUnit - Unit & Integration Tests
```bash
# Run all tests
php vendor/bin/phpunit tests/

# Run specific test file
php vendor/bin/phpunit tests/unit/SampleTest.php



**Test Results:** **13/13 tests passing**

### Cypress - End-to-End Tests
```bash
# Run all E2E tests (headless)
npx cypress run --spec cypress/e2e/form.cy.js

# Open Cypress Test Runner (interactive)
npx cypress open

# Run with specific browser
npx cypress run --browser chrome
```

**Test Results:** **19/19 tests passing**

### What Gets Tested

**Backend Tests (PHPUnit):**
- Form validation rules
- Database insertion
- File upload handling
- Validator service methods
- Error scenarios

**E2E Tests (Cypress):**
- Form page loading
- All form fields display correctly
- Field input and interaction
- Country/province dropdown dynamics
- Budget selection
- File upload acceptance
- Form reset functionality
- CSRF token presence
- Honeypot field inclusion
- Accessibility attributes
- Placeholder and helper text

---

## API Endpoints

### Form Submission Endpoint
**POST** `/index.php`

**Request Parameters:**
```
title                - Required, string, max 255 chars
script               - Optional, string, max 1000 words
country              - Required, 'CA' or 'US'
state_or_province    - Required, valid for selected country
budget               - Required, 'low', 'medium', or 'high'
reference_file_path  - Optional, file upload (max 20MB)
csrf_token           - Required, auto-generated token
website              - Honeypot (must be empty)
```

**Success Response:**
- HTTP 302 Redirect to form page
- Email confirmation sent to user
- Flash message: "Job details submitted successfully!"

**Error Response:**
- HTTP 200 with form re-rendered
- Validation error messages displayed
- Form data preserved (except file)

---

## Logging System

### Architecture
The logging system uses a centralized `Logger` service with global helper functions, reducing code duplication and providing consistent log formatting.

### Global Helper Functions
All functions available globally after autoload:

```php
log_info($message)                      // General info logging
log_database($message, $success, $data) // Database operations
log_email($message, $success, $data)    // Email activity
log_form($message)                      // Form submissions
log_error($message)                     // Error logging
section($title)                         // Create log section header
```

### Usage Examples

```php
// Log form submission
log_form(section('FORM SUBMISSION RECEIVED'));
log_form("POST Data: " . Logger::data($_POST));

// Log database operation
log_database("INSERT SUCCESSFUL - ID: {$id}", true, $record);
log_database("INSERT FAILED - " . $error, false, $record);

// Log email activity
log_email("EMAIL SENT TO: user@example.com", true, $submission);

// Create section header
section('VALIDATION ERRORS')
// Output: "=== VALIDATION ERRORS ==="
```

### Log Files
Each log type writes to its own file with timestamps and JSON-formatted data:

- **form.log** - Form submissions, validation, processing
- **database.log** - Insert/update operations with data
- **email.log** - Email send attempts and results
- **errors.log** - Application errors and exceptions

---

## Form Validation

### Client-Side Validation (JavaScript)
Runs in real-time as user types:
- **Title**: Required, max 255 characters
- **Script**: Optional, max 1000 words with live counter
- **Country**: Required selection
- **State/Province**: Required, dynamic population per country
- **Budget**: Required radio button selection
- **File**: Optional, type and size validation

### Server-Side Validation (PHP Validator)
Runs on form submission, prevents invalid data storage:

```php
// Validation rules defined in app/Services/Validator.php
$rules = [
    'title'              => 'required|string|max:255',
    'script'             => 'optional|string|max_words:1000',
    'country'            => 'required|in:CA,US',
    'state_or_province'  => 'required|valid_for_country',
    'budget'             => 'required|in:low,medium,high',
    'reference_file_path'=> 'optional|file|max_size:20000000'
];
```

### Validation Error Handling
- Errors displayed under respective form fields
- Form data preserved for user correction
- File not preserved (security measure)
- User can fix and resubmit

---

## Security Features

### Protection Mechanisms Implemented
1. **CSRF Token Protection** - Anti-CSRF token generated and validated on every submission
2. **Honeypot Field** - Hidden "website" field catches bot submissions
3. **PDO Prepared Statements** - All database queries use parameterized statements
4. **File Upload Validation** - Whitelist of allowed file types, max size limits
5. **Server-Side Validation** - Never trust client-side input alone
6. **Input Sanitization** - Data cleaned before database storage
7. **SQL Injection Prevention** - PDO binding with proper placeholders
8. **AODA Compliance** - Accessibility features for users with disabilities

### Security Best Practices
- Sensitive configuration in `.env` (not in code)
- Uploaded files stored outside web root access (if possible)
- Error messages logged but not exposed to users
- IP address captured for fraud detection
- Database timestamps (created_at, updated_at) for audit trail

---

## Error Handling

### Form Validation Errors
User-friendly messages displayed in red text:
```
"Title is required"
"Script cannot exceed 1000 words"
"Country is required"
"Please select a valid province"
"Invalid file type. Allowed: PDF, Images, Audio, Video"
```

### Application Errors
- Logged to `errors.log` for developer debugging
- User sees generic "Something went wrong" message
- No sensitive details exposed in UI

### Database Errors
- PDO exceptions caught and logged
- User notified of submission failure
- Error details available to administrator in logs

---

## Development Notes

### Key Design Decisions

1. **MVC Architecture** - Separation of concerns (Model, View, Controller)
2. **Service Layer** - Validator, Mailer, Logger, FileUpload as reusable services
3. **Repository Pattern** - JobRepository handles all database operations
4. **Global Helpers** - Short function names for logging reduce code verbosity
5. **Centralized Logging** - Single Logger class prevents duplicate code
6. **Client + Server Validation** - UX (instant feedback) + security (server verification)

### File Upload Flow
1. User selects file in form
2. Client validates: type + size
3. Form submitted with file
4. Server validates file again
5. File moved to `/public/uploads/`
6. File path stored in database
7. File path sent in confirmation email

### Email Confirmation Flow
1. Form submitted successfully
2. Database insert completes
3. Email composed with submission details
4. Email sent via `mail()` function
5. Send attempt logged to `email.log`
6. User sees success message
7. Confirmation email arrives in inbox

### Testing Strategy
- **Unit Tests** - Individual methods (Validator, database operations)
- **Integration Tests** - Full form submission workflow
- **E2E Tests** - User interactions with form (Cypress)
- **Coverage** - 13 backend tests + 19 E2E tests = comprehensive validation

### Adding New Countries/Provinces
Edit `app/Services/Validator.php` PROVINCES constant:
```php
const PROVINCES = [
    'CA' => [
        'ON' => 'Ontario',
        'QC' => 'Quebec',
        // Add more...
    ],
    'US' => [
        'NY' => 'New York',
        // Add more...
    ]
];
```

Then update form country dropdown in `app/Views/form.php`.

---

## Support & Troubleshooting

### Common Issues

**Database Connection Error**
```
Error: SQLSTATE[HY000]: General error: Unable to open file
```
- Verify MySQL is running: `mysql -u root -p -e "SELECT 1"`
- Check `.env` credentials
- Ensure database `voices_job_form` exists

**Uploads Directory Permission Denied**
```
Error: Permission denied writing to uploads/
```
```bash
chmod 755 public/uploads
chmod 755 logs
```

**Composer Autoload Issues**
```
Class not found errors
```
```bash
composer dump-autoload
```

**Cypress Tests Failing**
```bash
# Clear cache and reinstall
rm -rf node_modules
npm install

# Run in debug mode
npx cypress run --spec cypress/e2e/form.cy.js --headed
```

**Email Not Sending**
- Check `logs/email.log` for attempts
- Verify MAIL_TO in `.env` is valid
- Ensure server supports `mail()` function
- Check with hosting provider if using shared hosting

---

## Project Statistics

| Metric | Value |
|--------|-------|
| PHP LOC | ~800 lines |
| JavaScript LOC | ~300 lines |
| CSS LOC | ~400 lines |
| Test Coverage | 32 tests (13 unit + 19 E2E) |
| Forms | 1 (Job Submission) |
| Database Tables | 1 (jobs) |
| API Endpoints | 1 (POST /) |
| Services | 4 (Validator, Mailer, Logger, FileUpload) |
| Time to Setup | ~15 minutes |

---

## Verification Checklist

After setup, verify everything works:

- [ ] PHP server starts without errors: `php -S localhost:8000 -t public`
- [ ] Form loads at: http://localhost:8000
- [ ] Database connection successful: `mysql -u root -p voices_job_form -e "SELECT * FROM jobs;"`
- [ ] All PHPUnit tests pass: `php vendor/bin/phpunit tests/` (13/13)
- [ ] All Cypress tests pass: `npx cypress run --spec cypress/e2e/form.cy.js` (19/19)
- [ ] Form submission creates database record
- [ ] Confirmation email received after submission
- [ ] Log files created and populated in `/logs/` directory
- [ ] File upload works (if testing with a file)

---

## Skills Demonstrated

This project showcases proficiency in:
- ✅ Full-stack PHP development
- ✅ MySQL database design and operations
- ✅ Client-side JavaScript (ES6+) 
- ✅ Form validation (client + server)
- ✅ RESTful principles
- ✅ Security best practices (CSRF, honeypot, sanitization)
- ✅ Email integration
- ✅ File upload handling
- ✅ Comprehensive testing (unit + E2E)
- ✅ Code organization and architecture
- ✅ Error handling and logging
- ✅ Responsive design
- ✅ AODA accessibility compliance

---

