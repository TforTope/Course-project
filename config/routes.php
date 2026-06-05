<?php
/**
 * Author:
 * Date: 6/2/2026
 * File: routes.php
 * Description: Defines routes for EventHub API
 */

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Add an app route
    $app->get('/', function (Request $request, Response $response, array $args) {
        $response->getBody()->write('Welcome to EventHub-API');
        return $response;
    });

    // Add another test route
    $app->get('/api/hello/{name}', function (Request $request, Response $response, array $args) {
        $response->getBody()->write("Hello " . $args['name']);
        return $response;
    });

    // Route group api/v1 pattern
    $app->group('/api/v1', function (RouteCollectorProxy $group) {

        // Route group for /attendees pattern
        $group->group('/attendees', function (RouteCollectorProxy $group) {
            // Call the index method defined in the AttendeeController class
            // Attendee is the container key defined in dependencies.php.
            $group->get('', 'Attendee:index');
            $group->get('/{attendee_id}', 'Attendee:view');
            $group->get('/{attendee_id}/registrations', 'Attendee:viewRegistrations');
            $group->get('/{attendee_id}/events', 'Attendee:viewEvents');
            // Create, update, and delete attendee
            $group->post('', 'Attendee:create');
            $group->put('/{attendee_id}', 'Attendee:update');
            $group->delete('/{attendee_id}', 'Attendee:delete');
        });

        // Route group for /events pattern
        $group->group('/events', function (RouteCollectorProxy $group) {
            // Call the index method defined in the EventController class
            // Event is the container key defined in dependencies.php.
            $group->get('', 'Event:index');
            $group->get('/{event_id}', 'Event:view');
            $group->get('/{event_id}/organizer', 'Event:viewOrganizer');
            $group->get('/{event_id}/venue', 'Event:viewVenue');
            $group->get('/{event_id}/registrations', 'Event:viewRegistrations');
            $group->get('/{event_id}/attendees', 'Event:viewAttendees');
            // Create, update, and delete event
            $group->post('', 'Event:create');
            $group->put('/{event_id}', 'Event:update');
            $group->delete('/{event_id}', 'Event:delete');
        });

        // Route group for /organizers pattern
        $group->group('/organizers', function (RouteCollectorProxy $group) {
            // Call the index method defined in the OrganizerController class
            // Organizer is the container key defined in dependencies.php.
            $group->get('', 'Organizer:index');
            $group->get('/{organizer_id}', 'Organizer:view');
            $group->get('/{organizer_id}/events', 'Organizer:viewEvents');
        });

        // Route group for /registrations pattern
        $group->group('/registrations', function (RouteCollectorProxy $group) {
            // Call the index method defined in the RegistrationController class
            // Registration is the container key defined in dependencies.php.
            $group->get('', 'Registration:index');
            $group->get('/{registration_id}', 'Registration:view');
            $group->get('/{registration_id}/event', 'Registration:viewEvent');
            $group->get('/{registration_id}/attendee', 'Registration:viewAttendee');
            // Create, update, and delete registrations
            $group->post('', 'Registration:create');
            $group->put('/{registration_id}', 'Registration:update');
            $group->delete('/{registration_id}', 'Registration:delete');
        });

        // Route group for /venues pattern
        $group->group('/venues', function (RouteCollectorProxy $group) {
            // Call the index method defined in the VenueController class
            // Venue is the container key defined in dependencies.php.
            $group->get('', 'Venue:index');
            $group->get('/{venue_id}', 'Venue:view');
            $group->get('/{venue_id}/events', 'Venue:viewEvents');
            // Create, update, and delete venue
            $group->post('', 'Venue:create');
            $group->put('/{venue_id}', 'Venue:update');
            $group->delete('/{venue_id}', 'Venue:delete');
        });
    });
};