# EventHub API Documentation

## Base URL

http://localhost/I425/Course-project/api/

## Resources

The API includes the following resources:

- Users
- Venues
- Categories
- Events
- Attendees
- Registrations
- Authentication


## Events Endpoints

### Get All Events
GET /events
Optional query parameters:
- search
- category
- city
- sort
- page
- limit

### Get Event
Example:
GET /events?search=workshop&sort=event_date&page=1&limit=10

Sample response:
{
"page": 1,
"limit": 10,
"total": 1,
"data": [
{
"event_id": 1,
"title": "Web Development Workshop",
"event_date": "2026-06-15",
"event_time": "10:00:00",
"status": "scheduled"
}
]
}
### Get Events by ID
GET /events/{id}


### Create Event
POST /events

Sample request:
{
"title": "AI Career Seminar",
"description": "A seminar about careers in artificial intelligence.",
"event_date": "2026-08-05",
"event_time": "14:00:00",
"venue_id": 1,
"category_id": 1,
"organizer_id": 2
}

### Update Event
PUT /events/{id}
### Delete Event
DELETE /events/{id}


## Users Endpoints

### Get All Users
GET /users
Optional query parameters:

- search
- role
- sort
- page
- limit

Example:
GET /users?role=organizer&page=1&limit=10
Sample response:

{
"page": 1,
"limit": 10,
"total": 2,
"data": [
{
"user_id": 1,
"full_name": "John Doe",
"email": "john@example.com",
"role": "organizer"
}
]
}

### Get User by ID
GET /users/{id}

### Create User
POST /users
Sample request:

{
"full_name": "Jane Smith",
"email": "jane@example.com",
"password": "password123",
"role": "attendee"
}

### Update User
PUT /users/{id}

### Delete User
DELETE /users/{id}


## Venues Endpoints

### Get All Venues
GET /venues
Optional query parameters:

- city
- search
- sort
- page
- limit

Example:
GET /venues?city=Indianapolis&page=1&limit=5

Sample response:
{
"page": 1,
"limit": 5,
"total": 1,
"data": [
{
"venue_id": 1,
"venue_name": "Downtown Convention Center",
"city": "Indianapolis",
"capacity": 500
}
]
}

### Get Venue by ID
GET /venues/{id}

### Create Venue
POST /venues

Sample request:
{
"venue_name": "Tech Hall",
"address": "123 Main Street",
"city": "Indianapolis",
"capacity": 300
}

### Update Venue
PUT /venues/{id}

### Delete Venue
DELETE /venues/{id}


## Categories Endpoints

### Get All Categories
GET /categories

Example response:
{
"data": [
{
"category_id": 1,
"category_name": "Technology"
},
{
"category_id": 2,
"category_name": "Business"
}
]
}

### Get Category by ID
GET /categories/{id}

### Create Category
POST /categories

Sample request:
{
"category_name": "Health"
}

### Update Category
PUT /categories/{id}

### Delete Category
DELETE /categories/{id}


## Attendees Endpoints

### Get All Attendees
GET /attendees
Optional query parameters:

- search
- page
- limit

Example:
GET /attendees?search=John&page=1&limit=10

Sample response:
{
"page": 1,
"limit": 10,
"total": 1,
"data": [
{
"attendee_id": 1,
"full_name": "John Doe",
"email": "john@example.com"
}
]
}

### Get Attendee by ID
GET /attendees/{id}

### Create Attendee
POST /attendees

Sample request:
{
"full_name": "Sarah Johnson",
"email": "sarah@example.com",
"phone_number": "3175551234"
}

### Update Attendee
PUT /attendees/{id}

### Delete Attendee
DELETE /attendees/{id}


## Registrations Endpoints

### Get All Registrations
GET /registrations
Optional query parameters:

- event_id
- attendee_id
- status
- page
- limit

Example:
GET /registrations?event_id=1&page=1&limit=10

Sample response:
{
"page": 1,
"limit": 10,
"total": 1,
"data": [
{
"registration_id": 1,
"event_id": 1,
"attendee_id": 1,
"registration_date": "2026-05-01",
"status": "confirmed"
}
]
}

### Get Registration by ID
GET /registrations/{id}

### Create Registration
POST /registrations

Sample request:
{
"event_id": 1,
"attendee_id": 2,
"status": "confirmed"
}

### Update Registration
PUT /registrations/{id}

### Delete Registration
DELETE /registrations/{id}


## Auth/signup Endpoints

## User Signup
POST /auth/signup

Sample request:
{
"full_name": "Michael Brown",
"email": "michael@example.com",
"password": "password123"
}

Sample response:
{
"message": "User registered successfully",
"user_id": 10
}


## Auth/login Endpoints

## User Login
POST /auth/login

Sample request:
{
"email": "michael@example.com",
"password": "password123"
}

Sample response:
{
"message": "Login successful",
"token": "jwt_token_here"
}