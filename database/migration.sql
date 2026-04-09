-- ----------------------------------------------------------------------------
-- Voices Job Form - Database Migration
-- To migrate Run: mysql -u root -p voices_job_form < database/migration.sql
-- ----------------------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS voices_job_form
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE voices_job_form;

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    script TEXT,
    country ENUM('CA','US') NOT NULL,
    state_or_province VARCHAR(255) NOT NULL,
    reference_file_path VARCHAR(255) NULL,
    budget ENUM('low','medium','high') NOT NULL,
    ip_address VARCHAR(45) NULL COMMENT 'IPv4 or IPv6',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_budget (budget),
    INDEX idx_country (country)
) ENGINE=InnoDB 
 DEFAULT CHARSET=utf8mb4 
 COLLATE=utf8mb4_unicode_ci;
