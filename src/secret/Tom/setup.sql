-- SQL script to set up the database for Tom's section
-- This script creates the database, table, and initializes like counters

-- Create the database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS schildflieger_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE schildflieger_web;

-- Create table for storing likes
CREATE TABLE IF NOT EXISTS media_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    media_filename VARCHAR(255) NOT NULL UNIQUE,
    like_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_media_filename (media_filename)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial records for all media files with zero likes
INSERT IGNORE INTO media_likes (media_filename, like_count) VALUES 
('Amena Tom.mp4', 0),
('Bild Theo guffi.jpeg', 0),
('Bild Theo Random.jpeg', 0),
('Bild Theo Schlafen.jpeg', 0),
('Bild Theo.jpeg', 0),
('Bild Tom klein.jpeg', 0),
('Bild Tom.jpeg', 0),
('Erik.jpeg', 0),
('Franzosen Tom.mp4', 0);

-- Display success message
SELECT 'Database setup completed successfully!' AS Message;
