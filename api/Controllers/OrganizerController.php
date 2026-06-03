<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: OrganizerController.php
 * Description: Defines the OrganizerController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Organizer;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class OrganizerController {
    // Retrieve all organizers
    public function index(Request $request, Response $response, array $args) : Response {
        $results = Organizer::getOrganizers();
        return Helper::withJson($response, $results, 200);
    }

    // View a specific organizer by organizer_id
    public function view(Request $request, Response $response, array $args) : Response {
        $results = Organizer::getOrganizerById($args['organizer_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all events by an organizer
    public function viewEvents(Request $request, Response $response, array $args) : Response {
        $results = Organizer::getEventsByOrganizer($args['organizer_id']);
        return Helper::withJson($response, $results, 200);
    }
}