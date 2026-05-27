# EventHub Project Proposal

## Project Title

EventHub: Event Management System API and SPA Client

## Project Overview

EventHub is a web-based event management system that allows users to browse, create, manage, and register for events.
The system includes a RESTful API server connected to a MySQL database and a Single Page Application client that
consumes the API.

## Purpose

The purpose of this project is to demonstrate the design and development of a RESTful web service and a client
application that uses it. EventHub provides real-world functionality for managing events, venues, attendees, and 
registrations.

## API Server Description

The API server will expose RESTful endpoints for users, venues, categories, events, attendees, and registrations. 
It will support Create, Retrieve, Update, and Delete operations using HTTP methods such as GET, POST, PUT, and DELETE.

## Database Description

The system will use a MySQL relational database. The database contains six main tables: users, venues, categories,
events, attendees, and registrations. The events table connects to venues, categories, and organizers. 
The registrations table connects attendees and events, creating a many-to-many relationship.

## SPA Client Description

The SPA client will allow users to interact with the API through a browser-based interface. 
Users will be able to sign up, sign in, view events, search events, register for events, and manage records 
depending on their role.

## Advanced Features

The project will include authentication, role-based authorization, JWT security, search, sort, pagination,
validation, duplicate registration prevention, and event capacity checking.