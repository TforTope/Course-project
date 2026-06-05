<?php
/**
 * Author:
 * Date: 6/4/2026
 * File: AttendeeController.php
 * Description: Defines the AttendeeController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Attendee;
use EventHubAPI\Controllers\ControllerHelper as Helper;
use EventHubAPI\Validation\Validator;

class AttendeeController
{
    // List all attendees
    public function index(Request $request, Response $response, array $args): Response
    {
        // Get querystring variables from URL
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : "";

        // Call the model method to get attendees
        $results = ($term) ? Attendee::searchAttendees($term) : Attendee::getAttendees();

        return Helper::withJson($response, $results, 200);
    }

    // View a specific attendee
    public function view(Request $request, Response $response, array $args): Response
    {
        $results = Attendee::getAttendeeById($args['attendee_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all registrations of an attendee
    public function viewRegistrations(Request $request, Response $response, array $args): Response
    {
        $attendee_id = $args['attendee_id'];
        $results = Attendee::getRegistrationsByAttendee($attendee_id);
        return Helper::withJson($response, $results, 200);
    }

    // View all events of an attendee
    public function viewEvents(Request $request, Response $response, array $args): Response
    {
        $attendee_id = $args['attendee_id'];
        $results = Attendee::getEventsByAttendee($attendee_id);
        return Helper::withJson($response, $results, 200);
    }

    // Create an attendee
    public function create(Request $request, Response $response, array $args): Response
    {
        // Validate the request
        $validation = Validator::validateAttendee($request);

        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return Helper::withJson($response, $results, 400);
        }

        // Create a new attendee
        $attendee = Attendee::createAttendee($request);

        if (!$attendee) {
            $results['status'] = "Attendee cannot be created.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Attendee has been created.",
            'data' => $attendee
        ];

        return Helper::withJson($response, $results, 201);
    }

    // Update an attendee
    public function update(Request $request, Response $response, array $args): Response
    {
        // Validate the request
        $validation = Validator::validateAttendee($request);

        // If validation failed
        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];

            return Helper::withJson($response, $results, 400);
        }

        $attendee = Attendee::updateAttendee($request);

        if (!$attendee) {
            $results['status'] = "Attendee cannot be updated.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Attendee has been updated.",
            'data' => $attendee
        ];

        return Helper::withJson($response, $results, 200);
    }

    // Delete an attendee
    public function delete(Request $request, Response $response, array $args): Response
    {
        $attendee = Attendee::deleteAttendee($request);

        if (!$attendee) {
            $results['status'] = "Attendee cannot be deleted.";
            return Helper::withJson($response, $results, 500);
        }

        $results['status'] = "Attendee has been deleted.";

        return Helper::withJson($response, $results, 200);
    }
}