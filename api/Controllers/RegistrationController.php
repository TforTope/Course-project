<?php
/**
 * Author:
 * Date: 6/2/2026
 * File: RegistrationController.php
 * Description: Defines the RegistrationController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Registration;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class RegistrationController {
    // Retrieve all registrations
    public function index(Request $request, Response $response, array $args) : Response {
        $results = Registration::getRegistrations();
        return Helper::withJson($response, $results, 200);
    }

    // View a specific registration by registration_id
    public function view(Request $request, Response $response, array $args) : Response {
        $results = Registration::getRegistrationById($args['registration_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View event for a registration
    public function viewEvent(Request $request, Response $response, array $args) : Response {
        $results = Registration::getEventByRegistration($args['registration_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View attendee for a registration
    public function viewAttendee(Request $request, Response $response, array $args) : Response {
        $results = Registration::getAttendeeByRegistration($args['registration_id']);
        return Helper::withJson($response, $results, 200);
    }
}