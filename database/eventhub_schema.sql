CREATE DATABASE IF NOT EXISTS eventhub_db;
USE eventhub_db;

CREATE TABLE Users (
                       user_id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       email VARCHAR(100) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL,
                       role ENUM('admin', 'organizer', 'attendee') NOT NULL DEFAULT 'attendee',
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Venues (
                        venue_id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(100) NOT NULL,
                        address VARCHAR(255) NOT NULL,
                        city VARCHAR(100) NOT NULL,
                        capacity INT NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Categories (
                            category_id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(100) NOT NULL UNIQUE,
                            description TEXT
);

CREATE TABLE Events (
                        event_id INT AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(150) NOT NULL,
                        description TEXT,
                        event_date DATE NOT NULL,
                        event_time TIME NOT NULL,
                        status ENUM('scheduled', 'cancelled', 'completed') DEFAULT 'scheduled',
                        venue_id INT NOT NULL,
                        category_id INT NOT NULL,
                        organizer_id INT NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                        FOREIGN KEY (venue_id) REFERENCES venues(venue_id),
                        FOREIGN KEY (category_id) REFERENCES categories(category_id),
                        FOREIGN KEY (organizer_id) REFERENCES users(user_id)
);

CREATE TABLE Attendees (
                           attendee_id INT AUTO_INCREMENT PRIMARY KEY,
                           user_id INT NOT NULL UNIQUE,
                           first_name VARCHAR(50) NOT NULL,
                           last_name VARCHAR(50) NOT NULL,
                           phone VARCHAR(20),

                           FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE Registrations (
                               registration_id INT AUTO_INCREMENT PRIMARY KEY,
                               event_id INT NOT NULL,
                               attendee_id INT NOT NULL,
                               registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               ticket_type ENUM('general', 'vip', 'student') DEFAULT 'general',
                               status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed',

                               FOREIGN KEY (event_id) REFERENCES events(event_id),
                               FOREIGN KEY (attendee_id) REFERENCES attendees(attendee_id),

                               UNIQUE (event_id, attendee_id)
);

