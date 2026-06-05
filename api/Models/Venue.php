<?php
/**
 * Author:
 * Date: 6/2/2026
 * File: Venue.php
 * Description: Defines the venue model class with pagination, sorting, searching, create, update, and delete features.
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model {
    // The table associated with this model
    protected $table = 'venues';

    // The primary key of the table
    protected $primaryKey = 'venue_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Columns that can be inserted or updated
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'indoor',
        'contact_email',
        'rental_fee',
        'parking_available'
    ];

    // Define the one-to-many relationship between Venue and Event model classes
    // One venue can host many events
    public function events() {
        return $this->hasMany(Event::class, 'venue_id');
    }

    // Retrieve all venues with pagination, sorting, and searching
    public static function getVenues($request) {
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
        $query = self::with('events');

        /*********** code for searching *************************/
        // Search by venue name, location, or contact email
        if (array_key_exists('search', $params)) {
            $search = trim($params['search']);

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('location', 'LIKE', "%$search%")
                        ->orWhere('contact_email', 'LIKE', "%$search%");
                });
            }
        }

        // Optional exact indoor filter: indoor=1 or indoor=0
        if (array_key_exists('indoor', $params)) {
            $query->where('indoor', (int)$params['indoor']);
        }

        // Optional exact parking filter: parking_available=1 or parking_available=0
        if (array_key_exists('parking_available', $params)) {
            $query->where('parking_available', (int)$params['parking_available']);
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
        $venues = $query->get();

        // Construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $venues
        ];

        return $results;
    }

    // Retrieve a specific venue
    public static function getVenueById(int $id) {
        $venue = self::findOrFail($id);
        $venue->load('events');
        return $venue;
    }

    // View all events hosted at a venue
    public static function getEventsByVenue(int $id) {
        $events = self::findOrFail($id)->events;
        return $events;
    }

    // Create a new venue
    public static function createVenue($request) {
        $data = $request->getParsedBody();

        $venue = new self();

        $venue->name = $data['name'] ?? null;
        $venue->location = $data['location'] ?? null;
        $venue->capacity = $data['capacity'] ?? null;
        $venue->indoor = $data['indoor'] ?? null;
        $venue->contact_email = $data['contact_email'] ?? null;
        $venue->rental_fee = $data['rental_fee'] ?? null;
        $venue->parking_available = $data['parking_available'] ?? null;

        $venue->save();

        return $venue;
    }

    // Update an existing venue
    public static function updateVenue($request, int $id) {
        $data = $request->getParsedBody();

        $venue = self::findOrFail($id);

        if (array_key_exists('name', $data)) {
            $venue->name = $data['name'];
        }

        if (array_key_exists('location', $data)) {
            $venue->location = $data['location'];
        }

        if (array_key_exists('capacity', $data)) {
            $venue->capacity = $data['capacity'];
        }

        if (array_key_exists('indoor', $data)) {
            $venue->indoor = $data['indoor'];
        }

        if (array_key_exists('contact_email', $data)) {
            $venue->contact_email = $data['contact_email'];
        }

        if (array_key_exists('rental_fee', $data)) {
            $venue->rental_fee = $data['rental_fee'];
        }

        if (array_key_exists('parking_available', $data)) {
            $venue->parking_available = $data['parking_available'];
        }

        $venue->save();

        return $venue;
    }

    // Delete an existing venue
    public static function deleteVenue(int $id) {
        $venue = self::findOrFail($id);
        $venue->delete();

        return [
            'message' => 'Venue deleted successfully.',
            'venue_id' => $id
        ];
    }

    // Return an array of links for pagination.
    // The array includes links for the current, first, previous, next, and last pages.
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
     * Sort directions can be 'asc' or 'desc' and defaults to 'asc'.
     *
     * Examples:
     * sort=[venue_id:asc,name:desc]
     * sort=[name,capacity:desc]
     */
    private static function getSortKeys($request) {
        $sort_key_array = [];

        // Allowed columns for sorting
        $allowed_columns = [
            'venue_id',
            'name',
            'location',
            'capacity',
            'indoor',
            'contact_email',
            'rental_fee',
            'parking_available'
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

                // Only allow valid venue table columns
                if (in_array($column, $allowed_columns)) {
                    $sort_key_array[$column] = $direction;
                }
            }
        }

        return $sort_key_array;
    }
}