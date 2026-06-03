<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: AttendeeController.php
 * Description: Defines the AttendeeController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Attendee;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class AttendeeController {
    // Retrieve all attendees
    public function index(Request $request, Response $response, array $args) : Response {
        $results = Attendee::getAttendees();
        return Helper::withJson($response, $results, 200);
    }

    // View a specific attendee by attendee_id
    public function view(Request $request, Response $response, array $args) : Response {
        $results = Attendee::getAttendeeById($args['attendee_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all registrations for an attendee
    public function viewRegistrations(Request $request, Response $response, array $args) : Response {
        $results = Attendee::getRegistrationsByAttendee($args['attendee_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all events for an attendee
    public function viewEvents(Request $request, Response $response, array $args) : Response {
        $results = Attendee::getEventsByAttendee($args['attendee_id']);
        return Helper::withJson($response, $results, 200);
    }
}