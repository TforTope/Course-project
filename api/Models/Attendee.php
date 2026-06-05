<?php
/**
 * Author:
 * Date: 6/2/2026
 * File: Attendee.php
 * Description: Defines the attendee model class.
 */

namespace EventHubAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model {
    // The table associated with this model
    protected $table = 'attendees';

    // The primary key of the table
    protected $primaryKey = 'attendee_id';

    // The PK is numeric
    public $incrementing = true;

    // The PK is an integer
    protected $keyType = 'int';

    // If the created_at and updated_at columns are not used
    public $timestamps = false;

    // Fields allowed for mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth'
    ];

    /*
     * Define the one-to-many relationship between Attendee and Registration.
     * One attendee can have many registrations.
     */
    public function registrations() {
        return $this->hasMany(Registration::class, 'attendee_id');
    }

    /*
     * Define the many-to-many relationship between Attendee and Event.
     * The registrations table links attendees and events.
     */
    public function events() {
        return $this->belongsToMany(Event::class, 'registrations', 'attendee_id', 'event_id');
    }

    // Retrieve all attendees
    public static function getAttendees() {
        $attendees = self::all();
        return $attendees;
    }

    // View a specific attendee
    public static function getAttendeeById(int $id) {
        $attendee = self::findOrFail($id);
        return $attendee;
    }

    // Get an attendee's registrations
    public static function getRegistrationsByAttendee(int $id) {
        return self::findOrFail($id)->registrations;
    }

    // Get an attendee's events
    public static function getEventsByAttendee(int $id) {
        return self::findOrFail($id)->events;
    }

    // Search for attendees
    public static function searchAttendees($term) {
        if (is_numeric($term)) {
            $query = self::where('attendee_id', '=', $term);
        } else {
            $query = self::where('name', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%")
                ->orWhere('phone', 'like', "%$term%");
        }

        return $query->get();
    }

    // Insert a new attendee
    public static function createAttendee($request) {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new Attendee instance
        $attendee = new Attendee();

        // Set the attendee's attributes
        foreach ($params as $field => $value) {
            $attendee->$field = $value;
        }

        // Insert the attendee into the database
        $attendee->save();

        return $attendee;
    }

    // Update an attendee
    public static function updateAttendee($request) {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Retrieve attendee_id from the request URL
        $id = $request->getAttribute('attendee_id');

        $attendee = self::findOrFail($id);

        if (!$attendee) {
            return false;
        }

        // Update attributes of the attendee
        foreach ($params as $field => $value) {
            $attendee->$field = $value;
        }

        // Save the attendee into the database
        $attendee->save();

        return $attendee;
    }

    // Delete an attendee
    public static function deleteAttendee($request) {
        // Retrieve attendee_id from the request
        $id = $request->getAttribute('attendee_id');

        $attendee = self::findOrFail($id);

        return ($attendee ? $attendee->delete() : $attendee);
    }
}