<?php
/**
 * Author:
 * Date: 6/4/2026
 * File: Organizer.php
 * Description: Defines the Organizer model class.
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model {
    // The table associated with this model
    protected $table = 'organizers';

    // The primary key of the table
    protected $primaryKey = 'organizer_id';

    // The PK is numeric and auto-incrementing
    public $incrementing = true;

    // The primary key type
    protected $keyType = 'int';

    // If created_at and updated_at columns are not used
    public $timestamps = false;

    // Fields allowed for mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization_name'
    ];

    // Define one-to-many relationship between Organizer and Event
    public function events() {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    // Retrieve all organizers with pagination, sorting, and searching
    public static function getOrganizers($request) {
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

        // Searching by name, email, phone, or organization name
        if (array_key_exists('search', $params)) {
            $search = trim($params['search']);

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%")
                        ->orWhere('organization_name', 'LIKE', "%$search%");
                });
            }
        }

        // Also support q as a shorter search query
        if (array_key_exists('q', $params)) {
            $term = trim($params['q']);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'LIKE', "%$term%")
                        ->orWhere('email', 'LIKE', "%$term%")
                        ->orWhere('phone', 'LIKE', "%$term%")
                        ->orWhere('organization_name', 'LIKE', "%$term%");
                });
            }
        }

        // Get total count after search/filter
        $count = $query->count();

        // Pagination links
        $links = self::getLinks($request, $limit, $offset, $count);

        // Sorting
        $sort_key_array = self::getSortKeys($request);

        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        // Limit rows
        $query = $query->skip($offset)->take($limit);

        // Retrieve organizers
        $organizers = $query->get();

        // Construct response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $organizers
        ];

        return $results;
    }

    // Retrieve a specific organizer
    public static function getOrganizerById(int $id) {
        $organizer = self::findOrFail($id);
        $organizer->load('events');
        return $organizer;
    }

    // View all events organized by an organizer
    public static function getEventsByOrganizer(int $id) {
        $events = self::findOrFail($id)->events;
        return $events;
    }

    // Search for organizers
    public static function searchOrganizers($term) {
        if (is_numeric($term)) {
            $query = self::where('organizer_id', '=', $term)
                ->orWhere('phone', 'like', "%$term%");
        } else {
            $query = self::where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%")
                ->orWhere('organization_name', 'like', "%$term%");
        }

        return $query->get();
    }

    // Insert a new organizer
    public static function createOrganizer($request) {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new Organizer instance
        $organizer = new Organizer();

        // Set the organizer's attributes
        foreach ($params as $field => $value) {
            $organizer->$field = $value;
        }

        // Insert organizer into the database
        $organizer->save();

        return $organizer;
    }

    // Update an organizer
    public static function updateOrganizer($request) {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Retrieve organizer_id from the request URL
        $id = $request->getAttribute('organizer_id');

        $organizer = self::findOrFail($id);

        if (!$organizer) {
            return false;
        }

        // Update attributes of the organizer
        foreach ($params as $field => $value) {
            $organizer->$field = $value;
        }

        // Save organizer into the database
        $organizer->save();

        return $organizer;
    }

    // Delete an organizer
    public static function deleteOrganizer($request) {
        // Retrieve organizer_id from request
        $id = $request->getAttribute('organizer_id');

        $organizer = self::findOrFail($id);

        return ($organizer ? $organizer->delete() : $organizer);
    }

    // Return an array of links for pagination
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
     * Sort examples:
     * sort=[organizer_id:asc,name:desc]
     * sort=[organization_name:asc]
     */
    private static function getSortKeys($request) {
        $sort_key_array = [];

        // Allowed columns for sorting
        $allowed_columns = [
            'organizer_id',
            'name',
            'email',
            'phone',
            'organization_name'
        ];

        // Get querystring variables from URL
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            // Remove white spaces, [, and ]
            $sort = preg_replace('/^\[|]$|\s+/', '', $params['sort']);

            // Get all key:direction pairs
            $sort_keys = explode(',', $sort);

            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;

                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }

                $column = trim($column);
                $direction = strtolower(trim($direction));

                if ($direction !== 'asc' && $direction !== 'desc') {
                    $direction = 'asc';
                }

                if (in_array($column, $allowed_columns)) {
                    $sort_key_array[$column] = $direction;
                }
            }
        }

        return $sort_key_array;
    }
}