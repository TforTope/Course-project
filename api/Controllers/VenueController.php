<?php
/**
 * Author:
 * Date: 6/2/2026
 * File: VenueController.php
 * Description: Defines the VenueController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Controllers\ControllerHelper as Helper;
use EventHubAPI\Models\Venue;

class VenueController {
    // Retrieve all venues
    public function index(Request $request, Response $response, array $args) : Response {
        $results = Venue::getVenues($request);
        return Helper::withJson($response, $results, 200);
    }

    // View a venue
    public function view(Request $request, Response $response, array $args) : Response {
        $venue_id = $args['venue_id'];
        $results = Venue::getVenueById($venue_id);
        return Helper::withJson($response, $results, 200);
    }

    // View events hosted at a venue
    public function viewEvents(Request $request, Response $response, array $args) : Response {
        $results = Venue::getEventsByVenue($args['venue_id']);
        return Helper::withJson($response, $results, 200);
    }

    // Create a new venue
    public function create(Request $request, Response $response, array $args) : Response {
        $results = Venue::createVenue($request);
        return Helper::withJson($response, $results, 201);
    }

    // Update a venue
    public function update(Request $request, Response $response, array $args) : Response {
        $venue_id = $args['venue_id'];
        $results = Venue::updateVenue($request, $venue_id);
        return Helper::withJson($response, $results, 200);
    }

    // Delete a venue
    public function delete(Request $request, Response $response, array $args) : Response {
        $venue_id = $args['venue_id'];
        $results = Venue::deleteVenue($venue_id);
        return Helper::withJson($response, $results, 200);
    }
}