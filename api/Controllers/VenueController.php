<?php
/**
 * Author: TOPE_OLUSEGU
 * Date: 6/2/2026
 * File: VenueController.php
 * Description: Defines the VenueController class.
 */

namespace EventHubAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use EventHubAPI\Models\Venue;
use EventHubAPI\Controllers\ControllerHelper as Helper;

class VenueController {
    // Retrieve all venues
    public function index(Request $request, Response $response, array $args) : Response {
        $results = Venue::getVenues();
        return Helper::withJson($response, $results, 200);
    }

    // View a specific venue by venue_id
    public function view(Request $request, Response $response, array $args) : Response {
        $results = Venue::getVenueById($args['venue_id']);
        return Helper::withJson($response, $results, 200);
    }

    // View all events hosted at a venue
    public function viewEvents(Request $request, Response $response, array $args) : Response {
        $results = Venue::getEventsByVenue($args['venue_id']);
        return Helper::withJson($response, $results, 200);
    }
}