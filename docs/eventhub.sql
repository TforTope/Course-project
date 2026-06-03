CREATE DATABASE eventhub_db;
USE eventhub_db;

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE organizers (
                            organizer_id INT NOT NULL AUTO_INCREMENT,
                            name VARCHAR(100) NOT NULL,
                            email VARCHAR(100) DEFAULT NULL,
                            phone VARCHAR(20) DEFAULT NULL,
                            organization_name VARCHAR(150) DEFAULT NULL,
                            PRIMARY KEY (organizer_id),
                            UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE venues (
                        venue_id INT NOT NULL AUTO_INCREMENT,
                        name VARCHAR(150) DEFAULT NULL,
                        location VARCHAR(200) DEFAULT NULL,
                        capacity INT DEFAULT NULL,
                        indoor TINYINT(1) DEFAULT NULL,
                        contact_email VARCHAR(100) DEFAULT NULL,
                        rental_fee DECIMAL(10,2) DEFAULT NULL,
                        parking_available TINYINT(1) DEFAULT NULL,
                        PRIMARY KEY (venue_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attendees (
                           attendee_id INT NOT NULL AUTO_INCREMENT,
                           name VARCHAR(100) DEFAULT NULL,
                           email VARCHAR(100) DEFAULT NULL,
                           phone VARCHAR(20) DEFAULT NULL,
                           date_of_birth DATE DEFAULT NULL,
                           PRIMARY KEY (attendee_id),
                           UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE events (
                        event_id INT NOT NULL AUTO_INCREMENT,
                        title VARCHAR(150) DEFAULT NULL,
                        description TEXT,
                        event_date DATE DEFAULT NULL,
                        organizer_id INT DEFAULT NULL,
                        venue_id INT DEFAULT NULL,
                        price DECIMAL(8,2) DEFAULT NULL,
                        max_capacity INT DEFAULT NULL,
                        category VARCHAR(50) DEFAULT NULL,
                        PRIMARY KEY (event_id),
                        KEY organizer_id (organizer_id),
                        KEY venue_id (venue_id),
                        CONSTRAINT events_ibfk_1
                            FOREIGN KEY (organizer_id)
                                REFERENCES organizers (organizer_id)
                                ON DELETE CASCADE,
                        CONSTRAINT events_ibfk_2
                            FOREIGN KEY (venue_id)
                                REFERENCES venues (venue_id)
                                ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE registrations (
                               registration_id INT NOT NULL AUTO_INCREMENT,
                               event_id INT DEFAULT NULL,
                               attendee_id INT DEFAULT NULL,
                               registration_date DATE DEFAULT NULL,
                               ticket_status VARCHAR(50) DEFAULT NULL,
                               payment_method VARCHAR(50) DEFAULT NULL,
                               amount_paid DECIMAL(8,2) DEFAULT NULL,
                               checked_in TINYINT(1) DEFAULT 0,
                               PRIMARY KEY (registration_id),
                               KEY event_id (event_id),
                               KEY attendee_id (attendee_id),
                               CONSTRAINT registrations_ibfk_1
                                   FOREIGN KEY (event_id)
                                       REFERENCES events (event_id)
                                       ON DELETE CASCADE,
                               CONSTRAINT registrations_ibfk_2
                                   FOREIGN KEY (attendee_id)
                                       REFERENCES attendees (attendee_id)
                                       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO organizers (organizer_id, name, email, phone, organization_name) VALUES
                                                                                 (1, 'Alex Johnson', 'alex@eventhub.com', '3171112222', 'AJ Events'),
                                                                                 (2, 'Maria Lopez', 'maria@events.com', '3172223333', 'ML Productions'),
                                                                                 (3, 'David Kim', 'david@kimco.com', '3173334444', 'KimCo'),
                                                                                 (4, 'Sarah Wilson', 'sarah@wilson.com', '3174445555', 'Wilson Group'),
                                                                                 (5, 'Chris Brown', 'chris@brown.com', '3175556666', 'Brown Events'),
                                                                                 (6, 'Priya Patel', 'priya@patelevent.com', '3176667777', 'Patel Events'),
                                                                                 (7, 'James Turner', 'james@turnerpro.com', '3177778888', 'Turner Productions'),
                                                                                 (8, 'Lisa Nguyen', 'lisa@nguyenco.com', '3178889999', 'Nguyen Co');

INSERT INTO venues (venue_id, name, location, capacity, indoor, contact_email, rental_fee, parking_available) VALUES
                                                                                                                  (1, 'Grand Hall', 'Indianapolis Downtown', 500, 1, 'grandhall@venues.com', 1200.00, 1),
                                                                                                                  (2, 'City Park', 'Indy Park District', 1000, 0, 'citypark@indyparks.com', 400.00, 1),
                                                                                                                  (3, 'Convention Center', 'West St', 2000, 1, 'info@indyconvention.com', 3500.00, 1),
                                                                                                                  (4, 'Riverside Venue', 'White River', 300, 0, 'riverside@venues.com', 750.00, 0),
                                                                                                                  (5, 'Tech Hub', 'IU Indianapolis', 200, 1, 'techhub@iupui.edu', 600.00, 1),
                                                                                                                  (6, 'Indy Ballroom', 'North Meridian St', 800, 1, 'ballroom@indyvenues.com', 2000.00, 1),
                                                                                                                  (7, 'Fountain Square', 'East Prospect St', 600, 0, 'events@fountainsq.com', 500.00, 0),
                                                                                                                  (8, 'Eagle Creek Pavilion', 'Eagle Creek Park', 450, 0, 'pavilion@eaglecreek.com', 300.00, 1);

INSERT INTO attendees (attendee_id, name, email, phone, date_of_birth) VALUES
                                                                           (1, 'John Carter', 'john@email.com', '3177771111', '1990-03-15'),
                                                                           (2, 'Emma Stone', 'emma@email.com', '3177772222', '1995-07-22'),
                                                                           (3, 'Liam Smith', 'liam@email.com', '3177773333', '1988-11-08'),
                                                                           (4, 'Olivia Brown', 'olivia@email.com', '3177774444', '2006-01-30'),
                                                                           (5, 'Noah Davis', 'noah@email.com', '3177775555', '2004-09-12'),
                                                                           (6, 'Sophia Lee', 'sophia@email.com', '3177776666', '1993-05-19'),
                                                                           (7, 'Mason Clark', 'mason@email.com', '3177777777', '2007-03-03'),
                                                                           (8, 'Isabella Hall', 'isabella@email.com', '3177778888', '1999-12-01'),
                                                                           (9, 'Ethan Wright', 'ethan@email.com', '3177779999', '1985-06-25'),
                                                                           (10, 'Ava Martinez', 'ava@email.com', '3177770000', '2002-08-14');

INSERT INTO events (event_id, title, description, event_date, organizer_id, venue_id, price, max_capacity, category) VALUES
                                                                                                                         (1, 'Tech Conference 2026', 'Annual technology conference', '2026-07-10', 1, 3, 150.00, 1800, 'Technology'),
                                                                                                                         (2, 'Music Festival', 'Outdoor music event', '2026-08-05', 2, 2, 75.00, 900, 'Music'),
                                                                                                                         (3, 'Startup Pitch Night', 'Pitch your startup ideas', '2026-06-15', 3, 5, 20.00, 180, 'Business'),
                                                                                                                         (4, 'Food Expo', 'Explore food vendors', '2026-07-25', 4, 1, 50.00, 450, 'Food & Drink'),
                                                                                                                         (5, 'Art Showcase', 'Local artists display work', '2026-06-30', 5, 4, 30.00, 250, 'Arts'),
                                                                                                                         (6, 'Wellness Summit', 'Health and wellness speakers', '2026-09-12', 6, 6, 60.00, 700, 'Health'),
                                                                                                                         (7, 'Comedy Night', 'Stand-up comedy showcase', '2026-08-20', 7, 7, 40.00, 500, 'Entertainment'),
                                                                                                                         (8, 'Indy Craft Beer Fest', 'Local craft beer tasting event', '2026-09-05', 8, 8, 55.00, 400, 'Food & Drink'),
                                                                                                                         (9, 'Photography Workshop', 'Learn portrait and landscape tips', '2026-07-18', 1, 5, 35.00, 150, 'Arts'),
                                                                                                                         (10, 'Career Fair 2026', 'Connect with top employers', '2026-10-01', 2, 3, 0.00, 1500, 'Business');

INSERT INTO registrations (registration_id, event_id, attendee_id, registration_date, ticket_status, payment_method, amount_paid, checked_in) VALUES
                                                                                                                                                  (1, 1, 1, '2026-05-01', 'Confirmed', 'Credit Card', 150.00, 0),
                                                                                                                                                  (2, 1, 2, '2026-05-02', 'Confirmed', 'PayPal', 150.00, 0),
                                                                                                                                                  (3, 2, 3, '2026-05-03', 'Pending', 'Credit Card', 0.00, 0),
                                                                                                                                                  (4, 3, 4, '2026-05-04', 'Confirmed', 'Debit Card', 20.00, 1),
                                                                                                                                                  (5, 4, 5, '2026-05-05', 'Cancelled', 'Credit Card', 0.00, 0),
                                                                                                                                                  (6, 5, 1, '2026-05-06', 'Confirmed', 'Cash', 30.00, 1),
                                                                                                                                                  (7, 2, 1, '2026-05-07', 'Confirmed', 'Venmo', 75.00, 0),
                                                                                                                                                  (8, 3, 2, '2026-05-08', 'Pending', 'PayPal', 0.00, 0),
                                                                                                                                                  (9, 4, 3, '2026-05-09', 'Confirmed', 'Credit Card', 50.00, 0),
                                                                                                                                                  (10, 5, 4, '2026-05-10', 'Confirmed', 'Debit Card', 30.00, 1),
                                                                                                                                                  (11, 6, 6, '2026-05-11', 'Confirmed', 'Credit Card', 60.00, 0),
                                                                                                                                                  (12, 7, 7, '2026-05-12', 'Confirmed', 'Venmo', 40.00, 0),
                                                                                                                                                  (13, 8, 8, '2026-05-13', 'Confirmed', 'Credit Card', 55.00, 0),
                                                                                                                                                  (14, 8, 9, '2026-05-14', 'Confirmed', 'Cash', 55.00, 0),
                                                                                                                                                  (15, 8, 10, '2026-05-15', 'Confirmed', 'PayPal', 55.00, 0),
                                                                                                                                                  (16, 8, 4, '2026-05-16', 'Pending', 'Debit Card', 0.00, 0),
                                                                                                                                                  (17, 9, 1, '2026-05-17', 'Confirmed', 'Credit Card', 35.00, 0),
                                                                                                                                                  (18, 9, 6, '2026-05-18', 'Confirmed', 'PayPal', 35.00, 0),
                                                                                                                                                  (19, 10, 2, '2026-05-19', 'Confirmed', 'Free', 0.00, 0),
                                                                                                                                                  (20, 10, 5, '2026-05-20', 'Confirmed', 'Free', 0.00, 0),
                                                                                                                                                  (21, 1, 8, '2026-05-21', 'Confirmed', 'Credit Card', 150.00, 0),
                                                                                                                                                  (22, 2, 9, '2026-05-22', 'Confirmed', 'Venmo', 75.00, 0),
                                                                                                                                                  (23, 3, 10, '2026-05-23', 'Confirmed', 'Debit Card', 20.00, 0),
                                                                                                                                                  (24, 6, 3, '2026-05-24', 'Pending', 'Credit Card', 0.00, 0),
                                                                                                                                                  (25, 7, 4, '2026-05-25', 'Confirmed', 'Cash', 40.00, 0);

ALTER TABLE attendees AUTO_INCREMENT = 11;
ALTER TABLE organizers AUTO_INCREMENT = 9;
ALTER TABLE venues AUTO_INCREMENT = 9;
ALTER TABLE events AUTO_INCREMENT = 11;
ALTER TABLE registrations AUTO_INCREMENT = 26;

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Database eventhub_db created successfully!' AS message;

SELECT COUNT(*) AS total_organizers FROM organizers;
SELECT COUNT(*) AS total_venues FROM venues;
SELECT COUNT(*) AS total_attendees FROM attendees;
SELECT COUNT(*) AS total_events FROM events;
SELECT COUNT(*) AS total_registrations FROM registrations;