<?php
/**
 * Author:
 * Date: 6/4/2026
 * File: OrganizerController.php
 * Description: Defines the OrganizerController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Organizer;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class OrganizerController {
    // List all organizers
    public function index(Request $request, Response $response, array $args): Response {
        $results = Organizer::getOrganizers($request);
        return Helper::withJson($response, $results, 200);
    }

    // View a specific organizer
    public function view(Request $request, Response $response, array $args): Response {
        $results = Organizer::getOrganizerById($args['organizer_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View events created by an organizer
    public function viewEvents(Request $request, Response $response, array $args): Response {
        $results = Organizer::getEventsByOrganizer($args['organizer_id']);
        return Helper::withJson($response, $results, 200);
    }

    // Create an organizer
    public function create(Request $request, Response $response, array $args): Response {
        $organizer = Organizer::createOrganizer($request);

        if (!$organizer) {
            $results['status'] = "Organizer cannot be created.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Organizer has been created.",
            'data' => $organizer
        ];

        return Helper::withJson($response, $results, 201);
    }

    // Update an organizer
    public function update(Request $request, Response $response, array $args): Response {
        $organizer = Organizer::updateOrganizer($request);

        if (!$organizer) {
            $results['status'] = "Organizer cannot be updated.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Organizer has been updated.",
            'data' => $organizer
        ];

        return Helper::withJson($response, $results, 200);
    }

    // Delete an organizer
    public function delete(Request $request, Response $response, array $args): Response {
        $organizer = Organizer::deleteOrganizer($request);

        if (!$organizer) {
            $results['status'] = "Organizer cannot be deleted.";
            return Helper::withJson($response, $results, 500);
        }

        $results['status'] = "Organizer has been deleted.";

        return Helper::withJson($response, $results, 200);
    }
}