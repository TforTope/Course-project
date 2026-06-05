<?php
/**
 * Author:
 * Date: 6/4/2026
 * File: EventController.php
 * Description: Defines the EventController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Event;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class EventController {
    // List all events
    public function index(Request $request, Response $response, array $args): Response {
        $results = Event::getEvents($request);
        return Helper::withJson($response, $results, 200);
    }

    // View a specific event
    public function view(Request $request, Response $response, array $args): Response {
        $results = Event::getEventById($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View organizer of an event
    public function viewOrganizer(Request $request, Response $response, array $args): Response {
        $results = Event::getOrganizerByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View venue of an event
    public function viewVenue(Request $request, Response $response, array $args): Response {
        $results = Event::getVenueByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all registrations for an event
    public function viewRegistrations(Request $request, Response $response, array $args): Response {
        $results = Event::getRegistrationsByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all attendees for an event
    public function viewAttendees(Request $request, Response $response, array $args): Response {
        $results = Event::getAttendeesByEvent($args['event_id']);
        return Helper::withJson($response, $results, 200);
    }

    // Create an event
    public function create(Request $request, Response $response, array $args): Response {
        $event = Event::createEvent($request);

        if (!$event) {
            $results['status'] = "Event cannot be created.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Event has been created.",
            'data' => $event
        ];

        return Helper::withJson($response, $results, 201);
    }

    // Update an event
    public function update(Request $request, Response $response, array $args): Response {
        $event = Event::updateEvent($request);

        if (!$event) {
            $results['status'] = "Event cannot be updated.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Event has been updated.",
            'data' => $event
        ];

        return Helper::withJson($response, $results, 200);
    }

    // Delete an event
    public function delete(Request $request, Response $response, array $args): Response {
        $event = Event::deleteEvent($request);

        if (!$event) {
            $results['status'] = "Event cannot be deleted.";
            return Helper::withJson($response, $results, 500);
        }

        $results['status'] = "Event has been deleted.";

        return Helper::withJson($response, $results, 200);
    }
}