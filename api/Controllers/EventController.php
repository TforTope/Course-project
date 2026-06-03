<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: EventController.php
 * Description: Defines the EventController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Event;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class EventController {
    // Retrieve all events
    public function index(Request $request, Response $response, array $args) : Response {
        $results = Event::getEvents();
        return Helper::withJson($response, $results, 200);
    }

    // View a specific event by event_id
    public function view(Request $request, Response $response, array $args) : Response {
        $results = Event::getEventById($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View organizer of an event
    public function viewOrganizer(Request $request, Response $response, array $args) : Response {
        $results = Event::getOrganizerByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View venue of an event
    public function viewVenue(Request $request, Response $response, array $args) : Response {
        $results = Event::getVenueByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all registrations for an event
    public function viewRegistrations(Request $request, Response $response, array $args) : Response {
        $results = Event::getRegistrationsByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all attendees for an event
    public function viewAttendees(Request $request, Response $response, array $args) : Response {
        $results = Event::getAttendeesByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }
}