<?php
/**
 * Author:
 * Date: 6/4/2026
 * File: Event.php
 * Description: Defines the Event model class.
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    // The table associated with this model
    protected $table = 'events';

    // The primary key of the table
    protected $primaryKey = 'event_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Fields allowed for mass assignment
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'organizer_id',
        'venue_id',
        'price',
        'max_capacity',
        'category'
    ];

    // Define many-to-one relationship between Event and Organizer
    public function organizer() {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    // Define many-to-one relationship between Event and Venue
    public function venue() {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    // Define one-to-many relationship between Event and Registration
    public function registrations() {
        return $this->hasMany(Registration::class, 'event_id');
    }

    // Define many-to-many relationship between Event and Attendee through registrations
    public function attendees() {
        return $this->belongsToMany(
            Attendee::class,
            'registrations',
            'event_id',
            'attendee_id'
        );
    }

    // Retrieve all events with pagination, sorting, and searching
    public static function getEvents($request) {
        // Get querystring variables from URL
        $params = $request->getQueryParams();

        // Items per page
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10;

        // Offset of the first item
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0;

        // Avoid bad values
        if ($limit <= 0) {
            $limit = 10;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        // Build query
        $query = self::with('organizer', 'venue', 'registrations');

        /*********** code for searching *************************/
        // Search by title, description, or category
        if (array_key_exists('search', $params)) {
            $search = trim($params['search']);

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('category', 'LIKE', "%$search%");
                });
            }
        }

        // Also support q as a shorter search query, for example: /events?q=music
        if (array_key_exists('q', $params)) {
            $term = trim($params['q']);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'LIKE', "%$term%")
                        ->orWhere('description', 'LIKE', "%$term%")
                        ->orWhere('category', 'LIKE', "%$term%");
                });
            }
        }

        // Optional category filter
        if (array_key_exists('category', $params)) {
            $query->where('category', 'LIKE', "%" . $params['category'] . "%");
        }

        // Optional organizer filter
        if (array_key_exists('organizer_id', $params)) {
            $query->where('organizer_id', (int)$params['organizer_id']);
        }

        // Optional venue filter
        if (array_key_exists('venue_id', $params)) {
            $query->where('venue_id', (int)$params['venue_id']);
        }

        // Get total count after search/filter
        $count = $query->count();

        // Pagination links
        $links = self::getLinks($request, $limit, $offset, $count);

        /*********** code for sorting *************************/
        $sort_key_array = self::getSortKeys($request);

        // Sort the output by one or more columns
        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        // Limit the rows
        $query = $query->skip($offset)->take($limit);

        // Finally, run the query and get the results
        $events = $query->get();

        // Construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $events
        ];

        return $results;
    }

    // Retrieve a specific event
    public static function getEventById(int $id) {
        $event = self::findOrFail($id);
        $event->load('organizer', 'venue', 'registrations');
        return $event;
    }

    // View organizer of an event
    public static function getOrganizerByEvent(int $id) {
        $organizer = self::findOrFail($id)->organizer;
        return $organizer;
    }

    // View venue of an event
    public static function getVenueByEvent(int $id) {
        $venue = self::findOrFail($id)->venue;
        return $venue;
    }

    // View all registrations for an event
    public static function getRegistrationsByEvent(int $id) {
        $registrations = self::findOrFail($id)->registrations;
        return $registrations;
    }

    // View all attendees for an event
    public static function getAttendeesByEvent(int $id) {
        $attendees = self::findOrFail($id)->attendees;
        return $attendees;
    }

    // Search for events
    public static function searchEvents($term) {
        if (is_numeric($term)) {
            $query = self::where('event_id', '=', $term)
                ->orWhere('price', '>=', $term)
                ->orWhere('max_capacity', '>=', $term);
        } else {
            $query = self::where('title', 'like', "%$term%")
                ->orWhere('description', 'like', "%$term%")
                ->orWhere('category', 'like', "%$term%")
                ->orWhere('event_date', 'like', "%$term%");
        }

        return $query->get();
    }

    // Insert a new event
    public static function createEvent($request) {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new Event instance
        $event = new Event();

        // Set the event's attributes
        foreach ($params as $field => $value) {
            $event->$field = $value;
        }

        // Insert the event into the database
        $event->save();

        return $event;
    }

    // Update an event
    public static function updateEvent($request) {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Retrieve event_id from the request URL
        $id = $request->getAttribute('event_id');

        $event = self::findOrFail($id);

        if (!$event) {
            return false;
        }

        // Update attributes of the event
        foreach ($params as $field => $value) {
            $event->$field = $value;
        }

        // Save the event into the database
        $event->save();

        return $event;
    }

    // Delete an event
    public static function deleteEvent($request) {
        // Retrieve event_id from the request
        $id = $request->getAttribute('event_id');

        $event = self::findOrFail($id);

        return ($event ? $event->delete() : $event);
    }

    // Return an array of links for pagination.
    private static function getLinks($request, $limit, $offset, $count) {
        // Get request URI and parts
        $uri = $request->getUri();

        if ($port = $uri->getPort()) {
            $port = ':' . $port;
        }

        $base_url = $uri->getScheme() . "://" . $uri->getHost() . $port . $uri->getPath();

        // Calculate last offset
        $lastOffset = 0;

        if ($count > 0) {
            $lastOffset = $limit * (ceil($count / $limit) - 1);
        }

        // Construct links for pagination
        $links = [];

        $links[] = [
            'rel' => 'self',
            'href' => "$base_url?limit=$limit&offset=$offset"
        ];

        $links[] = [
            'rel' => 'first',
            'href' => "$base_url?limit=$limit&offset=0"
        ];

        if ($offset - $limit >= 0) {
            $prevOffset = $offset - $limit;

            $links[] = [
                'rel' => 'prev',
                'href' => "$base_url?limit=$limit&offset=$prevOffset"
            ];
        }

        if ($offset + $limit < $count) {
            $nextOffset = $offset + $limit;

            $links[] = [
                'rel' => 'next',
                'href' => "$base_url?limit=$limit&offset=$nextOffset"
            ];
        }

        $links[] = [
            'rel' => 'last',
            'href' => "$base_url?limit=$limit&offset=$lastOffset"
        ];

        return $links;
    }

    /*
     * Sort keys are optionally enclosed in [ ], separated with commas.
     * Sort directions can be optionally appended to each sort key, separated by :.
     *
     * Examples:
     * sort=[event_id:asc,title:desc]
     * sort=[event_date:asc,price:desc]
     */
    private static function getSortKeys($request) {
        $sort_key_array = [];

        // Allowed columns for sorting
        $allowed_columns = [
            'event_id',
            'title',
            'event_date',
            'organizer_id',
            'venue_id',
            'price',
            'max_capacity',
            'category'
        ];

        // Get querystring variables from URL
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            // Remove white spaces, [, and ]
            $sort = preg_replace('/^\[|]$|\s+/', '', $params['sort']);

            // Get all the key:direction pairs
            $sort_keys = explode(',', $sort);

            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;

                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }

                $column = trim($column);
                $direction = strtolower(trim($direction));

                // Only allow valid directions
                if ($direction !== 'asc' && $direction !== 'desc') {
                    $direction = 'asc';
                }

                // Only allow valid event table columns
                if (in_array($column, $allowed_columns)) {
                    $sort_key_array[$column] = $direction;
                }
            }
        }

        return $sort_key_array;
    }
}