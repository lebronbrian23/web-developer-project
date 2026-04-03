CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    script TEXT,
    country ENUM('Canada','USA') NOT NULL,
    state_or_province VARCHAR(255) NOT NULL,
    reference_file_path VARCHAR(255) NOT NULL,
    budget ENUM('Low','Medium','High') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)