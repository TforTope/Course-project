USE eventhub_db;

INSERT INTO Users (username, email, password_hash, role) VALUES
                                                             ('admin01', 'admin@eventhub.com',
                                                              'hashed_password_1', 'admin'),
                                                             ('organizer01', 'organizer@eventhub.com',
                                                              'hashed_password_2', 'organizer'),
                                                             ('johnsmith', 'john@example.com',
                                                              'hashed_password_3', 'attendee'),
                                                             ('maryjones', 'mary@example.com',
                                                              'hashed_password_4', 'attendee');

INSERT INTO Venues (name, address, city, capacity) VALUES
                                                       ('Downtown Conference Center', '100 Main Street',
                                                        'Chicago', 500),
                                                       ('Innovation Hall', '25 Tech Avenue',
                                                        'New York', 300),
                                                       ('Community Arts Center', '75 Park Road',
                                                        'Boston', 200);

INSERT INTO Categories (name, description) VALUES
                                               ('Technology', 'Events related to software, IT,
                                                and innovation'),
                                               ('Music', 'Concerts and live music events'),
                                               ('Education', 'Workshops, lectures,
                                                and training events'),
                                               ('Business', 'Networking
                                                and professional events');

INSERT INTO Events
(title, description, event_date, event_time, status, venue_id, category_id, organizer_id)
VALUES
    ('Web Development Workshop', 'A beginner-friendly workshop on building web applications.',
     '2026-06-15', '10:00:00', 'scheduled', 2, 3, 2),
    ('Startup Networking Night', 'A networking event for entrepreneurs and investors.',
     '2026-07-01', '18:30:00', 'scheduled', 1, 4, 2),
    ('Summer Music Festival', 'An outdoor music event featuring local artists.',
     '2026-07-20', '16:00:00', 'scheduled', 3, 2, 2);

INSERT INTO Attendees (user_id, first_name, last_name, phone) VALUES
                                                                  (3, 'John', 'Smith',
                                                                   '555-111-2222'),
                                                                  (4, 'Mary', 'Jones',
                                                                   '555-333-4444');

INSERT INTO Registrations (event_id, attendee_id, ticket_type, status) VALUES
                                                                           (1, 1,
                                                                            'student', 'confirmed'),
                                                                           (2, 1,
                                                                            'general', 'confirmed'),
                                                                           (3, 2,
                                                                            'vip', 'pending');

